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
        'duration',
        'sort_order',
        'is_active',
        'is_free',
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
            'in_list' => 'نوع العنصر يجب أن يكون فيديو أو اختبار أو صفحة'
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
     * Create video item from bunny.net or YouTube data
     */
    public function createVideoItem($unitId, $videoData, $sortOrder = null)
    {
        $videoSource = $videoData['video_source'] ?? 'bunny';
        
        if ($videoSource === 'youtube') {
            // YouTube video metadata
            $metadata = [
                'video_id' => $videoData['video_id'] ?? '',
                'video_source' => 'youtube',
                'video_title' => $videoData['video_title'] ?? $videoData['title'] ?? '',
                'video_thumbnail' => $videoData['video_thumbnail'] ?? $videoData['thumbnail'] ?? '',
                'youtube_url' => $videoData['youtube_url'] ?? '',
                'embed_url' => $videoData['embed_url'] ?? 'https://www.youtube.com/embed/' . ($videoData['video_id'] ?? '')
            ];
            
            $data = [
                'unit_id' => $unitId,
                'item_type' => 'video',
                'title' => $videoData['video_title'] ?? $videoData['title'] ?? 'فيديو YouTube',
                'description' => $videoData['description'] ?? '',
                'metadata' => json_encode($metadata),
                'sort_order' => $sortOrder ?? $this->getNextSortOrder($unitId),
                'is_active' => 1
            ];
            
            return $this->insert($data);
        }
        
        // Bunny.net video (original logic)
        $contentData = [
            'collection_id' => $videoData['collection_id'] ?? '',
            'stream_url' => $videoData['stream_url'] ?? '',
            'preview_url' => $videoData['preview_url'] ?? '',
            'captions_path' => $videoData['captions_path'] ?? '',
            'seek_path' => $videoData['seek_path'] ?? '',
            'fallback_url' => $videoData['fallback_url'] ?? ''
        ];

        // Prepare metadata JSON with video information
        $metadata = [
            'video_id' => $videoData['video_id'] ?? '',
            'video_source' => 'bunny',
            'video_title' => $videoData['video_title'] ?? $videoData['title'] ?? '',
            'video_duration' => $videoData['video_duration'] ?? '',
            'video_thumbnail' => $videoData['video_thumbnail'] ?? $videoData['thumbnail'] ?? '',
            'collection_id' => $videoData['collection_id'] ?? '',
            'video_library_id' => $videoData['video_library_id'] ?? '',
            'content_data' => $contentData
        ];

        $data = [
            'unit_id' => $unitId,
            'item_type' => 'video',
            'item_id' => $videoData['video_id'] ?? '', // Store video_id in generic item_id column
            'title' => $videoData['video_title'] ?? $videoData['title'] ?? 'فيديو جديد',
            'description' => $videoData['description'] ?? '',
            'metadata' => json_encode($metadata),
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
        $quizModel = new \Modules\Quizzes\Models\QuizzesModel();
        $quiz = $quizModel->find($quizId);

        if (!$title) {
            $title = $quiz ? $quiz->quiz_title : 'اختبار جديد';
        }

        $description = $quiz ? $quiz->quiz_desc : '';

        $data = [
            'unit_id' => $unitId,
            'item_type' => 'quiz',
            'item_id' => $quizId,
            'title' => $title,
            'description' => $description,
            'sort_order' => $sortOrder ?? $this->getNextSortOrder($unitId),
            'is_active' => 1,
            'metadata' => json_encode([
                'quiz_id' => $quizId
            ])
        ];

        return $this->insert($data);
    }

    /**
     * Create page item
     */
    public function createPageItem($unitId, $pageId, $title = null, $sortOrder = null)
    {
        // Get page details if title not provided
        $pageModel = new \Modules\Pages\Models\PagesModel();
        $page = $pageModel->find($pageId);

        if (!$title) {
            $title = $page ? $page->title : 'صفحة جديدة';
        }

        $description = $page ? $page->desc : '';

        $data = [
            'unit_id' => $unitId,
            'item_type' => 'page',
            'item_id' => $pageId,
            'title' => $title,
            'description' => $description,
            'sort_order' => $sortOrder ?? $this->getNextSortOrder($unitId),
            'is_active' => 1,
            'metadata' => json_encode([
                'page_id' => $pageId
            ])
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
