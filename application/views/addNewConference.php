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
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Conference Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addConference" action="<?php echo base_url() ?>submitAddConference"
                          method="post" role="form">
                        <div class="box-body">
                            <!--                            Update 15-03-2020-->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" rel="stylesheet" />
                                    <label for="email">Time Picker</label>
                                    <input type="text" id="picker" class="form-control" name="appointmentTime"
                                           value="<?php echo set_value('appointmentTime'); ?>"/>
                                </div>
                                <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
                                <script>
                                    $('#picker').datetimepicker({
                                        timepicker: true,
                                        datepicker: true,
                                        format: 'Y-m-d H:i',
                                        step: 60
                                    })
                                </script>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo set_value('location'); ?>"
                                               id="location" name="location" maxlength="200">
                                    </div>
                                </div>
                            </div>
                            <!--                        End of Update 15-03-2020-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="topic">Topic</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo set_value('topic'); ?>"
                                               id="topic" name="topic" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="0">Select Type</option>
                                            <option value="Real">Real</option>
                                            <option value="Virtual">Virtual</option>
                                            <?php if (isset($_POST["type"])) {
                                                echo $_POST["type"];
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Status</label>
                                        <select class="form-control" id="cstatus" name="cstatus">
                                            <option value="0">Select Status</option>
                                            <option value="Activated">Activated</option>
                                            <option value="Deactivated">Deactivated</option>
                                            <?php if (isset($_POST["status"])) {
                                                echo $_POST["status"];
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Description</label>
                                        <textarea class="form-control" aria-label="With textarea"
                                                  id="description" name="description"
                                                  maxlength="200"><?php echo set_value('description'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->

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
<script src="<?php echo base_url(); ?>assets/js/addNewConference.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!--//Update 15-03-2020-->

//End of Update 15-03-2020