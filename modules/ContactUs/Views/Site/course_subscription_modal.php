<!--Start Toastr-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
<!--End Toastr-->

<!-- subscribe Modal -->
<div class="modal fade" id="startNowModal" tabindex="-1" role="dialog" aria-labelledby="startNowModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="subscribeForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="startNowModalLabel">بادر الأن وقم بحجز درس مجاني</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="selectedCourse" name="selectedCourse">
                    <div class="form-group">
                        <label for="study-year">السنة الدراسية</label>
                        <select class="form-control" id="study-year" name="studyYear">
                            <option value="1">اولى ثانوي</option>
                            <option value="2">ثانية ثانوي</option>
                            <option value="3">ثالثة ثانوي</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="mobile">رقم الجوال</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="notes">ملاحظات أخرى (اختياري)</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: flex-start;">
                    <button type="submit" class="btn btn-primary">تقديم</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Hide the study-year dropdown initially
        $('#study-year').closest('.form-group').hide();

        // Default year selection
        $('#study-year').val('1');
        // Assume you have a mechanism to update the 'selectedCourse' value
        $('[data-toggle="modal"]').click(function() {
            var courseId = $(this).attr('id'); // Get the ID of the clicked element
            $('#selectedCourse').val(courseId); // Set the selected course in the hidden input
            // Check if the clicked link is for the Digital Technology course
            if (courseId === 'DigitalTech') {
                $('#study-year').closest('.form-group').show();
            } else {
                $('#study-year').closest('.form-group').hide();
            }
        });

        // Handle form submission
        $('#subscribeForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: "<?= base_url('contact_us/subscribe'); ?>",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status === true) {
                        toastr.success("تم ارسال الطلب بنجاح وجاري مراجعة الإدارة", "تم بنجاح");
                        $('#startNowModal').modal('hide');
                    } else {
                        // Display errors
                        $.each(data.errors, function(key, value) {
                            var inputField = $('#' + key);
                            inputField.addClass('is-invalid');
                            inputField.next('.invalid-feedback').remove();
                            inputField.after('<div class="invalid-feedback">' + value + '</div>');
                        });
                    }
                },
                error: function() {
                    toastr.error("Error during subscription.");
                }
            });
        });

        // Clear validation states and error messages when the modal is closed
        $('#startNowModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('.form-group').find('.is-invalid').removeClass('is-invalid');
            $('.form-group').find('.invalid-feedback').remove();
        });

        // Optionally, you might want to clear errors as the user types to correct them
        $('input, select, textarea').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    });
</script>






