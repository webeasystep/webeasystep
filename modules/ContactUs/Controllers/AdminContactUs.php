<?php

namespace Modules\ContactUs\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use App\Libraries\FireUploader;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\ContactUs\Models\ContactUsModel;


class AdminContactUs extends BaseController
{
    protected $contactUs;
    protected $rules;
    protected $fireUploader;

    public function __construct()
    {
        $this->contactUs = new ContactUsModel();
        $this->fireUploader = new fireUploader();
        $this->rules = [
            "contact_us_number" => ['label' => lang("ContactUs.contact_us_number"), 'rules' => "required"],
            "contact_mobile" => ['label' => lang("ContactUs.contact_mobile"), 'rules' => "required"],
            "contact_us_address" => ['label' => lang("ContactUs.contact_us_address"), 'rules' => "required"],
        ];
    }


    public function index()
    {
        // i want to detect controller path automatically
        // set edit and view
        $data['title'] = lang('ContactUs.ContactUs');

        if ($this->request->isAJAX()) {
            $contactUsModel = $this->contactUs
                ->select('id,contact_mobile,contact_subject,contact_message,created_at')
                ->orderBy('id','desc')
                ->builder();

            // DtTable::searchableColumns(['category_name']);
            DtTable::orderableColumns(['contact_name', 'email']);
           // DtTable::setColumnImage('attachments');
            DtTable::hideActions(['edit']);
            //  DtTable::stateSave('false',120);
            DtTable::setShowColumns("contact_mobile,contact_subject,contact_message");
            $output = DtTable::tableRender($contactUsModel, false);

            return $this->response->setJSON($output);
        } else {
            return view('index', $data);
        }
    }


    function add()
    {
        $data['title'] = lang("Admin.add_data");
        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $this->data_arr();
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "ContactUs");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        return view('form', $data);
    }
    public function show($id): ResponseInterface
    {
        $table_name = $this->request->getPost('table');
        $columns = $this->request->getPost('columns');
        $all_columns = $columns == '' ? '*' : $columns;
        $module = $this->request->getPost('module');

        $array = $this->db->query("SELECT $all_columns from {$table_name} where id = $id ")->getRowArray();
        $new_array = array();
        // Replace the keys with the lang for this key
        foreach ($array as $key => $value) {
            if ($key !== 'id') {
                $langKey = "{$module}.{$key}";
                $langValue = lang($langKey);
                if ($langValue !== $langKey) {
                    $new_array[$langValue] = $value;
                } else {
                    $new_array[$langKey] = $value;
                }

                unset($new_array[$key]);
            }

        }

        $data = ['data' => $new_array];

            // Update the existing record
        $this->contactUs->update($id, ['is_read' => 1]);
        return $this->response->setJSON($data); // Return the user data as JSON
    }
    public function edit($id)
    {
        // if the profile photo is updated
        $data['title'] = lang("Admin.edit");
        if ($this->request->is('post')) {
                if ($this->validate($this->rules)) {
                $id = $this->data_arr();
                $this->fireUploader->upload_photos($this->contactUs, 'images', $id);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "pages");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        // Fetch the categories
        $this->contactUs->update($id, ['is_read' => 1]);

        $data['info'] =$this->contactUs->find($id);
        return view('form', $data);
    }

    function data_arr($id = NULL)
    {
        // add new page data
        $data = [
            'module_name' => $this->request->getPost('module_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'contact_name' => $this->request->getPost('contact_name'),
            'contact_mobile' => $this->request->getPost('contact_mobile', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];
        // Save the data using the save method
        if ($id) {
            // Update the existing record
            $this->contactUs->update($id, $data);
        } else {
            // Insert a new record
            $this->contactUs->insert($data);
        }
        return $id ?? $this->contactUs->getInsertID();

    }

}
