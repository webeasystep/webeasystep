<!-- Show Modal -->
<div class="modal fade" id="ShowModal" tabindex="-1" role="dialog" aria-labelledby="ShowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ShowModalLabel"><?= lang("Exams.show") ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Sections.username") ?>:</div>
                        <div class="col-sm-9" id="usernameShow"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Sections.full_name") ?>:</div>
                        <div class="col-sm-9" id="fullName_show"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Sections.mobile") ?>:</div>
                        <div class="col-sm-9" id="mobile_show"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?= lang("Sections.address") ?>:</div>
                        <div class="col-sm-9" id="address_show"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang("Admin.close") ?></button>
            </div>
        </div>
    </div>
</div>
