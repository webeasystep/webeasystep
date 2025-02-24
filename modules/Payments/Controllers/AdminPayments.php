<?php

namespace Modules\Payments\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Payments\Models\PaymentsModel;

class AdminPayments extends BaseController
{
    protected PaymentsModel $payments;
    protected array $rules;

    public function __construct()
    {
        $this->payments = new PaymentsModel();
        $this->rules = [
            "user_id" => ['label' => lang("Payments.user_id"), 'rules' => "required|integer"],
            "course_id" => ['label' => lang("Payments.course_id"), 'rules' => "required|integer"],
            "amount" => ['label' => lang("Payments.amount"), 'rules' => "required|decimal"],
            "payment_method" => ['label' => lang("Payments.payment_method"), 'rules' => "required"],
            "payment_status" => ['label' => lang("Payments.payment_status"), 'rules' => "required"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('Payments.payments_List');

        if ($this->request->isAJAX()) {
            $paymentsModel = $this->payments
                ->select('id, user_id, course_id, amount, payment_method, payment_status, created_at')
                ->orderBy('id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::searchableColumns(['user_id', 'course_id', 'payment_method', 'payment_status']);
            DtTable::orderableColumns(['user_id', 'course_id', 'amount', 'payment_status', 'created_at']);
            $output = DtTable::tableRender($paymentsModel, false);
            DtTable::setShowColumns("user_id,course_id,amount,payment_method,payment_status,created_at");

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
                return redirect()->to(ADMIN_URL . "payments");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        $data["courses"] = $this->payments->get_courses_list();
        $data["users"] = $this->payments->get_users_list();
        return view("form", $data);
    }

    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr($id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "payments");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        $data["courses"] = $this->payments->get_courses_list();
        $data["users"] = $this->payments->get_users_list();
        $data['payment'] = $this->payments->getPaymentById($id);
        return view('form', $data);
    }

    public function data_arr($id = NULL)
    {
        $builder = $this->db->table('tb_payments');

        $data = [
            'user_id' => $this->request->getPost('user_id', FILTER_SANITIZE_NUMBER_INT),
            'course_id' => $this->request->getPost('course_id', FILTER_SANITIZE_NUMBER_INT),
            'amount' => $this->request->getPost('amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'payment_method' => $this->request->getPost('payment_method', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'payment_status' => $this->request->getPost('payment_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
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
