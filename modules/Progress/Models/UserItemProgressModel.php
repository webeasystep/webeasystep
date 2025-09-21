<?php

namespace Modules\Progress\Models;

use App\Models\BaseModel;

class UserItemProgressModel extends BaseModel
{
    protected $table         = 'tb_user_item_progress';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id', 'unit_id', 'item_id', 'enrollment_id', 'progress_percentage', 
        'watch_time', 'last_position', 'is_completed', 'completed_at', 
        'first_accessed_at', 'last_accessed_at'
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'object';

    /**
     * Mark an item as completed
     */
    public function markItemCompleted(int $userId, int $unitId, int $itemId, int $enrollmentId): bool
    {
        // Debug logging
        log_message('debug', 'MARK_ITEM_COMPLETED DEBUG - Starting with userId=' . $userId . ', unitId=' . $unitId . ', itemId=' . $itemId . ', enrollmentId=' . $enrollmentId);
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' MARK_ITEM_COMPLETED DEBUG - Starting with userId=' . $userId . ', unitId=' . $unitId . ', itemId=' . $itemId . ', enrollmentId=' . $enrollmentId . "\n",
            FILE_APPEND | LOCK_EX);

        $data = [
            'user_id' => $userId,
            'unit_id' => $unitId,
            'item_id' => $itemId,
            'enrollment_id' => $enrollmentId,
            'progress_percentage' => 100.00,
            'is_completed' => 1,
            'completed_at' => date('Y-m-d H:i:s'),
            'last_accessed_at' => date('Y-m-d H:i:s')
        ];

        log_message('debug', 'MARK_ITEM_COMPLETED DEBUG - Data to insert/update: ' . json_encode($data));
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' MARK_ITEM_COMPLETED DEBUG - Data to insert/update: ' . json_encode($data) . "\n",
            FILE_APPEND | LOCK_EX);

        // Check if record exists
        $existing = $this->where('user_id', $userId)
                        ->where('item_id', $itemId)
                        ->first();

        log_message('debug', 'MARK_ITEM_COMPLETED DEBUG - Existing record: ' . ($existing ? json_encode($existing) : 'NULL'));
        file_put_contents('D:\laragon\www\msarlink\debug.log',
            date('Y-m-d H:i:s') . ' MARK_ITEM_COMPLETED DEBUG - Existing record: ' . ($existing ? json_encode($existing) : 'NULL') . "\n",
            FILE_APPEND | LOCK_EX);

        if ($existing) {
            $result = $this->update($existing->id, $data);
            log_message('debug', 'MARK_ITEM_COMPLETED DEBUG - Update result: ' . ($result ? 'true' : 'false'));
            file_put_contents('D:\laragon\www\msarlink\debug.log',
                date('Y-m-d H:i:s') . ' MARK_ITEM_COMPLETED DEBUG - Update result: ' . ($result ? 'true' : 'false') . "\n",
                FILE_APPEND | LOCK_EX);
            return $result;
        } else {
            $data['first_accessed_at'] = date('Y-m-d H:i:s');
            $insertResult = $this->insert($data);
            $result = $insertResult !== false;
            log_message('debug', 'MARK_ITEM_COMPLETED DEBUG - Insert result: ' . ($result ? 'true' : 'false') . ', Insert ID: ' . ($insertResult ?: 'NULL'));
            file_put_contents('D:\laragon\www\msarlink\debug.log',
                date('Y-m-d H:i:s') . ' MARK_ITEM_COMPLETED DEBUG - Insert result: ' . ($result ? 'true' : 'false') . ', Insert ID: ' . ($insertResult ?: 'NULL') . "\n",
                FILE_APPEND | LOCK_EX);
            
            // Check for database errors
            if (!$result) {
                $error = $this->db->error();
                log_message('debug', 'MARK_ITEM_COMPLETED DEBUG - Database error: ' . json_encode($error));
                file_put_contents('D:\laragon\www\msarlink\debug.log',
                    date('Y-m-d H:i:s') . ' MARK_ITEM_COMPLETED DEBUG - Database error: ' . json_encode($error) . "\n",
                    FILE_APPEND | LOCK_EX);
            }
            
            return $result;
        }
    }

    /**
     * Update item progress
     */
    public function updateItemProgress(int $userId, int $unitId, int $itemId, int $enrollmentId, float $percentage, int $watchTime = 0, int $lastPosition = 0): bool
    {
        $data = [
            'user_id' => $userId,
            'unit_id' => $unitId,
            'item_id' => $itemId,
            'enrollment_id' => $enrollmentId,
            'progress_percentage' => $percentage,
            'watch_time' => $watchTime,
            'last_position' => $lastPosition,
            'last_accessed_at' => date('Y-m-d H:i:s')
        ];

        // Mark as completed if 100%
        if ($percentage >= 100) {
            $data['is_completed'] = 1;
            $data['completed_at'] = date('Y-m-d H:i:s');
        }

        // Check if record exists
        $existing = $this->where('user_id', $userId)
                        ->where('item_id', $itemId)
                        ->first();

        if ($existing) {
            return $this->update($existing->id, $data);
        } else {
            $data['first_accessed_at'] = date('Y-m-d H:i:s');
            return $this->insert($data) !== false;
        }
    }

    /**
     * Get user's progress for all items in a unit
     */
    public function getUnitItemsProgress(int $userId, int $unitId): array
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_user_item_progress.*, tb_unit_items.title, tb_unit_items.item_type, tb_unit_items.sort_order');
        $builder->join('tb_unit_items', 'tb_unit_items.id = tb_user_item_progress.item_id');
        $builder->where('tb_user_item_progress.user_id', $userId);
        $builder->where('tb_user_item_progress.unit_id', $unitId);
        $builder->orderBy('tb_unit_items.sort_order');

        return $builder->get()->getResultArray();
    }

    /**
     * Get next incomplete item in unit
     */
    public function getNextIncompleteItem(int $userId, int $unitId): ?array
    {
        // Get all items in the unit
        $allItems = $this->db->table('tb_unit_items')
                           ->select('id, title, item_type, sort_order')
                           ->where('unit_id', $unitId)
                           ->where('is_active', 1)
                           ->orderBy('sort_order')
                           ->get()
                           ->getResultArray();

        // Get completed items
        $completedItems = $this->where('user_id', $userId)
                              ->where('unit_id', $unitId)
                              ->where('is_completed', 1)
                              ->findAll();

        $completedItemIds = array_column($completedItems, 'item_id');

        // Find first incomplete item
        foreach ($allItems as $item) {
            if (!in_array($item['id'], $completedItemIds)) {
                return $item;
            }
        }

        return null; // All items completed
    }

    /**
     * Calculate unit completion percentage based on completed items
     */
    public function calculateUnitCompletion(int $userId, int $unitId): float
    {
        // Get total items in unit
        $totalItems = $this->db->table('tb_unit_items')
                             ->where('unit_id', $unitId)
                             ->where('is_active', 1)
                             ->countAllResults();

        if ($totalItems == 0) {
            return 0.00;
        }

        // Get completed items count
        $completedItems = $this->where('user_id', $userId)
                              ->where('unit_id', $unitId)
                              ->where('is_completed', 1)
                              ->countAllResults();

        return round(($completedItems / $totalItems) * 100, 2);
    }

    /**
     * Check if unit is fully completed (all items completed)
     */
    public function isUnitCompleted(int $userId, int $unitId): bool
    {
        return $this->calculateUnitCompletion($userId, $unitId) >= 100.00;
    }

    /**
     * Get user's progress for all items in a course
     */
    public function getCourseItemsProgress(int $userId, int $courseId): array
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_user_item_progress.*, tb_unit_items.title, tb_unit_items.item_type, tb_unit_items.sort_order, tb_units.unit_name');
        $builder->join('tb_unit_items', 'tb_unit_items.id = tb_user_item_progress.item_id');
        $builder->join('tb_units', 'tb_units.id = tb_user_item_progress.unit_id');
        $builder->join('tb_sections', 'tb_sections.id = tb_units.section_id');
        $builder->where('tb_user_item_progress.user_id', $userId);
        $builder->where('tb_sections.course_id', $courseId);
        $builder->orderBy('tb_units.sort_order');
        $builder->orderBy('tb_unit_items.sort_order');

        return $builder->get()->getResultArray();
    }
}