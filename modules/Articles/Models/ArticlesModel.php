<?php
namespace Modules\Articles\Models ;
use App\Models\BaseModel;

class ArticlesModel extends BaseModel
{
    protected $table = 'articles';
    protected $primaryKey = 'id';
    // protected $useSoftDeletes = true;
    protected $allowedFields = ['title_ar','slug', 'desc_ar', 'content_ar','image', 'sort', 'active'];
    protected $useTimestamps = true;
    protected $returnType     = 'object';


    public function detailCustomer($id = null)
    {
        // $builder = $this->db->table($this->table);
        // $builder->select('customer_id AS id, customer_name AS customer');gender
        // return $builder->get()->getResultArray();
        $builder = $this->builder($this->table)->select('id, title_ar, slug,  desc_en, content_ar,image, sort,active');
        if (empty($id)) {
            return $builder->get()->getResult();
        } else {
            return $builder->where('id', $id)->get(1)->getRow();
        }
    }

    public function getArticleById($id)
    {
        return $this->db->query("SELECT * FROM articles WHERE id=?", [$id])->getRow();
    }
    // Custom insert method
    public function insertArticle($data, bool $returnID = true)
    {
        $builder = $this->db->table($this->table);
        // Check for the 'location' field
        $builder->insert($data, $returnID);
    }

    // Custom update method
    public function updateArticle($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->update($id, $data);

    }
}
