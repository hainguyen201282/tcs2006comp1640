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
<<<<<<< Updated upstream
            <i class="fa fa-graduation-cap"></i> Student Management
=======
            <i class="fa fa-users"></i> Student Management
>>>>>>> Stashed changes
            <small>Add / Edit Student</small>
        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Student Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

<<<<<<< Updated upstream
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="editStudent" action="<?php echo base_url() ?>editStudent" method="post"
=======
                    <form role="form" action="<?php echo base_url() ?>editOldStudent" method="post" id="editOldStudent"
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Name</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $name; ?>"
                                               id="name" name="name" maxlength="200">
=======

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="email" class="form-control" id="email" placeholder="Enter email"
                                               name="email" value="<?php echo $email; ?>" maxlength="128">
>>>>>>> Stashed changes
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
<<<<<<< Updated upstream
                                        <label for="mobile">Mobile</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $mobile; ?>"
                                               id="mobile" name="mobile" maxlength="50">
=======
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="Password"
                                               name="password" maxlength="20">
>>>>>>> Stashed changes
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
<<<<<<< Updated upstream
                                        <label for="roleId">RoleId</label>
                                        <input disabled type="text" class="form-control"
                                               value="<?php echo $roleId; ?>"
                                               id="roleId" name="roleId" maxlength="50">
=======
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" class="form-control" id="cpassword"
                                               placeholder="Confirm Password" name="cpassword" maxlength="20">
>>>>>>> Stashed changes
                                    </div>
                                </div>
                            </div>
                            <div class="row">
<<<<<<< Updated upstream
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender">
<!--                                            <option value="0">Select Gender</option>-->
                                            <option value="Male" <?php if($gender == "Male") echo "SELECTED";?>>Male</option>
                                            <option value="Female" <?php if($gender == "Female") echo "SELECTED";?>>Female</option>
                                            <?php if(isset($_POST["gender"])) { echo $_POST["gender"]; } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tutorId">Tutor ID</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $tutorId; ?>"
                                               id="tutorId" name="tutorId" maxlength="50">
=======
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile Number</label>
                                            <input type="text" class="form-control" id="mobile" placeholder="Mobile Number"
                                                   name="mobile" value="<?php echo $mobile; ?>" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" id="role" name="role">
                                                <option value="0">Select Role</option>
                                                <?php
                                                if (!empty($roles)) {
                                                    foreach ($roles as $rl) {
                                                        ?>
                                                        <option value="<?php echo $rl->roleId; ?>" <?php if ($rl->roleId == $roleId) {
                                                            echo "selected=selected";
                                                        } ?>><?php echo $rl->role ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Gender</label>
                                        <input type="text" class="form-control" id="gender" placeholder="Gender"
                                               name="gender" value="<?php echo $gender; ?>" maxlength="10">
>>>>>>> Stashed changes
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


