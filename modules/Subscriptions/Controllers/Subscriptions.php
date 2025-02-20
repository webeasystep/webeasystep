<?php
namespace Modules\Subscriptions\Controllers;
use App\Controllers\BaseController;
use Modules\Subscriptions\Models\SubscriptionsModel;

class Subscriptions extends BaseController
{
    public SubscriptionsModel $subscriptionsModel;

    public function __construct()
    {
        $this->subscriptionsModel = new SubscriptionsModel();
    }

    public function index(): string
    {
        $data = [
            'title' => lang('Subscriptions.Subscriptions'),
            'subscriptions' => $this->subscriptionsModel->where('status', 'active')->paginate(10),
            'pager' => $this->subscriptionsModel->pager,
        ];

        return view('site/index', $data);
    }
}
