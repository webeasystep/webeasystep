<?php
namespace Modules\Search\Models ;

use App\Models\BaseModel;

class SearchModel extends BaseModel
{
    protected $table = 'tborders';
    protected $primaryKey = 'id';
    // protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $returnType     = 'object';


    public function detailCustomer($id = null)
    {
        // $builder = $this->db->table($this->table);
        // $builder->select('customer_id AS id, customer_name AS customer');gender
        // return $builder->get()->getResultArray();
        $builder = $this->builder($this->table)->select('id, area_name, location, sort,active');
        if (empty($id)) {
            return $builder->get()->getResult();
        } else {
            return $builder->where('id', $id)->get(1)->getRow();
        }
    }

    public function getAreaById($id)
    {
        return $this->db->query("SELECT *, CONCAT(ST_X(location) , ',' , ST_Y(location)) AS location
            FROM tbsearch WHERE id=?", [$id])->getRow();
    }
    // Custom insert method
    public function insertArea($data, bool $returnID = true)
    {

        // Check for the 'location' field
        if (isset($data['location'])) {
            $location = "ST_GeometryFromText('" . $this->db->escapeString($data['location']) . "')";
            unset($data['location']); // remove 'location' from the data array

            // Prepare the SQL query for insert
            $fields = implode(', ', array_keys($data)) . ', location';
            $values = implode(', ', array_map(fn($item) => $this->db->escape($item), $data)) . ", $location";
            $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";

            $this->db->query($sql);
            return $returnID ? $this->db->insertID() : true;
        } else {
            return parent::insert($data, $returnID);
        }
    }

    // Custom update method
    public function updateArea($id, $data)
    {
        $builder = $this->db->table($this->table);

        if (isset($data['location'])) {
            $location = "ST_GeometryFromText('" . $this->db->escapeString($data['location']) . "')";
            unset($data['location']); // remove 'location' from the data array

            // Prepare the SQL query for update
            $setClause = array_map(fn($key) => "$key = " . $this->db->escape($data[$key]), array_keys($data));
            $setClause = implode(', ', $setClause) . ", location = $location";
            $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = " . $this->db->escape($id);

            $this->db->query($sql);
            return $this->db->affectedRows();
        } else {
            return parent::update($id, $data);
        }
    }
}
