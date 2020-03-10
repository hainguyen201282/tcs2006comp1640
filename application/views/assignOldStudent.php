<?php
$studentId = $studentInfo->studentId;
$email = $studentInfo->email;
$name = $studentInfo->name;
$mobile = $studentInfo->mobile;
$roleId = $studentInfo->roleId;
$gender = $studentInfo->gender;
$tutorId = $studentInfo->tutorId;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap"></i> Student Management
            <small>Assign Tutor to Student</small>
        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Tutor ID</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="assignStudent" action="<?php echo base_url() ?>assignStudent" method="post"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <input type="hidden" class="form-control required"
                                               value="<?php echo $email; ?>"
                                               id="email" name="email"
                                               placeholder="email" maxlength="128">
                                        <input type="hidden" value="<?php echo $studentId; ?>" name="studentId" id="studentId"/>
                                        <input type="hidden" class="form-control required"
                                               value="<?php echo $name; ?>"
                                               id="name" name="name" maxlength="200">
                                        <input type="hidden" class="form-control required"
                                               value="<?php echo $mobile; ?>"
                                               id="mobile" name="mobile" maxlength="50">
                                        <input type="hidden" class="form-control required"
                                               value="<?php echo $roleId; ?>"
                                               id="roleId" name="roleId" maxlength="50">
                                        <input type="hidden" class="form-control required"
                                               value="<?php echo $gender; ?>"
                                               id="gender" name="gender" maxlength="50">
                                        <input type="text" class="form-control required"
                                               value="<?php echo $tutorId; ?>"
                                               id="tutorId" name="tutorId" maxlength="50">
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

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>


