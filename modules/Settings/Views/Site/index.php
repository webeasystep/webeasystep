<?php $this->extend('admin_layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid">
    <button class="btn btn-primary mb-1 add"><i class="fas fa-plus"></i>Add Category</button>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="table-category" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Categories</th>
                            <th>Action</th>
                        </tr>
                        </thead>
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

<?php $this->endSection(); ?>
