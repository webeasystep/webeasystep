<?php

namespace Modules\Bundles\Models;

use App\Models\BaseModel;

class BundlesModel extends BaseModel
{
    protected $table         = 'tb_bundles';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'bundle_title', 'slug', 'description', 'image',
        'original_price', 'bundle_price', 'is_active', 'sort_order',
    ];

    /**
     * Get all active bundles with their course count
     */
    public function getActiveBundles(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get a bundle by slug with its courses
     */
    public function getBundleBySlug(string $slug): ?object
    {
        return $this->where('slug', $slug)
            ->where('is_active', 1)
            ->first();
    }

    /**
     * Get all courses belonging to a bundle
     */
    public function getBundleCourses(int $bundleId): array
    {
        return $this->db->table('tb_bundle_courses')
            ->select('tb_courses.*')
            ->join('tb_courses', 'tb_courses.id = tb_bundle_courses.course_id')
            ->where('tb_bundle_courses.bundle_id', $bundleId)
            ->where('tb_courses.active', 1)
            ->get()
            ->getResultArray();
    }

    /**
     * Get course IDs for a bundle
     */
    public function getBundleCourseIds(int $bundleId): array
    {
        $rows = $this->db->table('tb_bundle_courses')
            ->select('course_id')
            ->where('bundle_id', $bundleId)
            ->get()
            ->getResultArray();

        return array_column($rows, 'course_id');
    }

    /**
     * Recalculate original_price from the sum of course prices
     */
    public function recalculateOriginalPrice(int $bundleId): void
    {
        $sum = $this->db->table('tb_bundle_courses')
            ->selectSum('tb_courses.course_price')
            ->join('tb_courses', 'tb_courses.id = tb_bundle_courses.course_id')
            ->where('tb_bundle_courses.bundle_id', $bundleId)
            ->get()
            ->getRow();

        $this->update($bundleId, [
            'original_price' => $sum->course_price ?? 0,
        ]);
    }

    /**
     * Add courses to a bundle
     */
    public function setCourses(int $bundleId, array $courseIds): void
    {
        // Remove existing courses
        $this->db->table('tb_bundle_courses')
            ->where('bundle_id', $bundleId)
            ->delete();

        // Insert new courses
        $data = [];
        foreach ($courseIds as $courseId) {
            $data[] = [
                'bundle_id' => $bundleId,
                'course_id' => (int) $courseId,
            ];
        }

        if (!empty($data)) {
            $this->db->table('tb_bundle_courses')->insertBatch($data);
        }

        $this->recalculateOriginalPrice($bundleId);
    }
}
