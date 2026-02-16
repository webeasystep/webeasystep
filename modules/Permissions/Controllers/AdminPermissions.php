<?php

namespace Modules\Permissions\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use DateTime;
use DateTimeZone;
use Modules\Permissions\Models\PermissionsModel;

class AdminPermissions extends BaseController
{
    protected $permissions;
    protected $rules;

    public function __construct()
    {
        $this->permissions = new PermissionsModel();
        $this->rules = [
            "permission_name" => ['label' => lang("Permissions.permission_name"), 'rules' => "required"],
            "title" => ['label' => lang("Permissions.title"), 'rules' => "required"],
            "description" => ['label' => lang("Permissions.description"), 'rules' => "required"],
        ];
    }

    /**/
    public function index()
    {
        // i want to detect controller path automatically
        // set edit and view

        $data['title'] = lang('Permissions.permissions_List');

        if ($this->request->isAJAX()) {
            $permissionsModel = $this->permissions
                ->select('id,permission_name,title, created_at, updated_at')
                ->where(['id !=' => 1])
                ->builder();

            // DtTable::searchableColumns(['category_name']);
            //  DtTable::orderableColumns(['permission_name']);
            // DtTable::hideActions(['delete', 'show']);
            //  DtTable::stateSave('false',120);
            $output = DtTable::tableRender( $permissionsModel, false);

            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }

    public function add()
    {
         $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {
            // $this->rules['mobile'] = "required|is_unique[auth_permissions_users.mobile]";

            if ($this->validate($this->rules)) {
                $data = [
                    'permission_name' => $this->request->getPost('permission_name'),
                    'title' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('description'),
                ];

                // add new user data
                $id = $this->permissions->insert($data); // Use insert() instead of save()

                // Print the updated query
                /*  $query = $this->permissions->getLastQuery()->getQuery();
                  echo "Updated Query: " . $query;exit;*/
                if (!empty($id)) { // Check if $id is not empty
                    $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                    return redirect()->to(ADMIN_URL . "permissions");
                } else {
                    // Handle error when $id is empty
                    $data["errors"] = "Error occurred while saving user data.";
                }

            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        $data['permission'] = $this->db->table('auth_permissions_users')->get()->getResultArray();

        return view('form', $data);
    }


    //$validationErrors = $this->validation->getErrors();
    // return redirect()->back()->withInput()->with('errors', $validationErrors);
    public function edit($id)
    {

        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {

            // if the profile photo is updated
            // $this->rules['mobile'] = "required|is_unique[permissions.mobile,id,$id]";

            if ($this->validate($this->rules)) {
                $data = [
                    'id' => $id,
                    'permission_name' => $this->request->getPost('permission_name'),
                    'title' => $this->request->getPost('title'),
                ];

                // add new user data
                $this->permissions->save($data);
                // Print the updated query
                /*  $query = $this->permissions->getLastQuery()->getQuery();
                echo "Updated Query: " . $query;*/
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "permissions");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['permission'] = $this->permissions->find($id); // Fetch the user data by ID
        return view('form', $data);
    }


    public function send()
    {
        $data = $this->request->getPost('data');

        dd($data);
    }

}
