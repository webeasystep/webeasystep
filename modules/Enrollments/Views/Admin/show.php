<!-- Show Modal -->
<div class="modal fade" id="ShowModal" tabindex="-1" role="dialog" aria-labelledby="ShowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ShowModalLabel"><?= lang("Enrollments.show") ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Enrollments.user_id") ?>:</div>
                        <div class="col-sm-9" id="user_show"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Enrollments.unit_ids") ?>:</div>
                        <div class="col-sm-9" id="units_show"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Enrollments.total_amount") ?>:</div>
                        <div class="col-sm-9" id="total_amount_show"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Enrollments.payment_method") ?>:</div>
                        <div class="col-sm-9" id="payment_method_show"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Enrollments.status") ?>:</div>
                        <div class="col-sm-9" id="status_show"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Enrollments.payment_proof") ?>:</div>
                        <div class="col-sm-9" id="payment_proof_show"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Enrollments.admin_notes") ?>:</div>
                        <div class="col-sm-9" id="admin_notes_show"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang("Admin.close") ?></button>
            </div>
        </div>
    </div>
</div>
