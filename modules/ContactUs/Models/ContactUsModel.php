<?php
namespace Modules\ContactUs\Models ;
use App\Models\BaseModel;

class ContactUsModel extends BaseModel
{
    protected $table = 'contact_us';
    protected $primaryKey = 'id';
    // protected $useSoftDeletes = true;
    protected $allowedFields = ['is_read', 'contact_name', 'email', 'phone', 'send_to', 'subject', 'message', 'attachments'];
    protected $useTimestamps = true;
    protected $returnType     = 'object';

    public function detailCustomer($id = null)
    {
        // $builder = $this->db->table($this->table);
        // $builder->select('customer_id AS id, customer_name AS customer');gender
        // return $builder->get()->getResultArray();
        $builder = $this->builder($this->table)->select('id, customer_number as  customer, gender, customer_phone as  phone,customer_address as address');
        if (empty($id)) {
            return $builder->get()->getResult();
        } else {
            return $builder->where('id', $id)->get(1)->getRow();
        }
    }

}
