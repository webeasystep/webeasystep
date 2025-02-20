<?php

namespace Modules\Users\Controllers;

use App\Controllers\BaseController;
use Hermawan\DataTables\DataTable;
use Modules\Users\Models\UsersModel;

class Users extends BaseController
{
    protected $userModel;

    private $rules = [
        'name' => ['rules' => 'required'],
       // 'username' => ['rules' => 'required|alpha_numeric|is_unique[tb_users.username,id,{id}]'],
        'email' => ['rules' => 'required|valid_email|is_unique[users.email,id,{id}]'],
        'role' => ['rules' => 'required']
    ];

    public function __construct()
    {
        $this->userModel = new UsersModel();
        helper(['form', 'function']);
    }

    public function index()
    {
        $data = [
            'title' => 'User list',
            'roles' => $this->userModel->getRole()
        ];
        echo view('user/index', $data);
    }

}
