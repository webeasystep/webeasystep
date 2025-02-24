<?php
namespace Modules\Plans\Controllers;
use App\Controllers\BaseController;
use Modules\Plans\Models\PlansModel;

class Plans extends BaseController
{
    public PlansModel $plansModel;

    public function __construct()
    {
        $this->plansModel = new PlansModel();
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
