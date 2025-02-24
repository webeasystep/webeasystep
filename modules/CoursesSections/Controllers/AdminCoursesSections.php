<?php

namespace Modules\Plans\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Plans\Models\PlansModel;

class AdminPlans extends BaseController
{
    protected PlansModel $plans;
    protected array $rules;

    public function __construct()
    {
        $this->plans = new PlansModel();
        $this->rules = [
            "title" => ['label' => lang("Plans.title"), 'rules' => "required"],
            "price" => ['label' => lang("Plans.price"), 'rules' => "required|decimal"],
            "duration_days" => ['label' => lang("Plans.duration_days"), 'rules' => "required|integer"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('Plans.plans_List');

        if ($this->request->isAJAX()) {
            $plansModel = $this->plans
                ->select('id, title, price, duration_days, created_at')
                ->orderBy('id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['title', 'price', 'duration_days']);
            DtTable::orderableColumns(['title', 'price', 'duration_days', 'created_at']);
            $output = DtTable::tableRender($plansModel, false);
            DtTable::setShowColumns("title,price,duration_days,created_at");

            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");
        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr();
                $this->show_msg('success', lang("Admin.add_operation"), lang("Admin.add_success"));
                return redirect()->to(ADMIN_URL . "plans");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        return view("form", $data);
    }

    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr($id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "plans");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['plan'] = $this->plans->getPlanById($id);
        return view('form', $data);
    }

    public function data_arr($id = NULL)
    {
        $builder = $this->db->table('tb_plans');

        $data = [
            'title' => $this->request->getPost('title', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'price' => $this->request->getPost('price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'duration_days' => $this->request->getPost('duration_days', FILTER_SANITIZE_NUMBER_INT),
        ];

        if ($id) {
            $builder->where('id', $id);
            $builder->update($data);
        } else {
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        return $id;
    }
}
