<?php

namespace Modules\Users\Controllers;
use App\Controllers\BaseController;
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
            "password" => ['label' => lang("Users.password"), 'rules' => "required"],
        ];
    }
/**/
    public function index()
    {
        // i want to detect controller path automatically
        // set edit and view
        $data['title'] = lang('Users.users_List');

        if ($this->request->isAJAX()) {
        /*    $this->db = \Config\Database::connect();
            $builder = $this->db->table('users')
                ->select('users.id,category_name, avatar, status, active,  full_name, mobile, address, users.created_at, users.updated_at')
                ->join('tb_category', 'tb_category.id = users.category_id', 'inner')
                ->where(['users.id !=' => 1]);

            DtTable::setColumns($builder);*/
            $usersModel = $this->users
                ->select('users.id, avatar, status, active,  full_name,  mobile, address, users.created_at, users.updated_at')
               // ->join('tb_category', 'tb_category.id = users.category_id', 'inner')
               // ->where(['users.id !=' => 1])
                ->builder();


           // DtTable::searchableColumns(['category_name']);
            DtTable::orderableColumns(['full_name', 'mobile', 'address']);
            DtTable::setColumnSwitch('active');
            DtTable::setColumnSwitch('status');
            DtTable::setColumnImage('avatar');
            DtTable::hideActions(['delete'], ['id' =>1]);
            //  DtTable::stateSave('false',120);
            $output = DtTable::tableRender($usersModel, false);

            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {
             $this->rules['mobile'] = "required|is_unique[users.mobile]";

            if ($this->validate($this->rules)) {
                $id = $this->data_arr();
                $this->fireUploader->upload_photos($this->users, 'avatar', $id);
                $this->show_msg('success', lang("Admin.add"), lang("Admin.add_success"));
                return redirect()->to(ADMIN_URL . "users");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        return view('form', $data);
    }


    //$validationErrors = $this->validation->getErrors();
    // return redirect()->back()->withInput()->with('errors', $validationErrors);
    public function edit($id)
    {

        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {

            // if the profile photo is updated
             $this->rules['mobile'] = "required|is_unique[users.mobile,id,{$id}]";
            if ($this->request->getFile('avatar')) {
                $this->rules['avatar'] = 'max_size[avatar,1024]|is_image[avatar]';
            }

            if ($this->validate($this->rules)) {
                $this->data_arr($id);

                // Print the updated query
                /*  $query = $this->users->getLastQuery()->getQuery();
                echo "Updated Query: " . $query;*/
                $this->fireUploader->upload_photos($this->users, 'avatar', $id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));

                return redirect()->to(ADMIN_URL . "users");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['user'] = $this->users->find($id); // Fetch the user data by ID

        $data['files'] = json_decode($data['user']->avatar, true);
        return view('form', $data);
    }

    function data_arr($id = NULL){
        // add new page data
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'password' => password_hash('2231989', PASSWORD_DEFAULT), // 2231989
            'mobile' => $this->request->getPost('mobile'),
            'active' => $this->request->getPost('active') ? 1 : 0,
            'status' => $this->request->getPost('status') ? 1 : 0,
        ];

        // Retrieve the supported locales
        $locales = $this->request->config->supportedLocales;
        foreach ($locales as $locale) {
            $address = 'address_' . $locale;
            $data[$address] = $this->request->getPost($address);
        }

        // Save the data using the save method
        if ($id) {
            // Update the existing record
            $this->users->update($id, $data);
        } else {
            // Insert a new record
            $this->users->insert($data);
        }
        return $id ?? $this->users->getInsertID();
    }

    public function send()
    {
        $data = $this->request->getPost('data');

        dd($data);
    }

}
