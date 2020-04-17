<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-handshake-o"></i> Conference Management
            <small>Add / Edit Conference</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group" style="margin-top: 34px"></div>
            </div>
        </div>
        <div class="row">
            <!-- left column -->
            <div class="col-xs-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Conference Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addConference" action="<?php echo base_url() ?>submitNewConference"
                          method="post">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="app-date">Appointment Date *</label>
                                        <input id="app-date" class="form-control" type="text" name="appDate" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="app-time">Time *</label>
                                    <select class="form-control" id="app-time" name="appTime">
                                        <option value="0" selected="selected">---Select Time---</option>
                                        <option value="08:00:00">08:00</option>
                                        <option value="10:00:00">10:00</option>
                                        <option value="12:00:00">12:00</option>
                                        <option value="14:00:00">14:00</option>
                                        <option value="16:00:00">16:00</option>
                                        <option value="18:00:00">18:00</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title *</label>
                                        <input id="title" class="form-control" type="text"
                                               name="title" value="<?php echo set_value('title'); ?>"
                                               maxlength="128" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="topic">Topic *</label>
                                        <input id="topic" class="form-control topic" type="text"
                                               name="topic" value="<?php echo set_value('topic'); ?>"
                                               maxlength="128" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location *</label>
                                        <input id="location" class="form-control" type="text"
                                               name="location" value="<?php echo set_value('location'); ?>"
                                               maxlength="128" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type *</label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="<?= VIRTUAL ?>">Virtual</option>
                                            <option value="<?= REAL ?>">Real</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="desc">Description</label>
                                        <textarea id="desc" name="desc" maxlength="65000"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit"/>
                            <input type="reset" class="btn btn-default" value="Reset"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) {
                    ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
                <?php
                $success = $this->session->flashdata('success');
                if ($success) {
                    ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" type="text/css"
      href="<?php echo base_url(); ?>assets/bootstrap-datepicker-1.9.0/css/bootstrap-datepicker.css"/>

<script rel="stylesheet" type="text/javascript"
        src="<?php echo base_url(); ?>assets/bootstrap-datepicker-1.9.0/js/bootstrap-datepicker.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        CKEDITOR.replace('desc', {
            filebrowserBrowseUrl: '<?php echo
            site_url('assets/js/ckfinder/ckfinder.html');?>',
            filebrowserUploadUrl: '<?php echo
            site_url('assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files');?>',
        });


        const appDate = $('#app-date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayBtn: true,
            minDate: new Date(),
        }).on('change', function () {
            // event listen change on datepicker
            let selDate = $(this).val();
        });

        const sleAppTime = document.getElementById('app-time');
        sleAppTime.addEventListener('change', function () {
            // get time from dropdown list
            const appTime = sleAppTime.options[sleAppTime.selectedIndex].value;

            // get all available time by date
            const promise = getAvailableTime(appDate.val());
            promise.then(data => {
                const availableTimes = data.result;
                availableTimes.forEach(item => {
                    if (item === appTime) {
                        alert("The schedule you choose already has in the system.");
                        location.reload();
                    }
                });
            });
        });
    });

    async function getAvailableTime(appDate) {
        let result;
        try {
            result = await $.ajax({
                type: "POST",
                url: baseURL + "getAvailableTime",
                data: {
                    appDate: appDate
                },
                dataType: "json"
            });
            return result;
        } catch (error) {
            console.error(error);
        }
    }
</script>
