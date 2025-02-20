<?php $this->extend('admin_layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid">
    <button class="btn btn-primary mb-1 add"><i class="fas fa-plus"></i>Add Category</button>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="jq-table" width="100%">
                    <thead></thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <?= form_open(); ?>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="category" class="col-sm-3 col-form-label">Categories</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="category" id="category">
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="">Save</button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>

<script>
    $(document).ready(function() {
        '<?= $output; ?>'
    });
</script>
<?php $this->endSection(); ?>
<script>

    $(".add").on("click", function(e) {
        $("#formModal").modal("show");
        $(".modal-title").text("Add Data");
        $("form").attr("action", `${BASE_URL}/category/add`);
        $("button[type=submit]").attr("id", "add");
    })
    $(".content").on("click", "#add", function(e) {
        e.preventDefault();
        $.ajax({
            type: $("form").attr("method"),
            url: $("form").attr("action"),
            dataType: "json",
            data: $("form").serialize(),
            success: function(response) {
                responseValidation(['add'], ['category'], response);
                if (response.success) {
                    $("#formModal").modal("hide");
                    table.ajax.reload()
                }
            }
        });
    })
    $(".content").on("click", ".edit", function() {
        $("#formModal").modal("show");
        $(".modal-title").text("Edit Data");
        $("form").attr("action", `${BASE_URL}/category/change`);

        $("#category").val($(this).data("category"));
        $("button[type=submit]").attr("id", "change");
        $(".modal-footer").append('<input type="hidden" name="id" value="' + $(this).data("id") + '">');
    })
    $(".content").on("click", "#change", function(e) {
        e.preventDefault();
        $.ajax({
            type: $("form").attr("method"),
            url: $("form").attr("action"),
            dataType: "json",
            data: $("form").serialize(),
            success: function(response) {
                responseValidation(['change'], ['category'], response);
                if (response.success) {
                    $("#formModal").modal("hide");
                    table.ajax.reload()
                }
            }
        });
    })
    $("#formModal").on("hide.bs.modal", function() {
        $("form")[0].reset();
        $("#category").removeClass("is-invalid");
        $("input[name=id]").remove();
    })
    $(".content").on("click", ".delete", function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure you want to delete this data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirmation!',
            cancelButtonText: 'Cancelled'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${BASE_URL}/users/delete`,
                    data: {
                        id: $(this).data("id")
                    },
                    success: function(response) {
                        if (response.status) {
                            table.ajax.reload()
                            toastr.success(response.message, 'Success')
                        } else {
                            toastr.error(response.message, 'Failed')
                        }
                    }
                });
            }
        })
    })
</script>
