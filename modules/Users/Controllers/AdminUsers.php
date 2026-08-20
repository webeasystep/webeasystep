<?php

namespace Modules\Users\Controllers;

use App\Controllers\BaseController;
use App\Libraries\UserType;
use App\Libraries\DtTable;
use App\Libraries\FireUploader;
use Modules\Users\Models\UsersModel;

class AdminUsers extends BaseController
{
    protected UsersModel $users;
    protected array $rules;
    protected FireUploader $fireUploader;

    public function __construct()
    {
        $this->fireUploader = new FireUploader();
        $this->users = new UsersModel();
        $this->rules = [
            "full_name" => ['label' => lang("Users.full_name"), 'rules' => "required"],
            "user_type" => ['label' => lang("Users.user_type"), 'rules' => "required|in_list[1,2]"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('Users.users_List');
        $data['add_url'] = ADMIN_URL . 'users/add';

        if ($this->request->isAJAX()) {
            $usersModel = $this->users
                ->select("users.id, users.full_name, users.user_type, CASE WHEN users.user_type = 2 THEN 'محاضر' ELSE 'طالب' END as user_type_label, ident_email.secret as email, ident_mobile.secret as mobile, users.status, users.active, users.created_at")
                ->join('auth_identities as ident_email', 'ident_email.user_id = users.id AND ident_email.type = "email_password"', 'left')
                ->join('auth_identities as ident_mobile', 'ident_mobile.user_id = users.id AND ident_mobile.type IN ("mobile_password", "mobile_number")', 'left')
                ->builder();

            DtTable::hideColumns(['id', 'user_type']);
            DtTable::searchableColumns(['users.full_name', 'ident_email.secret', 'ident_mobile.secret', 'users.user_type']);
            DtTable::orderableColumns(['full_name', 'users.user_type', 'email', 'mobile', 'status', 'active', 'created_at']);
            DtTable::setShowColumns('full_name,user_type_label,email,mobile,status,active,created_at');
            DtTable::setColumnSwitch('active');
            DtTable::setColumnSwitch('status');
            DtTable::hideActions(['delete'], ['id' => 1]);

            DtTable::changeColumn('full_name', function ($value, $row) {
                $name = esc($value ?: 'غير محدد');
                $phone = $row['mobile'] ?? '';

                $waUrl = null;
                if (!empty($phone)) {
                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                    if (str_starts_with($cleanPhone, '00')) $cleanPhone = substr($cleanPhone, 2);
                    if (str_starts_with($cleanPhone, '05') && strlen($cleanPhone) === 10) $cleanPhone = '966' . substr($cleanPhone, 1);
                    elseif (str_starts_with($cleanPhone, '01') && strlen($cleanPhone) === 11) $cleanPhone = '20' . substr($cleanPhone, 1);
                    $waUrl = 'https://wa.me/' . $cleanPhone;
                }

                $html = '<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">';
                $html .= '<span class="font-weight-bold text-dark"><i class="fas fa-user-circle text-primary ml-1"></i> ' . $name . '</span>';
                if ($waUrl) {
                    $html .= '<a href="' . esc($waUrl) . '" target="_blank" class="btn btn-sm shadow-sm" style="background-color: #25D366; color: #fff; border-radius: 15px; padding: 2px 8px; font-size: 11px; text-decoration: none;" title="محادثة واتساب: ' . esc($phone) . '">';
                    $html .= '<i class="fab fa-whatsapp ml-1"></i> واتساب';
                    $html .= '</a>';
                }
                $html .= '</div>';
                return $html;
            });

            DtTable::changeColumn('created_at', function ($value) {
                if (empty($value)) return '-';
                return '<span class="small text-muted" dir="ltr">' . esc(date('Y-m-d H:i', strtotime($value))) . '</span>';
            });
            
            $output = DtTable::tableRender($usersModel, false);

            return $this->response->setJSON($output);
        } else {
            return view('Modules\Users\Views\Admin\index', $data);
        }
    }

    /**
     * Students List View & DataTable (user_type = 1)
     */
    public function students()
    {
        $data['title'] = 'إدارة الطلاب';
        $data['add_url'] = ADMIN_URL . 'students/add';

        if ($this->request->isAJAX()) {
            $usersModel = $this->users
                ->select("users.id, users.full_name, COALESCE(users.email, ident_email.secret) as email, COALESCE(users.mobile, ident_mobile.secret) as mobile, users.status, users.active, users.created_at")
                ->join('auth_identities as ident_email', 'ident_email.user_id = users.id AND ident_email.type = "email_password"', 'left')
                ->join('auth_identities as ident_mobile', 'ident_mobile.user_id = users.id AND ident_mobile.type IN ("mobile_password", "mobile_number")', 'left')
                ->where('users.user_type', 1)
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['users.full_name', 'ident_email.secret', 'ident_mobile.secret', 'users.email', 'users.mobile']);
            DtTable::orderableColumns(['full_name', 'email', 'mobile', 'status', 'active', 'created_at']);
            DtTable::setShowColumns('full_name,email,mobile,status,active,created_at');
            DtTable::setColumnSwitch('active');
            DtTable::setColumnSwitch('status');
            DtTable::hideActions(['delete'], ['id' => 1]);

            DtTable::changeColumn('full_name', function ($value, $row) {
                $name = esc($value ?: 'غير محدد');
                $phone = $row['mobile'] ?? '';

                $waUrl = null;
                if (!empty($phone)) {
                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                    if (str_starts_with($cleanPhone, '00')) $cleanPhone = substr($cleanPhone, 2);
                    if (str_starts_with($cleanPhone, '05') && strlen($cleanPhone) === 10) $cleanPhone = '966' . substr($cleanPhone, 1);
                    elseif (str_starts_with($cleanPhone, '01') && strlen($cleanPhone) === 11) $cleanPhone = '20' . substr($cleanPhone, 1);
                    $waUrl = 'https://wa.me/' . $cleanPhone;
                }

                $html = '<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">';
                $html .= '<span class="font-weight-bold text-dark"><i class="fas fa-user-graduate text-info ml-1"></i> ' . $name . '</span>';
                if ($waUrl) {
                    $html .= '<a href="' . esc($waUrl) . '" target="_blank" class="btn btn-sm shadow-sm" style="background-color: #25D366; color: #fff; border-radius: 15px; padding: 2px 8px; font-size: 11px; text-decoration: none;" title="محادثة واتساب: ' . esc($phone) . '">';
                    $html .= '<i class="fab fa-whatsapp ml-1"></i> واتساب';
                    $html .= '</a>';
                }
                $html .= '</div>';
                return $html;
            });

            DtTable::changeColumn('created_at', function ($value) {
                if (empty($value)) return '-';
                return '<span class="small text-muted" dir="ltr">' . esc(date('Y-m-d H:i', strtotime($value))) . '</span>';
            });

            DtTable::setAction('edit', 'edit', ADMIN_URL . 'students/edit/');

            $output = DtTable::tableRender($usersModel, false);
            return $this->response->setJSON($output);
        } else {
            return view('Modules\Users\Views\Admin\index', $data);
        }
    }

    /**
     * Instructors List View & DataTable (user_type = 2)
     */
    public function instructors()
    {
        $data['title'] = 'إدارة المحاضرين';
        $data['add_url'] = ADMIN_URL . 'instructors/add';

        if ($this->request->isAJAX()) {
            $usersModel = $this->users
                ->select("users.id, users.full_name, COALESCE(users.email, ident_email.secret) as email, COALESCE(users.mobile, ident_mobile.secret) as mobile, users.status, users.active, users.created_at")
                ->join('auth_identities as ident_email', 'ident_email.user_id = users.id AND ident_email.type = "email_password"', 'left')
                ->join('auth_identities as ident_mobile', 'ident_mobile.user_id = users.id AND ident_mobile.type IN ("mobile_password", "mobile_number")', 'left')
                ->where('users.user_type', 2)
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['users.full_name', 'ident_email.secret', 'ident_mobile.secret', 'users.email', 'users.mobile']);
            DtTable::orderableColumns(['full_name', 'email', 'mobile', 'status', 'active', 'created_at']);
            DtTable::setShowColumns('full_name,email,mobile,status,active,created_at');
            DtTable::setColumnSwitch('active');
            DtTable::setColumnSwitch('status');
            DtTable::hideActions(['delete'], ['id' => 1]);

            DtTable::changeColumn('full_name', function ($value, $row) {
                $name = esc($value ?: 'غير محدد');
                $phone = $row['mobile'] ?? '';

                $waUrl = null;
                if (!empty($phone)) {
                    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                    if (str_starts_with($cleanPhone, '00')) $cleanPhone = substr($cleanPhone, 2);
                    if (str_starts_with($cleanPhone, '05') && strlen($cleanPhone) === 10) $cleanPhone = '966' . substr($cleanPhone, 1);
                    elseif (str_starts_with($cleanPhone, '01') && strlen($cleanPhone) === 11) $cleanPhone = '20' . substr($cleanPhone, 1);
                    $waUrl = 'https://wa.me/' . $cleanPhone;
                }

                $html = '<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 6px;">';
                $html .= '<span class="font-weight-bold text-dark"><i class="fas fa-chalkboard-teacher text-primary ml-1"></i> ' . $name . '</span>';
                if ($waUrl) {
                    $html .= '<a href="' . esc($waUrl) . '" target="_blank" class="btn btn-sm shadow-sm" style="background-color: #25D366; color: #fff; border-radius: 15px; padding: 2px 8px; font-size: 11px; text-decoration: none;" title="محادثة واتساب: ' . esc($phone) . '">';
                    $html .= '<i class="fab fa-whatsapp ml-1"></i> واتساب';
                    $html .= '</a>';
                }
                $html .= '</div>';
                return $html;
            });

            DtTable::changeColumn('created_at', function ($value) {
                if (empty($value)) return '-';
                return '<span class="small text-muted" dir="ltr">' . esc(date('Y-m-d H:i', strtotime($value))) . '</span>';
            });

            DtTable::setAction('edit', 'edit', ADMIN_URL . 'instructors/edit/');

            $output = DtTable::tableRender($usersModel, false);
            return $this->response->setJSON($output);
        } else {
            return view('Modules\Users\Views\Admin\index', $data);
        }
    }

    public function add($defaultUserType = null)
    {
        $defaultType = $defaultUserType ? (int) $defaultUserType : (int) ($this->request->getGet('type') ?? 1);
        $data['title'] = $defaultType === 2 ? 'إضافة محاضر جديد' : ($defaultType === 1 ? 'إضافة طالب جديد' : lang("Admin.add_data"));

        if ($this->request->is('post')) {
            $this->rules['password'] = ['label' => lang("Users.password"), 'rules' => "required"];
            $this->rules['email'] = ['label' => 'البريد الإلكتروني', 'rules' => "required|valid_email|is_unique[auth_identities.secret]"];
            $this->rules['mobile'] = ['label' => 'الجوال', 'rules' => "required|is_unique[auth_identities.secret]"];
            $this->rules['username'] = ['label' => lang("Users.username"), 'rules' => "permit_empty|alpha_numeric_punct|min_length[3]|max_length[30]|is_unique[users.username]"];

            if ($this->validate($this->rules)) {
                $id = $this->data_arr();
                $this->fireUploader->upload_photos($this->users, 'avatar', $id);
                $this->show_msg('success', lang("Admin.add"), lang("Admin.add_success"));

                $submittedType = (int) ($this->request->getPost('user_type') ?? $defaultType);
                $redirectUrl = $submittedType === 2 ? (ADMIN_URL . "instructors") : (ADMIN_URL . "students");
                return redirect()->to($redirectUrl);
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $user = new \stdClass();
        $user->id = null;
        $user->username = '';
        $user->full_name = '';
        $user->email = '';
        $user->mobile = '';
        $user->user_type = $defaultType;
        $user->instructor_bio = '';
        $user->active = 1;
        $user->status = 1;

        $data['user'] = $user;
        return view('Modules\Users\Views\Admin\form', $data);
    }

    public function edit($id, $overrideType = null)
    {
        $user = $this->users->find($id);
        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $isInstructor = ((int) ($overrideType ?? $user->user_type) === 2);
        $data['title'] = $isInstructor ? 'تعديل بيانات المحاضر' : 'تعديل بيانات الطالب';

        if ($this->request->is('post')) {
            $this->rules['email'] = ['label' => 'البريد الإلكتروني', 'rules' => "required|valid_email"];
            $this->rules['mobile'] = ['label' => 'الجوال', 'rules' => "required"];

            if ($this->validate($this->rules)) {
                $id = $this->data_arr($id);
                $this->fireUploader->upload_photos($this->users, 'avatar', $id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));

                $submittedType = (int) ($this->request->getPost('user_type') ?? $user->user_type);
                $redirectUrl = $submittedType === 2 ? (ADMIN_URL . "instructors") : (ADMIN_URL . "students");
                return redirect()->to($redirectUrl);
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $db = \Config\Database::connect();
        $emailIdentity = $db->table('auth_identities')
            ->where('user_id', $id)
            ->where('type', 'email_password')
            ->get()->getRow();
        $mobileIdentity = $db->table('auth_identities')
            ->where('user_id', $id)
            ->where('type', 'mobile_password')
            ->get()->getRow();

        $user->email = !empty($emailIdentity->secret) ? $emailIdentity->secret : ($user->email ?? '');
        $user->mobile = !empty($mobileIdentity->secret) ? $mobileIdentity->secret : ($user->mobile ?? '');

        if ($overrideType) {
            $user->user_type = (int) $overrideType;
        }

        $data['user'] = $user;
        $data['files'] = !empty($user->avatar) ? (is_array($user->avatar) ? $user->avatar : json_decode($user->avatar, true)) : [];

        return view('Modules\Users\Views\Admin\form', $data);
    }

    function data_arr($id = NULL){
        // add new page data
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'instructor_bio' => $this->request->getPost('instructor_bio'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'user_type' => UserType::normalize($this->request->getPost('user_type')),
            'active' => $this->request->getPost('active') ? 1 : 0,
            'status' => $this->request->getPost('status') ? 1 : 0,
        ];

        $username = $this->request->getPost('username');
        if (!empty($username)) {
            $data['username'] = $username;
        }

        // Save the data using the save method
        if ($id) {
            // Update the existing record
            $this->users->update($id, $data);
        } else {
            // Insert a new record
            $this->users->insert($data);
            $id = $this->users->getInsertID();
        }

        // Save email and mobile in auth_identities
        $email = $this->request->getPost('email');
        $mobile = $this->request->getPost('mobile');
        $password = (string) $this->request->getPost('password');
        $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;

        $db = \Config\Database::connect();
        
        if (!empty($email)) {
            $existingEmail = $db->table('auth_identities')
                ->where('user_id', $id)
                ->where('type', 'email_password')
                ->get()->getRow();
                
            if ($existingEmail) {
                $emailUpdateData = ['secret' => $email, 'updated_at' => date('Y-m-d H:i:s')];
                if ($passwordHash !== null) {
                    $emailUpdateData['secret2'] = $passwordHash;
                }

                $db->table('auth_identities')
                    ->where('id', $existingEmail->id)
                    ->update($emailUpdateData);
            } else {
                $db->table('auth_identities')->insert([
                    'user_id' => $id,
                    'type' => 'email_password',
                    'secret' => $email,
                    'secret2' => $passwordHash ?? password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        if (!empty($mobile)) {
            $existingMobile = $db->table('auth_identities')
                ->where('user_id', $id)
                ->where('type', 'mobile_password')
                ->get()->getRow();
                
            if ($existingMobile) {
                $mobileUpdateData = ['secret' => $mobile, 'updated_at' => date('Y-m-d H:i:s')];
                if ($passwordHash !== null) {
                    $mobileUpdateData['secret2'] = $passwordHash;
                }

                $db->table('auth_identities')
                    ->where('id', $existingMobile->id)
                    ->update($mobileUpdateData);
            } else {
                $db->table('auth_identities')->insert([
                    'user_id' => $id,
                    'type' => 'mobile_password',
                    'secret' => $mobile,
                    'secret2' => $passwordHash ?? password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return $id;
    }

    public function show($id): \CodeIgniter\HTTP\ResponseInterface
    {
        $user = $this->users->find($id);
        if (!$user) {
            return $this->response->setJSON(['data' => []]);
        }

        $db = \Config\Database::connect();
        $emailIdentity = $db->table('auth_identities')
            ->where('user_id', $id)
            ->where('type', 'email_password')
            ->get()->getRow();
        $mobileIdentity = $db->table('auth_identities')
            ->where('user_id', $id)
            ->where('type', 'mobile_password')
            ->get()->getRow();

        $email = $emailIdentity ? $emailIdentity->secret : '';
        $mobile = $mobileIdentity ? $mobileIdentity->secret : '';

        // Prepare the translated keys for the show modal
        $module = 'Users';
        $new_array = [
            lang("{$module}.full_name") => $user->full_name,
            lang("{$module}.user_type") => UserType::getLabel((int) ($user->user_type ?? UserType::STUDENT)),
            lang("{$module}.email") => $email,
            lang("{$module}.mobile") => $mobile,
            lang("{$module}.status") => $user->status,
            lang("{$module}.active") => $user->active,
            lang("{$module}.created_at") => $user->created_at,
        ];

        return $this->response->setJSON(['data' => $new_array]);
    }

    public function send()
    {
        $data = $this->request->getPost('data');

        dd($data);
    }

    /**
     * Display device tracking & account sharing audit dashboard
     */
    public function devices()
    {
        $data['title'] = 'مراقبة الأجهزة والحسابات';
        
        /** @var \Modules\Users\Models\UserDeviceModel $deviceModel */
        $deviceModel = model(\Modules\Users\Models\UserDeviceModel::class);
        
        $data['suspiciousList'] = $deviceModel->getSuspiciousUsersList();
        
        $db = \Config\Database::connect();
        $data['allDevices'] = $db->table('tb_user_devices d')
            ->select('d.*, u.full_name, u.email, u.mobile')
            ->join('users u', 'u.id = d.user_id', 'left')
            ->orderBy('d.updated_at', 'DESC')
            ->get()->getResultArray();

        return view('devices', $data);
    }

    /**
     * Reset all registered devices for a specific user (allows re-binding new device)
     */
    public function resetDevices($userId)
    {
        /** @var \Modules\Users\Models\UserDeviceModel $deviceModel */
        $deviceModel = model(\Modules\Users\Models\UserDeviceModel::class);
        $deviceModel->resetUserDevices((int)$userId);

        session()->setFlashdata('success', 'تم تصفير وإعادة تعيين أجهزة الطالب بنجاح.');
        return redirect()->to(site_url('dt_admin/users/devices'));
    }

    /**
     * Toggle block status for a specific device
     */
    public function toggleDeviceBlock($deviceId)
    {
        $db = \Config\Database::connect();
        $device = $db->table('tb_user_devices')->where('id', $deviceId)->get()->getRowArray();
        
        if ($device) {
            $newStatus = empty($device['is_blocked']) ? 1 : 0;
            $db->table('tb_user_devices')->where('id', $deviceId)->update(['is_blocked' => $newStatus]);
            
            $msg = $newStatus ? 'تم حظر الجهاز بنجاح' : 'تم إلغاء حظر الجهاز بنجاح';
            session()->setFlashdata('success', $msg);
        }

        return redirect()->to(site_url('dt_admin/users/devices'));
    }

}
