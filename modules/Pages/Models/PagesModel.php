<?php
namespace Modules\Pages\Models;
use App\Models\BaseModel;

class PagesModel extends BaseModel
{
    protected $table      = 'pages';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
            'page_link', 'images',
            'title_ar','title_en',
            'desc_ar', 'desc_en',
            'content_ar', 'content_en',
            'show_home','active',
            'sort','parent_id',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getPage($slug): array
    {

        return (array) $this->where('page_link', $slug)->first();
    }

    public function get_pages(): array
    {
        $pages = $this->db->table('pages')->get()->getResultArray();
        // Add the "choose" option as the first element
        array_unshift($pages, ['id' => '', 'title_ar' => '--اختر--']);

        return array_column($pages, 'title_ar', 'id');
    }

}
