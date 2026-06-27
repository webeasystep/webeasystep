# خطة إضافة Refund إلى Enrollments

## الملخص
- الهدف هو إضافة عملية `refund` لاشتراكات الدورات داخل موديول `Enrollments` من **لوحة التحكم فقط**.
- عند تنفيذ الـ refund يتم تحويل الاشتراك إلى حالة جديدة `refunded` بحيث يفقد العميل صلاحية مشاهدة الكورس.
- يجب إضافة حقل `refund_proof` لحفظ **صورة إثبات الاسترجاع** بنفس فكرة إثبات الدفع، بدون حفظ مبلغ refund وبدون إرسال بريد للعميل.

## تحليل الحالة الحالية
- جدول الاشتراكات الحالي هو `tb_course_enrollments` ويعتمد على الحقل `status` للتحكم في الوصول إلى الكورس داخل `my_courses`.
- الوصول للمحتوى في واجهة العميل يعتمد فعليًا على كون الحالة `approved` فقط في:
  - `modules/Enrollments/Models/CourseEnrollmentsModel.php`
  - `modules/Enrollments/Views/Site/my_courses.php`
- لوحة التحكم حاليًا تدعم فقط:
  - `pending`
  - `approved`
  - `rejected`
- شاشة تعديل الاشتراك في الإدارة تعرض `payment_proof` فقط عبر:
  - `modules/Enrollments/Views/Admin/form.php`
- شاشة التفاصيل الإدارية تعرض أزرار الموافقة/الرفض للحالة `pending` فقط عبر:
  - `modules/Enrollments/Views/Admin/course_enrollment_details.php`
- الكنترولر الإداري الحالي `modules/Enrollments/Controllers/AdminEnrollments.php` يحتوي على:
  - `approveCourseEnrollment()`
  - `rejectCourseEnrollment()`
  - منطق حفظ يدوي داخل `data_arr()`
- لا توجد حاليًا أعمدة خاصة بالـ refund في `tb_course_enrollments`.

## القرارات المعتمدة
- إضافة حالة مستقلة باسم `refunded` بدل إعادة استخدام `rejected`.
- عدم إضافة حقل مبلغ refund.
- عدم إرسال بريد للعميل بعد تنفيذ refund.
- تنفيذ refund من لوحة التحكم فقط.
- إثبات الـ refund يكون صورة مرفقة مثل `payment_proof`.

## التغييرات المقترحة

### 1. قاعدة البيانات
- إنشاء Migration جديدة داخل `modules/Enrollments/Database/Migrations/` لإضافة أعمدة refund إلى `tb_course_enrollments`.
- الأعمدة المقترحة:
  - `refund_proof` `VARCHAR(255)` nullable
  - `refunded_at` `DATETIME` nullable
- لا يلزم عمود `refund_amount` لأن القرار المعتمد هو عدم حفظ المبلغ.
- لا يلزم تعديل نوع عمود `status` إذا كان `VARCHAR`، أما إذا كان `ENUM` فيجب توسعته لتشمل `refunded`.

### 2. الموديل
- تحديث `modules/Enrollments/Models/CourseEnrollmentsModel.php`.
- إضافة الحقول الجديدة إلى `allowedFields`:
  - `refund_proof`
  - `refunded_at`
- إضافة دالة جديدة مثل `refundEnrollment(int $enrollmentId, ?string $refundProof = null, ?string $notes = null): bool`.
- هذه الدالة تقوم بتحديث:
  - `status = refunded`
  - `refund_proof`
  - `refunded_at`
  - `notes` عند وجود ملاحظات
- لا نضيف منطقًا يعبث بسجل الاشتراك أو يحذفه؛ نكتفي بتغيير الحالة وتوثيق بيانات الاسترجاع.

### 3. الكنترولر الإداري
- تحديث `modules/Enrollments/Controllers/AdminEnrollments.php`.
- إضافة endpoint/handler جديد مثل `refundCourseEnrollment($id)`.
- منطق الإجراء:
  - جلب الاشتراك والتأكد من وجوده
  - السماح بالـ refund من الإدارة فقط
  - تقييد التنفيذ على اشتراك سبق تفعيله أو على الأقل عدم السماح بتكرار refund على سجل حالته `refunded`
  - حفظ `refund_proof` المرفوع بنفس أسلوب حفظ `payment_proof` أو بنفس آلية FireUploader المستخدمة بالمشروع
  - استدعاء دالة الموديل لتحديث الحالة إلى `refunded`
- تحديث `data_arr()` بحيث يدعم الحقول الجديدة عند التعديل اليدوي من الفورم إن تقرر عرضها هناك.
- مراجعة منطق `approveCourseEnrollment()` و`rejectCourseEnrollment()` لمنع تعارض الحالات بعد وصول السجل إلى `refunded`.

### 4. Routes
- تحديث `modules/Enrollments/Config/Routes.php`.
- إضافة route إداري جديد مثل:
  - `dt_admin/enrollments/courses/refund/(:num)`
- يجب أن يكون تحت `admin_filter` مثل approve/reject.

### 5. واجهة الإدارة: شاشة التفاصيل
- تحديث `modules/Enrollments/Views/Admin/course_enrollment_details.php`.
- إضافة عرض واضح لحالة `refunded`:
  - badge بلون مميز
  - نص عربي واضح مثل `تم الاسترجاع`
- إضافة معلومات refund عند وجودها:
  - `refunded_at`
  - رابط/زر عرض `refund_proof`
- إضافة بطاقة/نموذج Refund يظهر فقط عندما يكون الاشتراك في حالة تسمح بالاسترجاع:
  - يفضّل ظهوره للحالة `approved`
  - يتضمن:
    - رفع صورة إثبات refund
    - ملاحظات إدارية
    - زر تنفيذ refund
- بعد أن تصبح الحالة `refunded` يتم إخفاء أزرار الموافقة/الرفض/الاسترجاع غير المناسبة.

### 6. واجهة الإدارة: شاشة الفورم العامة
- تحديث `modules/Enrollments/Views/Admin/form.php`.
- إضافة عرض `refund_proof` عند وجوده بجانب `payment_proof`.
- إضافة خيار `refunded` داخل قائمة `status`.
- إذا كان الفورم سيُستخدم لتعديل سجل مسترد يدويًا، يتم عرض حقل أو معاينة إثبات refund.
- لا ينبغي إجبار الإدارة على رفع إثبات refund إلا عند اختيار الحالة `refunded`.

### 7. اللغات
- تحديث ملفات اللغة:
  - `modules/Enrollments/Language/ar/Enrollments.php`
  - `modules/Enrollments/Language/en/Enrollments.php`
- إضافة مفاتيح مثل:
  - `refund_proof`
  - `attach_refund_proof`
  - `refunded_at`
  - `status_refunded`
  - رسائل نجاح/فشل refund

### 8. واجهة العميل
- تحديث `modules/Enrollments/Views/Site/my_courses.php`.
- إضافة تمثيل بصري صحيح للحالة `refunded` بدل دمجها مع `rejected`.
- إظهار تنبيه واضح مثل:
  - `تم استرجاع قيمة هذه الدورة وتم إيقاف الوصول إليها`
- زر الدخول للكورس سيظل مغلقًا تلقائيًا لأن الوصول الحالي يعتمد على `approved` فقط، لكن يجب تحديث النص المعروض حتى لا يظهر كأنه `مرفوض`.

### 9. منطق الوصول للكورس
- مراجعة أي منطق آخر في `modules/Enrollments/Models/CourseEnrollmentsModel.php` أو في الكنترولرات المرتبطة بعرض محتوى الكورس للتأكد أن الوصول لا يتم إلا للحالة `approved`.
- إذا وُجد أي فحص يعتمد على "ليس rejected" أو "وجود اشتراك فقط"، يجب تعديله ليصبح صريحًا على `approved`.

### 10. DataTables والإدارة
- تحديث `AdminEnrollments::index()` داخل `modules/Enrollments/Controllers/AdminEnrollments.php`.
- إضافة دعم بصري لعرض:
  - `refund_proof` عند الحاجة
  - الحالة `refunded` في الجدول
- لا يلزم بالضرورة إظهار `refund_proof` في الجدول الرئيسي إذا كانت شاشة التفاصيل تكفي، لكن يجب على الأقل أن تكون الحالة الجديدة قابلة للفلترة والتمييز.

## ملاحظات تنفيذية
- المشروع يستخدم بنية Modules مع CI4 ويفضّل الالتزام بأسلوبه الحالي بدل إدخال abstraction جديد غير مستخدم.
- ينبغي اتباع نفس نمط رفع الملفات المستخدم حاليًا لإثبات الدفع بدل اختراع آلية موازية.
- أي حفظ لحالة `refunded` يجب أن يكون موثقًا بتاريخ الاسترجاع وصورة الإثبات والملاحظات عند وجودها.
- يجب الحفاظ على التوافق مع السجلات القديمة التي لا تحتوي حقول refund بعد تنفيذ migration.

## الافتراضات
- `tb_course_enrollments.status` قابل لاستقبال القيمة `refunded` أو يمكن توسيعه عبر migration بدون كسر السجلات الحالية.
- مسار حفظ الصور الحالي لإثباتات الاشتراك يمكن إعادة استخدامه لإثبات refund.
- لا توجد متطلبات محاسبية إضافية مثل partial refund أو multi-refund history.

## خطوات التحقق
1. تشغيل migration الخاصة بإضافة أعمدة refund.
2. فتح شاشة إدارة اشتراك Approved والتأكد من ظهور نموذج refund.
3. رفع صورة refund وتنفيذ العملية من لوحة التحكم.
4. التأكد من تحديث السجل في `tb_course_enrollments` إلى:
   - `status = refunded`
   - `refund_proof` محفوظ
   - `refunded_at` محفوظ
5. فتح `my_courses` لنفس العميل والتأكد من:
   - ظهور الحالة `refunded`
   - إيقاف زر الدخول للكورس
   - ظهور رسالة توضح أن الوصول أُوقف بسبب استرجاع المبلغ
6. التأكد أن شاشة تفاصيل الاشتراك في الإدارة تعرض إثبات refund وتاريخه.
7. مراجعة اللوج و`writable/logs` بعد التنفيذ للتأكد من عدم وجود أخطاء أو تحذيرات.

## نطاق التنفيذ
- داخل النطاق:
  - DB migration
  - Admin routes/controller/views
  - Enrollment model
  - Site `my_courses` status rendering
  - Language files
- خارج النطاق:
  - إرسال بريد refund
  - حفظ مبلغ refund
  - refund جزئي
  - workflow مالي خارجي أو تكامل بوابة دفع
