<?= $this->extend('admin_layout/template'); ?>

<?= $this->section('content'); ?>

<!-- custom form files !-->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?= $this->include('admin_layout/admin_msg'); ?>
            <?= form_open_multipart(); ?>

            <div class="form-group row">
                <label for="user" class="col-sm-3 col-form-label"><?= lang("Search.area_name") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="area_name" name="area_name"
                           value="<?= set_value('area_name',$area->area_name ?? "") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-md-2 col-xs-12">
                    <?php echo lang("Search.map");?> <span class="text-danger">*</span>:</label>
                <div class="control-cont col-md-10 col-xs-12">
                    <div id="map"></div>
                </div>
            </div>

            <div class="form-group row">
                <label for="location" class="col-sm-3 col-form-label"><?= lang("Search.location") ?></label>
                <div class="col-sm-9">
                    <input type="text" id="location"  class="form-control"  name="location"
                           value="<?= set_value('location',$area->location ?? "32.4833,44.4333") ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>


            <div class="form-group row">
                <label for="sort" class="col-sm-3 col-form-label"><?= lang("Search.sort") ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="sort" name="sort" value="<?= set_value('sort') ?>">
                    <small class="invalid-feedback"></small>
                </div>
            </div>
            <!-- Switch for 'active' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Search.active") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" <?= set_value('active', $area->active ?? 0) ? 'checked' : '' ?> >
                        <label class="custom-control-label" for="active"></label>
                    </div>
                </div>
            </div>


            <!-- Switch for 'is_far' -->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"><?= lang("Search.is_far") ?></label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_far" name="is_far" value="1" <?= set_value('is_far', $area->is_far ?? 0) ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="is_far"></label>
                    </div>
                </div>
            </div>

            <!-- Additional fields for edit form -->
            <a type="button" class="btn btn-secondary"
                    href="<?= ADMIN_URL . 'search' ?>"><?= lang("Admin.cancel") ?></a>
            <button type="submit" id="" class="btn btn-primary"><?= lang("Admin.save") ?></button>
            <?= form_close(); ?>
        </div>
    </div>

</div><!-- /.container-fluid -->
<style>
    #map {
        padding: 0;
        margin: 0;
        height: 300px;
    }
    .lang_selector {
        width: 100px !important;
    }
    select {
        max-width: 250px !important;
    }

</style>

<?= $this->endSection(); ?>
<!-- Script -->
<?= $this->section('js'); ?>
<?= $this->include('admin_layout/curd_js'); ?>
<script async defer type="text/javascript"
        src="https://maps.google.com/maps/api/js?callback=initMap&key=AIzaSyDmCvx8YNLMKoNTFkQZMQWbrpFytDLl5Dg&language=ar-SA&libraries=drawinglibraries=places,drawing"></script>
<script type="text/javascript">
    var my_marker;
    var infoWindow;

    // Init map
    function initMap() {
        var defaultLat = 24.7136; // Default latitude
        var defaultLng = 46.6753; // Default longitude
        // Parse the existing location value (if available)
        var existingLocation = $("#location").val();
        var my_location = existingLocation ? existingLocation.split(",") : [defaultLat, defaultLng];
        var my_position = new google.maps.LatLng(my_location[0], my_location[1]);

        // Init map
        var my_map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: my_position,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: false,
            zoomControl: true
        });

        // Init marker
        my_marker = new google.maps.Marker({
            position: my_position,
            map: my_map,
            draggable: true,
            title: "Choose location..."
        });

        // Update location field on dragend event
        google.maps.event.addListener(my_marker, "dragend", function(event) {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            // Format as longitude,latitude
            $("#location").val(`${lng},${lat}`);
        });

        infoWindow = new google.maps.InfoWindow;
    }
</script>
<?php $this->endSection(); ?>


