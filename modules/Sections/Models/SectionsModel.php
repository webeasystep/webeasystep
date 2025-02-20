<?php
namespace Modules\Sections\Models;
use App\Models\BaseModel;

class SectionsModel extends BaseModel
{
    protected $table      = 'sections';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
            'parent_id', 'section_link',
            'title','icon',
            'active', 'sort',];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function getSection($slug)
    {
        return $this->where('section_link', $slug)
            ->first();
    }

    public function get_sections(): array
    {
        $sections = $this->db->table('sections')->get()->getResultArray();
        // Add the "choose" option as the first element
        array_unshift($sections, ['id' => '', 'title' => '--اختر--']);

        return array_column($sections, 'title', 'id');
    }


    public function getParentSections($userId): array
    {
        $userCategories = [];

        // Retrieve the user's selected categories from the database
        $query = $this->db->table('sections')
            ->select('id')
            //  ->where('id', $userId)
            ->get();
        //  echo $this->db->getLastQuery();
        // Loop through the query results and populate the $userCategories array
        foreach ($query->getResult() as $row) {
            $userCategories[] = $row->id;
        }

        return $userCategories;
    }
}
