<?php

namespace Modules\Units\Models;

use CodeIgniter\Model;

class UnitItemsModel extends Model
{
    protected $table = 'tb_unit_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'unit_id',
        'item_type',
        'item_id',
        'title',
        'description',
        'thumbnail',
        'duration',
        'sort_order',
        'is_active',
        'metadata'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'unit_id' => 'required|integer',
        'item_type' => 'required|in_list[video,quiz,page]',
        'title' => 'required|max_length[255]',
        'sort_order' => 'integer'
    ];

    protected $validationMessages = [
        'unit_id' => [
            'required' => 'معرف الوحدة مطلوب',
            'integer' => 'معرف الوحدة يجب أن يكون رقماً'
        ],
        'item_type' => [
            'required' => 'نوع العنصر مطلوب',
            'in_list' => 'نوع العنصر يجب أن يكون فيديو أو كويز أو صفحة'
        ],
        'title' => [
            'required' => 'عنوان العنصر مطلوب',
            'max_length' => 'عنوان العنصر لا يجب أن يتجاوز 255 حرف'
        ]
    ];

    /**
     * Get all items for a specific unit
     */
    public function getUnitItems($unitId, $activeOnly = false)
    {
        $builder = $this->where('unit_id', $unitId);

        if ($activeOnly) {
            $builder->where('is_active', 1);
        }

        return $builder->orderBy('sort_order', 'ASC')
                      ->orderBy('created_at', 'ASC')
                      ->findAll();
    }

    /**
     * Get items with related data (quiz/page details)
     */
    public function getUnitItemsWithDetails($unitId, $activeOnly = false)
    {
        $items = $this->getUnitItems($unitId, $activeOnly);

        foreach ($items as &$item) {
            switch ($item->item_type) {
                case 'quiz':
                    if ($item->item_id) {
                        $quizModel = new \Modules\Quizzes\Models\QuizzesModel();
                        $item->quiz_details = $quizModel->find($item->item_id);
                    }
                    break;

                case 'page':
                    if ($item->item_id) {
                        $pageModel = new \Modules\Pages\Models\PagesModel();
                        $item->page_details = $pageModel->find($item->item_id);
                    }
                    break;
            }
        }

        return $items;
    }

    /**
     * Get next sort order for a unit
     */
    public function getNextSortOrder($unitId)
    {
        $maxOrder = $this->selectMax('sort_order')
                        ->where('unit_id', $unitId)
                        ->first();

        return ($maxOrder->sort_order ?? 0) + 1;
    }

    /**
     * Update sort orders for multiple items
     */
    public function updateSortOrders($items)
    {
        $this->db->transStart();

        foreach ($items as $item) {
            $this->update($item['id'], ['sort_order' => $item['sort_order']]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Toggle item active status
     */
    public function toggleActive($itemId)
    {
        $item = $this->find($itemId);
        if (!$item) {
            return false;
        }

        return $this->update($itemId, ['is_active' => !$item->is_active]);
    }

    /**
     * Create video item from bunny.net data
     */
    public function createVideoItem($unitId, $videoData, $sortOrder = null)
    {
        $data = [
            'unit_id' => $unitId,
            'item_type' => 'video',
            'video_id' => $videoData['video_id'],
            'video_title' => $videoData['title'] ?? '',
            'video_duration' => $videoData['duration'] ?? '',
            'video_thumbnail' => $videoData['thumbnail'] ?? '',
            'title' => $videoData['title'] ?? 'فيديو جديد',
            'description' => $videoData['description'] ?? '',
            'sort_order' => $sortOrder ?? $this->getNextSortOrder($unitId),
            'is_active' => 1
        ];

        return $this->insert($data);
    }

    /**
     * Create quiz item
     */
    public function createQuizItem($unitId, $quizId, $title = null, $sortOrder = null)
    {
        // Get quiz details if title not provided
        if (!$title) {
            $quizModel = new \Modules\Quizzes\Models\QuizzesModel();
            $quiz = $quizModel->find($quizId);
            $title = $quiz ? $quiz->quiz_title : 'كويز جديد';
        }

        $data = [
            'unit_id' => $unitId,
            'item_type' => 'quiz',
            'item_id' => $quizId,
            'title' => $title,
            'sort_order' => $sortOrder ?? $this->getNextSortOrder($unitId),
            'is_active' => 1
        ];

        return $this->insert($data);
    }

    /**
     * Create page item
     */
    public function createPageItem($unitId, $pageId, $title = null, $sortOrder = null)
    {
        // Get page details if title not provided
        if (!$title) {
            $pageModel = new \Modules\Pages\Models\PagesModel();
            $page = $pageModel->find($pageId);
            $title = $page ? $page->title : 'صفحة جديدة';
        }

        $data = [
            'unit_id' => $unitId,
            'item_type' => 'page',
            'item_id' => $pageId,
            'title' => $title,
            'sort_order' => $sortOrder ?? $this->getNextSortOrder($unitId),
            'is_active' => 1
        ];

        return $this->insert($data);
    }

    /**
     * Delete all items for a unit
     */
    public function deleteUnitItems($unitId)
    {
        return $this->where('unit_id', $unitId)->delete();
    }

    /**
     * Get statistics for unit items
     */
    public function getItemsStatistics($unitId = null)
    {
        $builder = $this->db->table($this->table);

        if ($unitId) {
            $builder->where('unit_id', $unitId);
        }

        $stats = [
            'total' => $builder->countAllResults(false),
            'active' => $builder->where('is_active', 1)->countAllResults(false),
            'videos' => $builder->where('item_type', 'video')->countAllResults(false),
            'quizzes' => $builder->where('item_type', 'quiz')->countAllResults(false),
            'pages' => $builder->where('item_type', 'page')->countAllResults(false)
        ];

        return $stats;
    }
}
