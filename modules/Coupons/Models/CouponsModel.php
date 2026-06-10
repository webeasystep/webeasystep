<?php

namespace Modules\Coupons\Models;

use App\Models\BaseModel;
use DateTime;

class CouponsModel extends BaseModel
{
    protected $table = 'fd_coupons';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'coupon_code', 'discount_type', 'discount_percentage', 'discount_value',
        'end_date', 'usage_limit', 'usage_limit_per_account', 'used_count', 'active', 'is_deleted',
        'created_at', 'updated_at', 'deleted_at',
    ];
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $useSoftDeletes = false;

    public function normalizeCouponCode(string $couponCode): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $couponCode));
    }

    public function getBusinessDate(?DateTime $dateTime = null): string
    {
        $dateTime ??= new DateTime();
        $businessDate = clone $dateTime;

        if ((int) $dateTime->format('H') < 2) {
            $businessDate->modify('-1 day');
        }

        return $businessDate->format('Y-m-d');
    }

    public function getCouponExpiryDateTime(string $endDate): string
    {
        return date('Y-m-d H:i:s', strtotime($endDate . ' +1 day 02:00:00'));
    }

    public function saveCoupon(array $data, $id = null): void
    {
        $discountType = $data['discount_type'] ?? 'percentage';

        $couponData = [
            'coupon_code'              => $this->normalizeCouponCode($data['coupon_code'] ?? ''),
            'discount_type'            => $discountType,
            'discount_percentage'      => ($discountType === 'percentage') ? (int) ($data['discount_percentage'] ?? 0) : 0,
            'discount_value'           => ($discountType === 'fixed')      ? (int) ($data['discount_value']      ?? 0) : 0,
            'end_date'                 => $data['end_date'],
            'usage_limit'              => $data['usage_limit'],
            'usage_limit_per_account'  => (int) ($data['usage_limit_per_account'] ?? 0),
            'active'                   => $data['active'] ?? 0,
            'updated_at'               => date('Y-m-d H:i:s'),
        ];

        if ($id === null) {
            $couponData['used_count'] = 0;
            $couponData['created_at'] = date('Y-m-d H:i:s');
            $this->insert($couponData);
            return;
        }

        $this->update($id, $couponData);
    }

    /**
     * Count how many times a specific client has used a given coupon (completed orders).
     */
    public function getClientUsageCount(int $couponId, int $clientId): int
    {
        return (int) $this->db->table('fd_orders')
            ->where('coupon_id', $couponId)
            ->where('client_id', $clientId)
            ->countAllResults();
    }

    public function deleteCoupons(array $ids): void
    {
        $ids = array_filter(array_map('intval', $ids));

        if (empty($ids)) {
            return;
        }

        $this->whereIn('id', $ids)->set([
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
        ])->update();
    }

    public function getValidCouponByCode(string $couponCode): ?object
    {
        $couponCode = $this->normalizeCouponCode($couponCode);

        if ($couponCode === '') {
            return null;
        }

        $coupon = $this->where('coupon_code', $couponCode)
            ->where('active', 1)
            ->where('is_deleted', 0)
            ->first();

        if (empty($coupon)) {
            return null;
        }

        if ($this->getCouponExpiryDateTime($coupon->end_date) < date('Y-m-d H:i:s')) {
            return null;
        }

        if ((int) $coupon->used_count >= (int) $coupon->usage_limit) {
            return null;
        }

        return $coupon;
    }

    public function calculateDiscountAmount(float $itemsPrice, object $coupon): int
    {
        if ($itemsPrice <= 0) {
            return 0;
        }

        $type = $coupon->discount_type ?? 'percentage';

        if ($type === 'fixed') {
            // Fixed amount — cap at items price so total never goes negative
            $discountAmount = min((int) $coupon->discount_value, $itemsPrice);
        } else {
            $discountAmount = ($itemsPrice * (int) $coupon->discount_percentage) / 100;
        }

        return (int) floor($discountAmount);
    }

    public function incrementUsage(int $couponId): void
    {
        $this->db->table($this->table)
            ->set('used_count', 'used_count + 1', false)
            ->set('updated_at', date('Y-m-d H:i:s'))
            ->where('id', $couponId)
            ->update();
    }
}
