<?php
namespace Modules\Videos\Models;
use App\Models\BaseModel;

class VideosModel extends BaseModel
{
    protected $table = 'videos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title_ar', 'course_id', 'desc_ar',  'image', 'video_url', 'sort',  'active', 'code'];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    public function get_videos(): array
    {
        $builder = $this->db->table('videos');
        $list = $builder->select('id, title_ar')
            ->where('active', '1')
            ->get()
            ->getResultArray();
        array_unshift($list, ['id' => '', 'title_ar' => '--اختر--']);
        return array_column($list, 'title_ar', 'id');
    }

    public function getById($id = null)
    {
        $builder = $this->builder($this->table)->select('*');
        if (!empty($id)) {
            return $builder->where('id', $id)->get()->getRowArray();
        } else {
            return $builder->get()->getResult();
        }
    }

    public function getvideoImages($id = null): array
    {
        $builder = $this->builder($this->table)->select('image');

        if (!empty($id)) {
            $result = $builder->where('id', $id)->get()->getRowArray();
            if ($result && isset($result['image'])) {
                return [$result['image']]; // Return the image path as an array
            }
            return []; // Return an empty array if 'image' is not set
        } else {
            $results = $builder->get()->getResultArray();
            $allFiles = [];

            foreach ($results as $row) {
                if (isset($row['image'])) {
                    $allFiles[] = $row['image']; // Collect image paths from each video
                }
            }

            return $allFiles; // Return a combined array of all image paths from each video
        }
    }
}
