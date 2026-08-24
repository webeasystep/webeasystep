<?php

namespace Modules\Cart\Models;

use App\Models\BaseModel;

class CartModel extends BaseModel
{
    protected $table         = 'tb_cart';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = false;
    protected $allowedFields = ['user_id', 'item_type', 'item_id'];

    /**
     * Get all cart items for a user with full details
     */
    public function getUserCart(int $userId): array
    {
        $items = $this->where('user_id', $userId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $result = [];
        foreach ($items as $item) {
            $hydrated = $this->hydrateCartItem($item->item_type, (int) $item->item_id, $item->id);
            if ($hydrated !== null) {
                $result[] = $hydrated;
            }
        }
        return $result;
    }

    /**
     * Hydrate item details (title, price, image, courses)
     */
    public function hydrateCartItem(string $itemType, int $itemId, $cartId = null): ?object
    {
        $entry = (object) [
            'cart_id'        => $cartId ?? ($itemType . '_' . $itemId),
            'item_type'      => $itemType,
            'item_id'        => $itemId,
            'title'          => '',
            'price'          => 0.0,
            'image'          => null,
            'original_price' => 0.0,
            'courses'        => [],
        ];

        if ($itemType === 'course') {
            $course = $this->db->table('tb_courses')
                ->where('id', $itemId)
                ->get()->getRow();

            if (!$course) {
                return null;
            }

            $entry->title = $course->course_title;
            $entry->price = (float) $course->course_price;
            $entry->image = $course->image;
        } else {
            $bundle = $this->db->table('tb_bundles')
                ->where('id', $itemId)
                ->get()->getRow();

            if (!$bundle) {
                return null;
            }

            $entry->title = $bundle->bundle_title;
            $entry->price = (float) $bundle->bundle_price;
            $entry->image = $bundle->image;
            $entry->original_price = (float) ($bundle->original_price ?? 0);

            $entry->courses = $this->db->table('tb_bundle_courses')
                ->select('tb_courses.id, tb_courses.course_title, tb_courses.course_price')
                ->join('tb_courses', 'tb_courses.id = tb_bundle_courses.course_id')
                ->where('tb_bundle_courses.bundle_id', $itemId)
                ->get()->getResultArray();
        }

        return $entry;
    }

    /**
     * Add an item to the user's cart
     * Returns: 'added', 'exists', 'enrolled', or 'overlap'
     */
    public function addItem(int $userId, string $itemType, int $itemId): string
    {
        // Check if already in cart
        $exists = $this->where('user_id', $userId)
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->first();

        if ($exists) {
            return 'exists';
        }

        // Collect all course IDs that will be enrolled
        $newCourseIds = [];
        if ($itemType === 'course') {
            $newCourseIds = [(int) $itemId];
        } else {
            $newCourseIds = array_column(
                $this->db->table('tb_bundle_courses')
                    ->select('course_id')
                    ->where('bundle_id', $itemId)
                    ->get()->getResultArray(),
                'course_id'
            );
        }

        // Check if any of these courses are already enrolled (approved or pending)
        if (!empty($newCourseIds)) {
            $enrolled = $this->db->table('tb_course_enrollments')
                ->whereIn('course_id', $newCourseIds)
                ->where('user_id', $userId)
                ->whereIn('status', ['approved', 'pending'])
                ->countAllResults();

            if ($enrolled > 0) {
                return 'enrolled';
            }
        }

        // Check overlap with existing cart items
        $existingCartCourseIds = $this->getCartCourseIds($userId);
        $overlap = array_intersect($newCourseIds, $existingCartCourseIds);
        if (!empty($overlap)) {
            return 'overlap';
        }

        $this->insert([
            'user_id'   => $userId,
            'item_type' => $itemType,
            'item_id'   => $itemId,
        ]);

        return 'added';
    }

    /**
     * Remove an item from cart
     */
    public function removeItem(int $userId, int $cartId): bool
    {
        return $this->where('id', $cartId)
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Clear all items from user's cart
     */
    public function clearCart(int $userId): void
    {
        $this->where('user_id', $userId)->delete();
    }

    /**
     * Get the total price of all items in cart
     */
    public function getCartTotal(int $userId): float
    {
        $items = $this->getUserCart($userId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item->price;
        }
        return $total;
    }

    /**
     * Get count of items in cart
     */
    public function getCartCount(int $userId): int
    {
        return $this->where('user_id', $userId)->countAllResults();
    }

    /**
     * Get all individual course IDs currently in the cart
     * (flattening bundles into their courses)
     */
    public function getCartCourseIds(int $userId): array
    {
        $items = $this->where('user_id', $userId)->findAll();
        $courseIds = [];

        foreach ($items as $item) {
            if ($item->item_type === 'course') {
                $courseIds[] = (int) $item->item_id;
            } else {
                $bundleCourses = $this->db->table('tb_bundle_courses')
                    ->select('course_id')
                    ->where('bundle_id', $item->item_id)
                    ->get()->getResultArray();
                foreach ($bundleCourses as $bc) {
                    $courseIds[] = (int) $bc['course_id'];
                }
            }
        }

        return array_unique($courseIds);
    }
}
