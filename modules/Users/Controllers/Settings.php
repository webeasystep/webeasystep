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
            return redirect()->to('/login')->with('error', 'سجّل دخولك أول، وبعدها كل إعداداتك بتكون جاهزة لك.');
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

        // Fetch user devices for security tab & ensure current device is registered
        /** @var \Modules\Users\Models\UserDeviceModel $deviceModel */
        $deviceModel = model(\Modules\Users\Models\UserDeviceModel::class);
        
        $agent = $this->request->getUserAgent();
        $deviceKey = md5((string)$agent);
        $deviceName = ($agent->getBrowser() ?: 'Browser') . ' on ' . ($agent->getPlatform() ?: 'Device');
        
        $deviceModel->registerOrUpdateDevice(
            $user->id,
            $deviceKey,
            $deviceName,
            (string)$agent,
            $this->request->getIPAddress(),
            session_id()
        );

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
            return redirect()->to('/login')->with('error', 'سجّل دخولك أول، وبعدها نكمل تحديث بياناتك بكل سهولة.');
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
                    return redirect()->back()->withInput()->with('errors', ['email' => 'هذا البريد مستخدم من قبل. جرّب بريدًا آخر أو سجّل دخولك مباشرة.']);
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

            return redirect()->back()->with('success', 'تم تحديث ملفك الشخصي بنجاح، وكل شيء صار محدث وجاهز.');
        } catch (\Exception $e) {
            log_message('error', 'Profile update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'صار خلل أثناء تحديث الملف الشخصي. جرّب مرة ثانية، وإذا تكرر إحنا معك.');
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
            return redirect()->to('/login')->with('error', 'سجّل دخولك أول، وبعدها نضبط كلمة المرور معك.');
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
                return redirect()->back()->with('error', 'كلمة المرور الحالية ما طابقت. تأكد منها وجرّب مرة ثانية.');
            }

            // Update password using Shield's user provider
            $userProvider = auth()->getProvider();
            $userEntity = $userProvider->findById($user->id);

            // Hash the new password
            $userEntity->password = $newPassword;

            if ($userProvider->save($userEntity)) {
                return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح، وحسابك الآن أكثر أمانًا.');
            } else {
                return redirect()->back()->with('error', 'ما قدرنا نغيّر كلمة المرور الآن. جرّب بعد شوي ونكمل معك.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Password change error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'صار خلل أثناء تغيير كلمة المرور. جرّب مرة ثانية، وإذا احتجت إحنا معك.');
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
            return redirect()->to('/login')->with('error', 'سجّل دخولك أول، وبعدها نحدّث صورتك الشخصية.');
        }

        // Initialize FireUploader
        $fireUploader = new \App\Libraries\FireUploader();

        try {
            // Use FireUploader to handle the upload
            $uploadResult = $fireUploader->upload_photos($this->usersModel, 'avatar', $user->id);

            if ($uploadResult) {
                return redirect()->to('/settings')->with('success', 'تم تحديث صورتك الشخصية بنجاح، وصار حسابك أرتب وأوضح.');
            } else {
                return redirect()->back()->with('error', 'ما قدرنا نرفع الصورة الآن. جرّب صورة ثانية أو أعد المحاولة بعد شوي.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Avatar upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'صار خلل أثناء رفع الصورة. جرّب مرة ثانية، وإذا استمرت المشكلة بنضبطها معك.');
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
            return redirect()->to('/login')->with('error', 'سجّل دخولك أول، وبعدها تقدر تدير صورتك الشخصية بكل سهولة.');
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
                    return redirect()->back()->with('success', 'تم حذف الصورة الشخصية بنجاح.');
                } else {
                    return redirect()->back()->with('error', 'ما قدرنا نحذف الصورة من النظام الآن. جرّب مرة ثانية، وإذا لزم الأمر نساعدك.');
                }
            } else {
                return redirect()->back()->with('error', 'ما فيه صورة شخصية محفوظة حتى نحذفها.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Avatar deletion error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'صار خلل أثناء حذف الصورة الشخصية. جرّب مرة ثانية، وإذا احتجت إحنا معك.');
        }
    }
}
