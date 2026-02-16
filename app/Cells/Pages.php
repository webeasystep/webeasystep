<?php
namespace App\Cells;
use Config\Database;

class Pages
{
    public $order;
    public $limit;
    private $pages;

    public function recentPages( $limit, $order): string
    {
        // Enable query logging
        $db = Database::connect();
        $this->pages = $db->table('pages')
            ->orderBy('id', $order)
            ->limit($limit)->get();
        // Print the SQL query (for debugging purposes)
        $data['recentPages'] = $this->pages->getResultArray();
        return MainView('site_layout/cells/recent_pages_cell',$data);
    }

}


