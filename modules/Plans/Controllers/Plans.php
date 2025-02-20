<?php
namespace Modules\Plans\Controllers;
use App\Controllers\BaseController;
use Modules\Plans\Models\SubscriptionsModel;

class Plans extends BaseController
{
    public SubscriptionsModel $plansModel;

    public function __construct()
    {
        $this->plansModel = new SubscriptionsModel();
    }

    public function index(): string
    {
        $data = [
            'title' => lang('Plans.Plans'),
            'plans' => $this->plansModel->paginate(10),
            'pager' => $this->plansModel->pager,
        ];

        return view('site/index', $data);
    }
}
