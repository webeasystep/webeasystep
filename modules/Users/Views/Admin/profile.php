<?= $this->extend('admin_layout/template'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="img-fluid img-circle avatar" src="<?= base_url('uploads/profile/' . esc($user->avatar)) ?>">
                    </div>
                    <h3 class="profile-username text-center"></h3>
                    <p class="text-muted text-center">Register Date : <?= esc(date('d M Y', strtotime($user->created_at))); ?></p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <?= form_open_multipart(base_url('/users/change'), ['csrf_id' => 'token']); ?>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" value="<?= esc($user->name) ?>">
                            <small class="invalid-feedback"></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="username" id="username" value="<?= esc($user->username) ?>">
                            <small class="invalid-feedback"></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="email" id="email" value="<?= esc($user->email) ?>">
                            <small class="invalid-feedback"></small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" id="password" autocomplete="off">
                            <small class="text-danger">Empty if you don't want to change it!</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <textarea name="address" id="address" class="form-control"><?= esc($user->address); ?></textarea>
                            <small class="invalid-feedback"></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="avatar" class="col-sm-2 col-form-label">Photo Profile</label>
                        <div class="col-sm-2 d-none">
                            <img class="img-thumbnail" id="img-preview">
                        </div>
                        <div class="col-sm-4">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="avatar" name="avatar">
                                <label class="custom-file-label" for="avatar">Upload Photo</label>
                                <small class="invalid-feedback"></small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="id" value="<?= esc($user->id); ?>">
                        <input type="hidden" name="role" value="<?= esc($user->role_id); ?>">
                        <input type="hidden" name="avatarLama" id="avatarLama" value="<?= esc($user->avatar); ?>">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" id="save" class="btn btn-success">Save</button>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
    $("#avatar").on("change", function(e) {
        let src = URL.createObjectURL(e.target.files[0]);
        $("#img-preview").prop("src", src).parent().removeClass("d-none")
    })
    $("#save").on("click", function(e) {
        e.preventDefault();
        let formData = new FormData($("form")[0]);
        $.ajax({
            type: $("form").attr("method"),
            url: $("form").attr("action"),
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            success: function(response) {
                responseValidation(['save'], ['name', 'username', 'email', 'avatar'], response);
                if (response.success) {
                    $("#img-preview").parent().addClass('d-none');
                    $(".avatar").attr("src", `${BASE_URL}/uploads/profile/${response.user.avatar}`);
                    $("#name").val(response.user.name);
                    $("#username").val(response.user.username);
                    $("#email").val(response.user.email);
                    $("#password").val('');
                    $("#address").val(response.user.address);
                    $("#avatarLama").val(response.user.avatar);
                }
            }
        });
    });
</script>

<?php $this->endSection(); ?>
