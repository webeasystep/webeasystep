<?php

namespace Modules\Subscriptions\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Subscriptions\Models\SubscriptionsModel;

class AdminSubscriptions extends BaseController
{
    protected SubscriptionsModel $subscriptions;
    protected array $rules;

    public function __construct()
    {
        $this->subscriptions = new SubscriptionsModel();
        $this->rules = [
            "user_id" => ['label' => lang("Subscriptions.user_id"), 'rules' => "required|integer"],
            "plan_id" => ['label' => lang("Subscriptions.plan_id"), 'rules' => "required|integer"],
            "start_date" => ['label' => lang("Subscriptions.start_date"), 'rules' => "required"],
            "end_date" => ['label' => lang("Subscriptions.end_date"), 'rules' => "required"],
            "status" => ['label' => lang("Subscriptions.status"), 'rules' => "required"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('Subscriptions.subscriptions_List');

        if ($this->request->isAJAX()) {
            $subscriptionsModel = $this->subscriptions
                ->select('id, user_id, plan_id, start_date, end_date, status')
                ->orderBy('id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['user_id', 'plan_id', 'start_date', 'end_date', 'status']);
            DtTable::orderableColumns(['user_id', 'plan_id', 'start_date', 'end_date', 'status']);
            $output = DtTable::tableRender($subscriptionsModel, false);
            DtTable::setShowColumns("user_id,plan_id,start_date,end_date,status");

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
                return redirect()->to(ADMIN_URL . "subscriptions");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        $data["users"] = $this->subscriptions->get_users_list();
        $data["plans"] = $this->subscriptions->get_plans_list();
        return view("form", $data);
    }

    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr($id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "subscriptions");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        $data["users"] = $this->subscriptions->get_users_list();
        $data["plans"] = $this->subscriptions->get_plans_list();
        $data['subscription'] = $this->subscriptions->getSubscriptionById($id);
        return view('form', $data);
    }

    public function data_arr($id = NULL)
    {
        $builder = $this->db->table('tb_subscriptions');

        $data = [
            'user_id' => $this->request->getPost('user_id', FILTER_SANITIZE_NUMBER_INT),
            'plan_id' => $this->request->getPost('plan_id', FILTER_SANITIZE_NUMBER_INT),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'status' => $this->request->getPost('status', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
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
