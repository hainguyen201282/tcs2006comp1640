<?php
$studentId = $studentInfo->studentId;
$email = $studentInfo->email;
$name = $studentInfo->name;
$mobile = $studentInfo->mobile;
$roleId = $studentInfo->roleId;
$gender = $studentInfo->gender;
$tutorId = $studentInfo->tutorId;
$tutorName = $studentInfo->tutorName;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap"></i> Student Management
            <small>Add / Edit Student</small>
        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Student Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="editStudent" action="<?php echo base_url() ?>editStudent" method="post"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">Email</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $email; ?>"
                                               id="email" name="email"
                                               placeholder="email" maxlength="128">
                                        <input type="hidden" value="<?php echo $studentId; ?>" name="studentId" id="studentId"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Name</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $name; ?>"
                                               id="name" name="name" maxlength="200">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $mobile; ?>"
                                               id="mobile" name="mobile" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roleId">RoleId</label>
                                        <input disabled type="text" class="form-control"
                                               value="<?php echo $roleId; ?>"
                                               id="roleId" name="roleId" maxlength="50">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="Male" <?php if($gender == "Male") echo "SELECTED";?>>Male</option>
                                            <option value="Female" <?php if($gender == "Female") echo "SELECTED";?>>Female</option>
                                            <?php if(isset($_POST["gender"])) { echo $_POST["gender"]; } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tutorName">Tutor Name</label>
                                            <input disabled class="form-control"
                                                   value="<?php echo $tutorName; ?>"
                                                   id="tutorName" name="tutorName">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tutor">Assign New Tutor</label>
                                        <select class="form-control required" id="tutor" name="tutor">
                                            <?php
                                            if(!empty($tutors))
                                            {
                                                foreach ($tutors as $rl)
                                                {
                                                    ?>
                                                    <option value="<?php echo $rl->userId ?>"
                                                        <?php if($rl->name == $tutorName) {echo "selected=selected";} ?>>
                                                        <?php echo $rl->name ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
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
