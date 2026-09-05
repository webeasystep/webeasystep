<?php
// Fetch colleges here so they can be available in the modal globally
$db = \Config\Database::connect();
$colleges = $db->table('tb_colleges')->where('active', 1)->get()->getResultArray();
?>

<!-- Course Request Modal -->
<div class="modal fade" id="courseRequestModal" tabindex="-1" role="dialog" aria-labelledby="courseRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      
      <!-- Premium Gradient Header -->
      <div class="modal-header">
          <button type="button" class="course-req-close-btn" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
              <i class="fas fa-times"></i>
          </button>
          
          <div class="course-req-icon-badge">
              <i class="fas fa-graduation-cap"></i>
          </div>

          <h5 class="modal-title" id="courseRequestModalLabel">شاركنا اسم المادة التي تبحث عنها</h5>
          <p class="course-req-subtitle">أخبرنا بالمقرر الذي تريده وسنعمل على توفيره وشرحه قريباً</p>
      </div>

      <div class="modal-body">
          <form id="courseRequestForm" action="<?= site_url('api/course-requests') ?>" method="POST">
              <?= csrf_field() ?>

              <div class="form-group">
                  <label class="form-label-custom">
                      <i class="fas fa-book-open text-primary ml-1"></i> اسم المادة أو كودها <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control form-control-custom" name="course_name_code" required placeholder="مثال: MATH 001 أو الذكاء الاصطناعي">
              </div>

              <div class="form-group">
                  <label class="form-label-custom">
                      <i class="fas fa-university text-primary ml-1"></i> الكلية <span class="text-danger">*</span>
                  </label>
                  <select class="form-control form-control-custom" name="college_id" id="requestCollegeSelect" required>
                      <option value="" disabled selected>اختر الكلية التابع لها المقرر</option>
                      <?php foreach($colleges as $col): ?>
                          <option value="<?= $col['id'] ?>"><?= esc($col['college_name_ar']) ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="form-group">
                  <label class="form-label-custom">
                      <i class="fas fa-layer-group text-primary ml-1"></i> القسم الأكاديمي <span class="text-danger">*</span>
                  </label>
                  <select class="form-control form-control-custom" name="department_id" id="requestDepartmentSelect" required>
                      <option value="" disabled selected>اختر القسم</option>
                  </select>
              </div>

              <!-- Notification Toggle Card -->
              <div class="notify-me-box d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center" style="gap: 12px;">
                      <div class="notify-icon-circle">
                          <i class="fas fa-bell"></i>
                      </div>
                      <div>
                          <div class="font-weight-bold" style="font-size: 0.92rem;">أبلغني عند توفر المادة</div>
                          <small class="text-muted">إشعار بريدي فوري عند رفع شروحات المادة</small>
                      </div>
                  </div>
                  <div class="custom-control custom-switch" style="cursor: pointer;">
                      <input type="checkbox" class="custom-control-input" id="notifyMeCheckbox" name="notify_me" value="1" style="cursor: pointer;">
                      <label class="custom-control-label" for="notifyMeCheckbox" style="cursor: pointer;"></label>
                  </div>
              </div>

              <div class="form-group" id="emailFieldContainer" style="display: none;">
                  <label class="form-label-custom">
                      <i class="fas fa-envelope text-primary ml-1"></i> بريدك الإلكتروني <span class="text-danger">*</span>
                  </label>
                  <input type="email" class="form-control form-control-custom" name="contact_info" id="contactEmail" placeholder="name@example.com">
              </div>

              <div id="courseRequestMsg" class="mb-3" style="display:none;"></div>

              <button type="submit" class="btn btn-primary btn-block btn-submit-course-req">
                  <i class="fas fa-paper-plane ml-2"></i> إرسال طلب المقرر
              </button>
          </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-open modal if hash or query parameter is present (deep-linking)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('request_course') || urlParams.has('open_request_modal') || window.location.hash === '#courseRequestModal') {
        setTimeout(function() {
            if (window.jQuery) {
                $('#courseRequestModal').modal('show');
            } else if (window.bootstrap) {
                const modalEl = document.getElementById('courseRequestModal');
                if (modalEl) {
                    const modalObj = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modalObj.show();
                }
            }
        }, 300);
    }
    
    // Toggle Email Field
    const notifyMeCheckbox = document.getElementById('notifyMeCheckbox');
    const emailFieldContainer = document.getElementById('emailFieldContainer');
    const contactEmail = document.getElementById('contactEmail');

    if (notifyMeCheckbox) {
        notifyMeCheckbox.addEventListener('change', function() {
            if(this.checked) {
                emailFieldContainer.style.display = 'block';
                contactEmail.required = true;
            } else {
                emailFieldContainer.style.display = 'none';
                contactEmail.required = false;
            }
        });
    }

    // Handle dynamic department loading
    const requestCollegeSelect = document.getElementById('requestCollegeSelect');
    const requestDepartmentSelect = document.getElementById('requestDepartmentSelect');

    if (requestCollegeSelect && requestDepartmentSelect) {
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
    }

    // Handle AJAX Form Submission
    const courseRequestForm = document.getElementById('courseRequestForm');
    const courseRequestMsg = document.getElementById('courseRequestMsg');

    if (courseRequestForm) {
        courseRequestForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري الإرسال...';
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
                submitBtn.innerHTML = '<i class="fas fa-paper-plane ml-2"></i> إرسال طلب المقرر';
                
                if (data.status && data.status > 300) {
                    // error
                    courseRequestMsg.style.display = 'block';
                    courseRequestMsg.className = 'alert alert-danger p-2 mb-3 text-right';
                    let errorMsg = 'حدث خطأ. حاول مرة أخرى.';
                    if(data.messages) {
                        errorMsg = Object.values(data.messages).join('<br>');
                    }
                    courseRequestMsg.innerHTML = '<i class="fas fa-exclamation-circle ml-1"></i> ' + errorMsg;
                } else {
                    // success
                    courseRequestMsg.style.display = 'block';
                    courseRequestMsg.className = 'alert alert-success p-2 mb-3 text-right';
                    courseRequestMsg.innerHTML = '<i class="fas fa-check-circle ml-1"></i> ' + (data.message || 'تم إرسال طلبك بنجاح وسنعمل على توفير المقرر قريباً!');
                    courseRequestForm.reset();
                    if (emailFieldContainer) emailFieldContainer.style.display = 'none';
                    
                    setTimeout(() => {
                        if (typeof $ !== 'undefined') {
                            $('#courseRequestModal').modal('hide');
                        }
                        courseRequestMsg.style.display = 'none';
                    }, 2500);
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane ml-2"></i> إرسال طلب المقرر';
                courseRequestMsg.style.display = 'block';
                courseRequestMsg.className = 'alert alert-danger p-2 mb-3 text-right';
                courseRequestMsg.innerHTML = '<i class="fas fa-exclamation-circle ml-1"></i> حدث خطأ في الاتصال، حاول مرة أخرى.';
            });
        });
    }
});
</script>
