<?php
// Fetch colleges here so they can be available in the modal globally
$db = \Config\Database::connect();
$colleges = $db->table('tb_colleges')->where('active', 1)->get()->getResultArray();
?>

<!-- Course Request Modal -->
<div class="modal fade" id="courseRequestModal" tabindex="-1" role="dialog" aria-labelledby="courseRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 15px; overflow: hidden;">
      
      <div class="modal-header border-0 text-center d-flex justify-content-between align-items-center pb-0">
          <button type="button" class="close text-primary" data-dismiss="modal" aria-label="Close" style="background:none; border:none; font-size:1.5rem; opacity: 1;">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>

      <div class="modal-body pt-0 pb-4 px-4 text-center">
          
          <div class="mb-4 text-primary">
              <!-- Animated question icon or simple image matching the design -->
              <i class="fa fa-question-circle" style="font-size: 60px; opacity: 0.5;"></i>
          </div>

          <h5 class="modal-title mb-4 text-primary" id="courseRequestModalLabel" style="font-weight: bold;">شاركنا اسم المادة التي تبحث عنها</h5>
          
          <form id="courseRequestForm" action="<?= site_url('api/course-requests') ?>" method="POST">
              <?= csrf_field() ?>

              <div class="form-group text-right mb-3">
                  <label class="text-primary mb-1" style="font-size: 0.9rem;">اسم المادة:</label>
                  <input type="text" class="form-control" name="course_name_code" required style="border-radius: 20px;" placeholder="اسم المقرر أو كوده">
              </div>

              <div class="form-group text-right mb-3">
                  <label class="text-primary mb-1" style="font-size: 0.9rem;">الكلية:</label>
                  <select class="form-control" name="college_id" id="requestCollegeSelect" required style="border-radius: 20px;">
                      <option value="" disabled selected>اختر الكلية</option>
                      <?php foreach($colleges as $col): ?>
                          <option value="<?= $col['id'] ?>"><?= esc($col['college_name_ar']) ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="form-group text-right mb-4">
                  <label class="text-primary mb-1" style="font-size: 0.9rem;">القسم:</label>
                  <select class="form-control" name="department_id" id="requestDepartmentSelect" required style="border-radius: 20px;">
                      <option value="" disabled selected>اختر القسم</option>
                  </select>
              </div>

              <div class="form-group text-right mb-3 d-flex align-items-center justify-content-end">
                  <label class="text-primary mb-0 mr-2" style="font-size: 0.9rem; margin-left:10px;">أبلغني عند توفر المادة</label>
                  <input type="checkbox" name="notify_me" id="notifyMeCheckbox" value="1" style="width: 18px; height: 18px; accent-color: #5c7cfa;">
              </div>

              <div class="form-group text-right mb-4" id="emailFieldContainer" style="display: none;">
                  <label class="text-primary mb-1" style="font-size: 0.9rem;">البريد الإلكتروني:</label>
                  <input type="email" class="form-control" name="contact_info" id="contactEmail" style="border-radius: 20px;" placeholder="أدخل بريدك الإلكتروني">
              </div>

              <div id="courseRequestMsg" class="mb-3" style="display:none; font-size: 0.9rem;"></div>

              <button type="submit" class="btn btn-primary w-50" style="border-radius: 25px; background-color: #8da2e5; border-color: #8da2e5; padding: 10px;">طلب مادة</button>
          </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle Email Field
    const notifyMeCheckbox = document.getElementById('notifyMeCheckbox');
    const emailFieldContainer = document.getElementById('emailFieldContainer');
    const contactEmail = document.getElementById('contactEmail');

    notifyMeCheckbox.addEventListener('change', function() {
        if(this.checked) {
            emailFieldContainer.style.display = 'block';
            contactEmail.required = true;
        } else {
            emailFieldContainer.style.display = 'none';
            contactEmail.required = false;
        }
    });

    // Handle dynamic department loading
    const requestCollegeSelect = document.getElementById('requestCollegeSelect');
    const requestDepartmentSelect = document.getElementById('requestDepartmentSelect');

    requestCollegeSelect.addEventListener('change', function() {
        const collegeId = this.value;
        requestDepartmentSelect.innerHTML = '<option value="" disabled selected>جاري التحميل...</option>';
        
        fetch(`<?= site_url('api/departments/') ?>${collegeId}`)
            .then(res => res.json())
            .then(data => {
                requestDepartmentSelect.innerHTML = '<option value="" disabled selected>اختر القسم</option>';
                if(data.length > 0) {
                    data.forEach(dept => {
                        requestDepartmentSelect.innerHTML += `<option value="${dept.id}">${dept.department_name_ar}</option>`;
                    });
                } else {
                    requestDepartmentSelect.innerHTML = '<option value="" disabled selected>لا يوجد أقسام</option>';
                }
            })
            .catch(err => {
                requestDepartmentSelect.innerHTML = '<option value="" disabled selected>خطأ في التحميل</option>';
            });
    });

    // Handle AJAX Form Submission
    const courseRequestForm = document.getElementById('courseRequestForm');
    const courseRequestMsg = document.getElementById('courseRequestMsg');

    courseRequestForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'جاري الإرسال...';
        courseRequestMsg.style.display = 'none';

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'طلب مادة';
            
            if (data.status && data.status > 300) {
                // error
                courseRequestMsg.style.display = 'block';
                courseRequestMsg.className = 'text-danger mb-3';
                let errorMsg = 'حدث خطأ. حاول مرة أخرى.';
                if(data.messages) {
                    errorMsg = Object.values(data.messages).join('<br>');
                }
                courseRequestMsg.innerHTML = errorMsg;
            } else {
                // success
                courseRequestMsg.style.display = 'block';
                courseRequestMsg.className = 'text-success mb-3';
                courseRequestMsg.innerHTML = data.message || 'تم إرسال طلبك بنجاح';
                courseRequestForm.reset();
                emailFieldContainer.style.display = 'none';
                
                setTimeout(() => {
                    $('#courseRequestModal').modal('hide');
                    courseRequestMsg.style.display = 'none';
                }, 3000);
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'طلب مادة';
            courseRequestMsg.style.display = 'block';
            courseRequestMsg.className = 'text-danger mb-3';
            courseRequestMsg.innerHTML = 'حدث خطأ في الاتصال، حاول مرة أخرى.';
        });
    });
});
</script>
