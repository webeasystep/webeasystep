<?php

namespace Modules\Sections\Controllers;
use App\Controllers\BaseController;
use Modules\Sections\Models\SectionsModel;

class Sections extends BaseController
{
    public $sections;


    public function __construct()
    {
        $this->sections = new SectionsModel();
    }

    /**
     * @throws \Exception
     */
    public function show($slug): string
    {
        $data = [
            'title' => lang("Site.$slug"),
            'section_info' => $this->sections->getSection($slug),
        ];
        //
        if (!$data['section_info']) {
            throw new \Exception('Oooops : NOT FOUND This PAGE');
        }
        //  var_dump($this->modulePath);
        return view('show', $data);
        //echo view('Modules\sections\Views\site\view_section', $data);
    }


    /**
     * Get user by ID.
     *
     * @param int $id
     * @return mixed|null
     */
    public function getSectionById($id)
    {
        $query = $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->get();

        return $query->getRow();
    }


}
