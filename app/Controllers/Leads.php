<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class Leads extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Save course lead (interest) to database
     * POST /leads/save
     */
    public function save(): ResponseInterface
    {
        $request = $this->request;

        // Get JSON data
        $json = $request->getJSON();
        
        $courseName = $json->course_name ?? '';
        $email = $json->email ?? '';

        // Validate input
        if (empty($courseName) || empty($email)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'البيانات غير مكتملة'
            ])->setStatusCode(400);
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'الإيميل غير صحيح'
            ])->setStatusCode(400);
        }

        // Check for duplicate entry (same email + course in last 24 hours)
        $existingLead = $this->db->table('tb_course_requests')
            ->where('contact_info', $email)
            ->where('course_name_code', $courseName)
            ->where('notify_me', 1)
            ->where('created_at >', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->get()
            ->getRow();

        if ($existingLead) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'لقد سجلت اهتمامك مسبقاً. سنقوم بالتواصل معك!'
            ]);
        }

        // Save lead to database
        $data = [
            'course_name_code' => $courseName,
            'contact_info' => $email,
            'notify_me' => 1,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('tb_course_requests')->insert($data);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'تم تسجيل اهتمامك بنجاح! سنقوم بإبلاغك فور إتاحة الكورس 🚀'
        ]);
    }
}
