<?php

namespace Modules\Groups\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Groups\Models\GroupsModel;

class AdminGroups extends BaseController
{
    protected $groups;
    protected $rules;

    public function __construct()
    {
        $this->groups = new GroupsModel();
        $this->rules = [
            "group_name" => ['label' => lang("Groups.group_name"), 'rules' => "required"],
            "permissions" => ['label' => lang("Groups.permissions"), 'rules' => "required"],
            "title" => ['label' => lang("Groups.title"), 'rules' => "required"],
            "description" => ['label' => lang("Groups.description"), 'rules' => "required"],
        ];
    }

    /**/
    public function index()
    {
        // i want to detect controller path automatically
        // set edit and view
        $data['title'] = lang('Groups.groups_List');

        if ($this->request->isAJAX()) {
            $groupsModel = $this->groups
                ->select('id,group_name, title, description, created_at, updated_at')
                ->orderBy('id','desc')
                ->builder();

            // DtTable::searchableColumns(['category_name']);
            DtTable::orderableColumns(['group_name']);
            DtTable::hideActions(['delete','show'], ['group_name' =>'SuperAdmin']);
            //  DtTable::stateSave('false',120);
            $output = DtTable::tableRender( $groupsModel, false);

            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {
            // $this->rules['mobile'] = "required|is_unique[groups.mobile]";
            if ($this->validate($this->rules)) {
                $id = $this->data_arr();
                $selectedPermissions = $this->request->getPost('permissions');
                // Assuming 'permissions' is the name of your input
                // Insert group permissions
                $this->groups->insertGroupPermissions($id, $selectedPermissions);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "groups");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        // Fetch the categories
        $data['permissions'] = $this->db->table('auth_permissions')->get()->getResultArray();
        return view('form', $data);
    }


    public function edit($id)
    {

        $data['title'] = lang("Admin.add_data");

        if ($this->request->is('post')) {
            // if the profile photo is updated
            // $this->rules['mobile'] = "required|is_unique[groups.mobile,id,$id]";
            if ($this->validate($this->rules)) {
                $this->data_arr($id);
                $selectedPermissions = $this->request->getPost('permissions'); // Assuming 'permissions' is the name of your input
                // Insert group permissions
                $this->groups->insertGroupPermissions($id, $selectedPermissions);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "groups");
            }
        }
        // Fetch the group data by ID
        $data['group'] = $this->groups->find($id);
        // Fetch the categories
        $data['permissions'] = $this->db->table('auth_permissions')->get()->getResultArray();
        // Fetch the user's selected categories
        $data['selectedPermissions'] = $this->groups->getSelectedPermissions($id);

        return view('form', $data);
    }

    function data_arr($id = NULL){
        // add new page data
        $data = [
            'id' => $id,
            'group_name' => $this->request->getPost('group_name'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
        ];

        // Save the data using the save method
        if ($id) {
            // Update the existing record
            $this->groups->update($id, $data);
        } else {
            // Insert a new record
            $this->groups->insert($data);
        }
        return $id ?? $this->groups->getInsertID();

    }
    public function send()
    {
        $data = $this->request->getPost('data');

        dd($data);
    }

}
