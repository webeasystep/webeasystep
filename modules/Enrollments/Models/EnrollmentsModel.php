<?php

namespace Modules\Enrollments\Models;

use App\Models\BaseModel;

class EnrollmentsModel extends BaseModel
{
    protected $table      = 'tb_enrollments';
    protected $primaryKey = 'id';

    /**
     * Allowed fields to avoid the "DataException: no data in update"
     * Make sure these match your actual columns in `tb_enrollments`.
     */
    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrolled_at',
        'status',
        'proof_image',    // Add this if you store the payment proof
        'completed_at',
        'updated_at',
    ];

    /**
     * If you want CI4 to auto-manage created/updated timestamps, set:
     *   protected $useTimestamps = true;
     * Then define the fields it should use:
     *
     * protected $createdField  = 'enrolled_at';
     * protected $updatedField  = 'updated_at';
     *
     * Make sure your DB columns exist and you want that behavior.
     */
    protected $useTimestamps = false; // or true if you want

    protected $returnType = 'object';


    /**
     * Enroll user immediately (e.g., for free courses).
     * This sets status='active' so the user can access the course immediately.
     */
    public function enrollUser(int $userId, int $courseId)
    {
        // Check if the user is already enrolled
        $existing = $this->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
        if ($existing) {
            // Already enrolled, do nothing
            return;
        }

        // Otherwise, create a new enrollment
        $this->insert([
            'user_id'     => $userId,
            'course_id'   => $courseId,
            'enrolled_at' => date('Y-m-d H:i:s'),
            'status'      => 'active', // or 'pending' if you want admin approval
        ]);
    }


    /**
     * جلب سجل الالتحاق بناءً على المعرف
     */
    public function getEnrollmentById(int $id)
    {
        return $this->find($id);
    }

    /**
     * إدراج سجل جديد
     */
    public function insertEnrollment(array $data, bool $returnID = true)
    {
        return $this->insert($data, $returnID);
    }

    /**
     * تحديث سجل موجود
     */
    public function updateEnrollment(int $id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * قائمة الدورات (للاستخدام في Forms مثلاً)
     */
    public function get_courses_list(): array
    {
        $builder = $this->db->table('tb_courses');
        $builder->select('id, course_name');
        $builder->where('active', '1');
        $query = $builder->get();
        $list = $query->getResultArray();

        // إضافة خيار فارغ في البداية
        array_unshift($list, ['id' => '', 'course_name' => '--اختر الدورة--']);

        // تحويلها لمصفوفة ملائمة: [course_id => course_name]
        return array_column($list, 'course_name', 'id');
    }

    /**
     * قائمة المستخدمين (للاستخدام في Forms مثلاً)
     */
    public function get_users_list(): array
    {
        $builder = $this->db->table('users');
        $builder->select('id, username');  // أو أي عمود آخر يمثل اسم المستخدم
        $builder->where('active', '1');
        $query = $builder->get();
        $list = $query->getResultArray();

        array_unshift($list, ['id' => '', 'username' => '--اختر المستخدم--']);

        return array_column($list, 'username', 'id');
    }
    /**
     * If you truly need to handle payments in the same table,
     * you can keep references to 'amount', 'enrollment_method', etc.
     * But if you have a separate tb_payments table, move them to a PaymentModel.
     */
}
