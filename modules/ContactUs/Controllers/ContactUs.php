<?php

namespace Modules\ContactUs\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use Config\Services;
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

    /**
     * Sends a default test email using the current SMTP settings.
     */
    public function testEmail()
    {
        $recipient = 'webeasystep@gmail.com';
        $email = Services::email();
        $sentAt = date('Y-m-d H:i:s');
        $baseUrl = base_url();
        $message = '
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>WebEasyStep Test Email</title>
            </head>
            <body style="margin:0; padding:24px; background:#f5f7fb; font-family:Arial, sans-serif; color:#1f2937;">
                <div style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:12px; padding:32px; border:1px solid #e5e7eb;">
                    <h1 style="margin:0 0 16px; font-size:24px; color:#111827;">WebEasyStep Test Email</h1>
                    <p style="margin:0 0 12px; line-height:1.7;">This is a default test email generated from the <strong>ContactUs</strong> module.</p>
                    <p style="margin:0 0 12px; line-height:1.7;"><strong>Recipient:</strong> ' . esc($recipient) . '</p>
                    <p style="margin:0 0 12px; line-height:1.7;"><strong>Sent At:</strong> ' . esc($sentAt) . '</p>
                    <p style="margin:0; line-height:1.7;"><strong>Website:</strong> <a href="' . esc($baseUrl) . '">' . esc($baseUrl) . '</a></p>
                </div>
            </body>
            </html>';

        $email->setTo($recipient);
        $email->setSubject('Test Email From WebEasyStep');
        $email->setMessage($message);

        // Keep the debugger output available so the browser response shows the SMTP result.
        if ($email->send(false)) {
            log_message('info', 'ContactUs::testEmail sent successfully to {email}', ['email' => $recipient]);

            return $this->response
                ->setContentType('text/html')
                ->setBody(
                    '<h2>Test email sent successfully.</h2>'
                    . '<p>Recipient: ' . esc($recipient) . '</p>'
                    . '<pre>' . esc($email->printDebugger(['headers'])) . '</pre>'
                );
        }

        $debugger = $email->printDebugger(['headers', 'subject', 'body']);

        log_message('error', 'ContactUs::testEmail failed for {email}: {debugger}', [
            'email' => $recipient,
            'debugger' => $debugger,
        ]);

        return $this->response
            ->setStatusCode(500)
            ->setContentType('text/html')
            ->setBody(
                '<h2>Test email failed.</h2>'
                . '<p>Recipient: ' . esc($recipient) . '</p>'
                . '<pre>' . esc($debugger) . '</pre>'
            );
    }

}
