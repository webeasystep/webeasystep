<?php

namespace Modules\Users\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Modules\Users\Models\UsersModel;
use App\Libraries\FireUploader;

class Settings extends BaseController
{
    protected $usersModel;
    protected $fireUploader;
    protected $userIdentityModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->fireUploader = new FireUploader();
        $this->userIdentityModel = new \Modules\Users\Models\UserIdentityModel();
    }

    /**
     * Display user settings page
     */
    public function index()
    {
        // Use Shield's auth helper to get current user
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // Get user data from the users model
        $userData = $this->usersModel->find($user->id);

        // Fetch mobile from auth_identities
        $mobileIdentity = $this->userIdentityModel->getIdentityByType($user, 'mobile_password');
        if ($mobileIdentity) {
            $userData->mobile = $mobileIdentity->secret;
        }

        // Fetch email from auth_identities
        $emailIdentity = $this->userIdentityModel->getIdentityByType($user, 'email_password');
        if ($emailIdentity) {
            $userData->email = $emailIdentity->secret;
        }

        // Fetch user devices for security tab
        /** @var \Modules\Users\Models\UserDeviceModel $deviceModel */
        $deviceModel = model(\Modules\Users\Models\UserDeviceModel::class);
        $userDevices = $deviceModel->where('user_id', $user->id)->orderBy('updated_at', 'DESC')->findAll();

        $data = [
            'title' => 'إعدادات الحساب',
            'user' => $userData,
            'userDevices' => $userDevices
        ];

        return view('site/settings', $data);
    }

    /**
     * Update user profile information
     */
    public function updateProfile()
    {
        // Use Shield's auth helper to get current user
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required|min_length[2]|max_length[100]',
            // We use Shield's identity rules to check email uniqueness properly or custom logic
            'email' => 'permit_empty|valid_email',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $email = $this->request->getPost('email');
        
        try {
            // Check if the email provided is valid and unique in auth_identities (except for current user)
            if (!empty($email)) {
                $existingIdentity = $this->userIdentityModel->where('type', 'email_password')
                                          ->where('secret', $email)
                                          ->where('user_id !=', $user->id)
                                          ->first();
                if ($existingIdentity) {
                    return redirect()->back()->withInput()->with('errors', ['email' => 'هذا البريد الإلكتروني مستخدم بالفعل']);
                }
            }

            // Update full_name in the usersModel
            $updateData = [
                'full_name' => $this->request->getPost('full_name'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->usersModel->update($user->id, $updateData);
            
            // Update or create email in auth_identities
            if (!empty($email)) {
                $emailIdentity = $this->userIdentityModel->getIdentityByType($user, 'email_password');
                if ($emailIdentity) {
                    // Update existing
                    $this->userIdentityModel->update($emailIdentity->id, ['secret' => $email]);
                } else {
                    // Create new
                    $this->userIdentityModel->insert([
                        'user_id' => $user->id,
                        'type' => 'email_password',
                        'secret' => $email,
                        'secret2' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), // Dummy password since Shield expects one for this type
                        'expires' => null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح!');
        } catch (\Exception $e) {
            log_message('error', 'Profile update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي.');
        }
    }

    /**
     * Change user password
     */
    public function changePassword()
    {
        // Use Shield's auth helper to get current user
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        try {
            // Verify current password using Shield's password verification
            if (!password_verify($currentPassword, $user->password_hash)) {
                return redirect()->back()->with('error', 'كلمة المرور الحالية غير صحيحة.');
            }

            // Update password using Shield's user provider
            $userProvider = auth()->getProvider();
            $userEntity = $userProvider->findById($user->id);

            // Hash the new password
            $userEntity->password = $newPassword;

            if ($userProvider->save($userEntity)) {
                return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح!');
            } else {
                return redirect()->back()->with('error', 'فشل في تغيير كلمة المرور.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Password change error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تغيير كلمة المرور.');
        }
    }

    /**
     * Upload profile picture
     */
    public function uploadAvatar()
    {
        // Use Shield's auth helper to get current user
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // Initialize FireUploader
        $fireUploader = new \App\Libraries\FireUploader();

        try {
            // Use FireUploader to handle the upload
            $uploadResult = $fireUploader->upload_photos($this->usersModel, 'avatar', $user->id);

            if ($uploadResult) {
                return redirect()->to('/settings')->with('success', 'تم تحديث صورة الملف الشخصي بنجاح!');
            } else {
                return redirect()->back()->with('error', 'فشل في رفع صورة الملف الشخصي.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Avatar upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفع الصورة: ' . $e->getMessage());
        }
    }

    /**
     * Delete profile picture
     */
    public function deleteAvatar()
    {
        // Use Shield's auth helper to get current user
        $user = auth()->user();
        if (!$user) {
            return redirect()->to('/login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        try {
            // Get user data to check for existing avatar
            $userData = $this->usersModel->find($user->id);

            if ($userData && $userData->avatar) {
                // Delete the avatar file if it exists
                $avatarPath = FCPATH . 'uploads/avatars/' . $userData->avatar;
                if (file_exists($avatarPath)) {
                    unlink($avatarPath);
                }

                // Update database to remove avatar reference
                if ($this->usersModel->update($user->id, ['avatar' => null])) {
                    return redirect()->back()->with('success', 'تم حذف صورة الملف الشخصي بنجاح!');
                } else {
                    return redirect()->back()->with('error', 'فشل في حذف صورة الملف الشخصي من قاعدة البيانات.');
                }
            } else {
                return redirect()->back()->with('error', 'لا توجد صورة ملف شخصي لحذفها.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Avatar deletion error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف صورة الملف الشخصي.');
        }
    }
}
