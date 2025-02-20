<?php

namespace Modules\Search\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use CodeIgniter\Model;
use Modules\Search\Models\SearchModel;


class AdminSearch extends BaseController
{
    protected $search;
    protected $rules;

    public function __construct()
    {
        $this->search = new SearchModel();
        $this->rules = [
            "area_name" => ['label' => lang("Search.area_name"), 'rules' => "required"],
            "location" => ['label' => lang("Search.location"), 'rules' => "required"],
        ];
    }

    public function orders_drivers_interactions()
    {
        // set edit and view
        $data['title'] = lang('Search.orders_drivers_interactions');

        if ($this->request->isAJAX()) {
            $this->db->query("SET time_zone='+3:00'");

            // Subquery for minimum difference calculation
            $subQuery = $this->db->table('tborders')
                ->select('driver_id, MIN(TIMESTAMPDIFF(MINUTE, accepted_at, CURRENT_TIME)) AS min_diff')
                ->where('accepted_at IS NOT NULL')
                ->groupStart()
                ->where('DATE(created_at)', 'CURDATE()', false)
                ->orWhere('created_at >=', 'NOW() - INTERVAL 5 HOUR', false)
                ->groupEnd()
                ->groupBy('driver_id')
                ->getCompiledSelect(false);

            // Main query
            $this->db->query("SET time_zone='+3:00'");
            $query = $this->db->table('tborders ord')
                ->select('
                   drv.full_name AS driver,
                   mrc.full_name AS merchant_name,
                   ar.area_name,
                   ord.id, ord.order_code,
                   (ord.orders_count + 1) AS total_order,
                 DATE_FORMAT(ord.created_at, "%h:%i:%s") AS created_at,
                  DATE_FORMAT(ord.accepted_at, "%h:%i:%s") AS accepted_at, 
                  TIMESTAMPDIFF(MINUTE, ord.accepted_at, CURRENT_TIME) AS min_diff
                  ')
                ->join('users drv', 'ord.driver_id = drv.id AND drv.user_type = 2', 'inner')
                ->join('users mrc', 'ord.merchant_id = mrc.id', 'inner')
                ->join('tbareas ar', 'ord.area_id = ar.id', 'left')
                ->join("($subQuery) AS min_diff_table", 'ord.driver_id = min_diff_table.driver_id AND TIMESTAMPDIFF(MINUTE, ord.accepted_at, CURRENT_TIME) = min_diff_table.min_diff', 'inner')
                ->where('drv.active', 1)
                ->groupStart()
                ->where('DATE(ord.created_at)', 'CURDATE()', false)
                ->orWhere('ord.created_at >=', 'NOW() - INTERVAL 5 HOUR', false)
                ->groupEnd()
                ->orderBy('min_diff', 'DESC'); // Add this line for descending order


            DtTable::changeColumn('min_diff', function ($data, $row) {
                // Apply style based on the sort value
                $backgroundColor = (int)$data > 45 ? 'red' : 'green';
                return "<div style='background-color: {$backgroundColor}; 
                        color: white; padding: 5px; text-align: center;'>
                             {$data}
                        </div>";
            });
            // DtTable::searchableColumns(['category_name']);
          //  DtTable::orderableColumns(['name', 'email']);
             DtTable::hideActions(['edit','show','delete']);
            //  DtTable::stateSave('false',120);
            $output = DtTable::tableRender($query, false);

            return $this->response->setJSON($output);
        } else {
            return view('orders_drivers_interactions', $data);
        }
    }
    public function drivers_unpaid_orders()
    {
        // set edit and view
        $data['title'] = lang('Search.drivers_unpaid_orders');

        if ($this->request->isAJAX()) {
           // $this->db->query("SET time_zone='+3:00'");
            // Define your raw SQL query
            $searchModel =  $this->search
                ->select('ord.id, drv.full_name as driver_name, mrc.full_name as merchant_name, ord.order_code, ord.order_price, notes, ord.created_at')
                ->join('users drv', 'ord.driver_id = drv.id', 'INNER')
                ->join('users mrc', 'ord.merchant_id = mrc.id', 'INNER')
                ->where('ord.group_id', 0)
                ->where('ord.order_price !=', 0)
                ->where('ord.order_price !=', setting('App.order_default_price'))
                ->where('has_paid', 0)
                ->from('tborders as ord',true)
                ->builder();

            DtTable::searchableColumns(['driver_name','merchant_name','order_code','order_price']);
           //  DtTable::orderableColumns(['name', 'email']);
             DtTable::hideActions(['edit','show','delete']);
            //  DtTable::stateSave('false',120);
            $output = DtTable::tableRender($searchModel, false);

            return $this->response->setJSON($output);
        } else {
            return view('orders_drivers_interactions', $data);
        }
    }



    public function data_arr($id = NULL)
    {

        $builder = $this->db->table('tbsearch');

        // Sanitize and prepare data
        $data = [
            'area_name' => $this->request->getPost('area_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'sort' => $this->request->getPost('sort', FILTER_SANITIZE_NUMBER_INT),
            'active' => $this->request->getPost('active') ? '1' : '0',
            'is_far' => $this->request->getPost('is_far') ? '1' : '0',
        ];

        // Handle the location field
        $location = $this->request->getPost('location');

        if ($location) {

            // Split the string into longitude and latitude
            list($longitude, $latitude) = explode(',', $location);


            // Convert to WKT format
            $locationWKT = "POINT($latitude $longitude)";

            // Use raw SQL for the geometry field
            $builder->set('location', "ST_GeometryFromText('" . $this->db->escapeString($locationWKT) . "')", false);
        }

        if ($id) {
            // Update existing record
            $builder->where('id', $id);
            $builder->update($data);
        } else {
            // Insert new record
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        // Uncomment for debugging: Output the last executed query
        // echo $this->db->getLastQuery(); exit;

        return $id;
    }



}
