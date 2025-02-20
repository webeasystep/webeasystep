<?php
namespace Modules\Payments\Controllers;
use App\Controllers\BaseController;
use Modules\Payments\Models\PlansModel;

class Payments extends BaseController
{
    public PlansModel $paymentsModel;

    public function __construct()
    {
        $this->paymentsModel = new PlansModel();
    }

    public function index(): string
    {
        $data = [
            'title' => lang('Payments.Payments'),
            'payments' => $this->paymentsModel->where('payment_status', 'completed')->paginate(10),
            'pager' => $this->paymentsModel->pager,
        ];

        return view('site/index', $data);
    }
}
