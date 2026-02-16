<?php

namespace Modules\ContactUs\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use Modules\ContactUs\Models\ContactUsModel;

class ContactUs extends BaseController
{
    public ContactUsModel $ContactUs;
    protected $rules;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->ContactUs = new ContactUsModel();
        $this->rules = [
            'contact_name' => 'required',
            'contact_mobile' => 'required|is_unique[contact_us.contact_mobile,id,{id}]',
            'contact_subject' => 'required',
            'contact_message' => 'required',
        ];
    }


    public function index()
    {
        $data['title'] = lang('ContactUs.ContactUs');

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $newData = [
                    "contact_name" => $this->request->getPost("contact_name"),
                    "contact_mobile" => $this->request->getPost("contact_mobile"),
                    "send_to" => setting('App.contact_email'), // Assuming you have this setting
                    "contact_subject" => $this->request->getPost("contact_subject"),
                    "contact_message" => $this->request->getPost("contact_message"),
                    "module_name" => "remove_account",
                    "msg_date" => date("Y-m-d H:i:s"),
                ];

                $this->ContactUs->insert($newData);
                // Assuming send_mail is a method for sending email
                $this->show_msg('success', lang("Site.add"), lang("Site.add_success"));

                return redirect()->back();
            } else {
                $this->show_msg('danger', lang("Site.validation_errors"), validation_errors());
                return redirect()->back()->withInput();
            }
        }
        return view("site/index", $data);
    }

    public function subscribe()
    {
        // Define validation rules
        $this->rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'studyYear' => 'required',
            'mobile' => 'required'  // Ensure there's a validation rule for phone if it's required
        ];

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                $data = [
                    'module_name'     => "subscription",
                    'contact_name'     => $this->request->getPost('name'),
                    'contact_email'    => $this->request->getPost('email'),
                    'contact_mobile'   => $this->request->getPost('mobile'),
                    'contact_subject'  => 'new subscription', // Fixed subject line
                    'contact_message'  => $this->request->getPost('notes'),
                    'selected_course'  => $this->request->getPost('selectedCourse'),
                    'study_year'       => $this->request->getPost('studyYear'),
                    'send_to'          => setting('App.contact_email') // Assuming setting() is configured to get settings
                ];

                // Get database connection
                $db = Database::connect();
                $builder = $db->table('contact_us');

                // Insert data into the database
                if ($builder->insert($data)) {
                    return $this->response->setJSON(['status' => true]);
                } else {
                    return $this->response->setJSON(['status' => false, 'errors' => ['db' => 'Failed to save data']]);
                }
            } else {
                return $this->response->setJSON(['status' => false, 'errors' => $this->validator->getErrors()]);
            }
        } else {
            // Handle non-POST request
            return $this->response->setJSON(['status' => false, 'message' => 'Request must be POST.']);
        }
    }

}
