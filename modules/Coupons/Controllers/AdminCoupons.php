<?php

namespace Modules\Coupons\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Coupons\Models\CouponsModel;

class AdminCoupons extends BaseController
{
    protected CouponsModel $coupons;

    public function __construct()
    {
        $this->coupons = new CouponsModel();
    }

    protected function buildRules(?int $id = null): array
    {
        $uniqueRule = !empty($id)
            ? "is_unique[tb_coupons.coupon_code,id,{$id}]"
            : "is_unique[tb_coupons.coupon_code]";

        $discountType = request()->getPost('discount_type') ?? 'percentage';

        return [
            'course_id' => [
                'label' => lang('Coupons.course'),
                'rules' => 'permit_empty|integer|is_not_unique[tb_courses.id]',
            ],
            'coupon_code' => [
                'label' => lang('Coupons.coupon_code'),
                'rules' => "required|alpha_numeric|min_length[2]|max_length[50]|{$uniqueRule}",
            ],
            'discount_type' => [
                'label' => lang('Coupons.discount_type'),
                'rules' => 'required|in_list[percentage,fixed]',
            ],
            'discount_percentage' => [
                'label' => lang('Coupons.discount_percentage'),
                'rules' => $discountType === 'percentage'
                    ? 'required|integer|greater_than[0]|less_than_equal_to[100]'
                    : 'permit_empty|integer',
            ],
            'discount_value' => [
                'label' => lang('Coupons.discount_value'),
                'rules' => $discountType === 'fixed'
                    ? 'required|integer|greater_than[0]'
                    : 'permit_empty|integer',
            ],
            'end_date' => [
                'label' => lang('Coupons.end_date'),
                'rules' => 'required|valid_date',
            ],
            'usage_limit' => [
                'label' => lang('Coupons.usage_limit'),
                'rules' => 'required|integer|greater_than_equal_to[1]',
            ],
            'usage_limit_per_account' => [
                'label' => lang('Coupons.usage_limit_per_account'),
                'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
            ],
        ];
    }

    /**
     * Cross-field check: usage_limit_per_account must not exceed usage_limit (when > 0).
     */
    private function validateUsageLimitPerAccount(array $postData): bool
    {
        $perAccount = (int) ($postData['usage_limit_per_account'] ?? 0);
        $total      = (int) ($postData['usage_limit'] ?? 0);

        if ($perAccount > 0 && $perAccount > $total) {
            $this->show_msg('danger', lang('Admin.validation_errors'), [
                'usage_limit_per_account' => lang('Coupons.usage_limit_per_account_exceeds_total'),
            ]);
            return false;
        }

        return true;
    }

    public function index()
    {
        $data['title'] = lang('Coupons.coupons');

        if ($this->request->isAJAX()) {
            $couponsModel = $this->coupons
                ->select('tb_coupons.id, tb_coupons.coupon_code, tb_coupons.discount_percentage, tb_coupons.discount_type, tb_coupons.discount_value, tb_coupons.end_date, tb_coupons.usage_limit, tb_coupons.used_count, tb_coupons.active, tb_coupons.created_at, tb_coupons.updated_at, tb_courses.course_title')
                ->join('tb_courses', 'tb_courses.id = tb_coupons.course_id', 'left')
                ->from('tb_coupons', true)
                ->where('tb_coupons.is_deleted', 0)
                ->builder();

            DtTable::setColumnSwitch('active');
            DtTable::searchableColumns(['tb_coupons.coupon_code', 'tb_courses.course_title']);
            DtTable::orderableColumns(['tb_coupons.coupon_code', 'tb_courses.course_title', 'tb_coupons.discount_percentage', 'tb_coupons.end_date', 'tb_coupons.usage_limit', 'tb_coupons.used_count', 'tb_coupons.created_at']);
            DtTable::hideActions(['show']);

            return $this->response->setJSON(DtTable::tableRender($couponsModel, false));
        }

        return view('index', $data);
    }

    public function add()
    {
        $data['title'] = lang('Coupons.add_coupon');
        $data['business_date'] = $this->coupons->getBusinessDate();
        $data['courses'] = $this->coupons->getCourseOptions();

        if ($this->request->is('post')) {
            $postData = $this->getF();

            if ($this->validate($this->buildRules()) && $this->validateCouponDate($postData) && $this->validateUsageLimitPerAccount($postData)) {
                $this->coupons->saveCoupon($postData);
                $this->show_msg('success', lang('Admin.add'), lang('Admin.add_success'));
                return redirect()->to(ADMIN_URL . 'coupons');
            }

            $this->show_msg('danger', lang('Admin.validation_errors'), $this->validator->getErrors());
        }

        return view('form', $data);
    }

    public function edit($id)
    {
        $data['title'] = lang('Coupons.edit_coupon');
        $data['coupon'] = $this->coupons->find($id);
        $data['business_date'] = $this->coupons->getBusinessDate();
        $data['courses'] = $this->coupons->getCourseOptions();

        if (empty($data['coupon'])) {
            $this->show_msg('danger', lang('Admin.error'), lang('Coupons.coupon_not_found'));
            return redirect()->to(ADMIN_URL . 'coupons');
        }

        if ($this->request->is('post')) {
            $postData = $this->getF();

            if ($this->validate($this->buildRules((int)$id)) && $this->validateCouponDate($postData) && $this->validateUsageLimitPerAccount($postData)) {
                $this->coupons->saveCoupon($postData, $id);
                $this->show_msg('success', lang('Admin.edit'), lang('Admin.edit_success'));
                return redirect()->to(ADMIN_URL . 'coupons');
            }

            $this->show_msg('danger', lang('Admin.validation_errors'), $this->validator->getErrors());
        }

        return view('form', $data);
    }

    public function delete(): ResponseInterface
    {
        if ($this->request->isAJAX()) {
            $ids = explode(',', $this->request->getPost('rows'));
            $this->coupons->deleteCoupons($ids);

            return $this->response->setJSON([
                'validation' => true,
                'success' => true,
                'message' => lang('Admin.delete_success'),
            ]);
        }

        return $this->response->setJSON([
            'validation' => true,
            'success' => false,
            'message' => lang('Admin.error'),
        ]);
    }

    public function getF(): array
    {
        $postData = $this->request->getPost();
        $postData['coupon_code'] = $this->coupons->normalizeCouponCode($postData['coupon_code'] ?? '');
        $postData['active'] = !empty($postData['active']) ? 1 : 0;

        return $postData;
    }

    private function validateCouponDate(array $postData): bool
    {
        if (($postData['end_date'] ?? '') < $this->coupons->getBusinessDate()) {
            $this->show_msg('danger', lang('Admin.validation_errors'), [
                'end_date' => lang('Coupons.end_date_before_today'),
            ]);

            return false;
        }

        return true;
    }
}
