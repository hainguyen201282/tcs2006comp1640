<?php
$userId = $userInfo->userId;
$name = $userInfo->name;
$email = $userInfo->email;
$mobile = $userInfo->mobile;
$roleId = $userInfo->roleId;
$roleText = $userInfo->roleText;
$address = isset($userInfo->address) ? $userInfo->address : '';
$tutorId = isset($userInfo->tutorId) ? $userInfo->tutorId : '';
$tutorEmail = isset($userInfo->tutorEmail) ? $userInfo->tutorEmail : '';
$tutorRoleId = isset($userInfo->tutorRoleId) ? $userInfo->tutorRoleId : '3';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user-circle"></i> My Profile
            <small>View or modify information</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-3">
                <!-- general form elements -->
                <div class="box box-warning">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle"
                             src="<?php echo base_url(); ?>assets/dist/img/avatar.png" alt="User profile picture">
                        <h3 class="profile-username text-center"><?= $name ?></h3>
                        <p class="text-muted text-center"><?= $roleText ?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Email</b>
                                <a class="pull-right"><?= $email ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Mobile</b>
                                <a class="pull-right"><?= $mobile ?></a>
                            </li>
                            <li class="list-group-item" <?php if ($role == STUDENT): ?>
                                style="display:block !important;">
                                <b>Tutor</b>
                                <a id="tutor-email" class="pull-right open-mes-modal" href="#mes-modal"
                                   data-toggle="modal"
                                   data-senderId="<?php echo $userId; ?>"
                                   data-senderRole="<?php echo $roleId; ?>"
                                   data-receiverId="<?php echo $tutorId; ?>"
                                   data-receiverRole="<?php echo $tutorRoleId; ?>"
                                   data-email="<?php echo $tutorEmail; ?>">
                                    <?php echo $tutorEmail; ?>
                                </a>
                            </li><?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="<?= ($active == "details") ? "active" : "" ?>">
                            <a href="#details" data-toggle="tab">Details</a>
                        </li>
                        <li class="<?= ($active == "changepass") ? "active" : "" ?>">
                            <a href="#changepass" data-toggle="tab">Change Password</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="<?= ($active == "details") ? "active" : "" ?> tab-pane" id="details">
                            <form action="<?php echo base_url() ?>profileUpdate" method="post" id="editProfile"
                                  role="form">
                                <?php $this->load->helper('form'); ?>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="fname">Full Name</label>
                                                <input type="text" class="form-control" id="fname" name="fname"
                                                       placeholder="<?php echo $name; ?>"
                                                       value="<?php echo set_value('fname', $name); ?>"
                                                       maxlength="128"/>
                                                <input type="hidden" value="<?php echo $userId; ?>" name="userId"
                                                       id="userId"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="mobile">Mobile Number</label>
                                                <input type="text" class="form-control" id="mobile" name="mobile"
                                                       placeholder="<?php echo $mobile; ?>"
                                                       value="<?php echo set_value('mobile', $mobile); ?>"
                                                       maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" id="email" name="email"
                                                       placeholder="<?php echo $email; ?>"
                                                       value="<?php echo set_value('email', $email); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                       placeholder="<?php echo $address; ?>"
                                                       value="<?php echo set_value('address', $address); ?>">
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
                        <div class="<?= ($active == "changepass") ? "active" : "" ?> tab-pane" id="changepass">
                            <form role="form" action="<?php echo base_url() ?>changePassword" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="inputPassword1">Old Password</label>
                                                <input type="password" class="form-control" id="inputOldPassword"
                                                       placeholder="Old password" name="oldPassword" maxlength="20"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="inputPassword1">New Password</label>
                                                <input type="password" class="form-control" id="inputPassword1"
                                                       placeholder="New password" name="newPassword" maxlength="20"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="inputPassword2">Confirm New Password</label>
                                                <input type="password" class="form-control" id="inputPassword2"
                                                       placeholder="Confirm new password" name="cNewPassword"
                                                       maxlength="20" required>
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

                <?php
                $noMatch = $this->session->flashdata('nomatch');
                if ($noMatch) {
                    ?>
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('nomatch'); ?>
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

<div class="modal fade" id="mes-modal" tabindex="-1" role="dialog" aria-labelledby="mes-modal-label"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mes-modal-label" style="float: left">Send Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" id="send-message" action="#" method="post">
                    <div class="box-body" style="margin-top: -20px">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input id="receiver" name="receiver" class="form-control required text-line"
                                           type="text" placeholder="To" maxlength="20" value="">
                                </div>
                                <div class="form-group">
                                    <input id="subject" name="subject" class="form-control required text-line"
                                           type="text" placeholder="Subject" maxlength="128" value="">
                                </div>
                            </div>
                        </div>
                        <div>
                            <textarea id="content" name="messageContent" class="form-control"
                                      aria-label="With textarea" maxlength="200"></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="submit" class="btn btn-primary" value="Send Message"/>
                        <input type="reset" class="btn btn-default" value="Reset"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .text-line {
        background-color: transparent;
        outline: none;
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: solid #eeeeee 1px;
        padding: 3px 10px;
    }
</style>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/editUser.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sendMessage.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: '<?php echo site_url('assets/js/ckfinder/ckfinder.html');?>',
            filebrowserUploadUrl: '<?php echo site_url('assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files');?>',
        });
    });
</script>
