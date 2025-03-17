<?php

namespace Modules\Enrollments\Models;

use App\Models\BaseModel;

class EnrollmentsModel extends BaseModel
{
    protected $table         = 'tb_enrollments';
    protected $primaryKey    = 'id';
    /**
     * الحقول المسموح بتحديثها أو إدراجها.
     * طبقًا لجدول tb_enrollments:
     *  - id
     *  - user_id
     *  - course_id
     *  - enrolled_at
     *  - status (active, completed, cancelled)
     */
    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrolled_at',
        'status',
    ];

    // لاستخدام حقول created_at وupdated_at التلقائية من CodeIgniter (إن رغبت)
    // ولكن الجدول الحالي لا يحتوي على هذين الحقلين بشكل افتراضي
    protected $useTimestamps = false;

    protected $returnType = 'object';

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
}
