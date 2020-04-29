<?php
$userId = isset($userInfo->userId) ? $userInfo->userId : -1;
$name = isset($userInfo->name) ? $userInfo->name : 'N/A';
$email = isset($userInfo->email) ? $userInfo->email : 'N/A';
$mobile = isset($userInfo->mobile) ? $userInfo->mobile : 'N/A';
$roleId = ($userInfo->roleId !== null) ? $userInfo->roleId : 'N/A';
$roleText = isset($userInfo->roleText) ? $userInfo->roleText : 'N/A';
$address = isset($userInfo->address) ? $userInfo->address : 'N/A';
$imgAvatar = isset($userInfo->imgAvatar) ? $userInfo->imgAvatar : 'avatar.png';
$tutorId = isset($userInfo->tutorId) ? $userInfo->tutorId : -1;
$tutorEmail = isset($userInfo->tutorEmail) ? $userInfo->tutorEmail : 'N/A';
$tutorRoleId = isset($userInfo->tutorRoleId) ? $userInfo->tutorRoleId : '3';
?>

<style type="text/css">
    tbody tr td p {
        display: block;
        width: 100px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

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
                             src="<?php echo base_url() . 'uploads/user_avatar/' . $imgAvatar; ?>"
                             alt="User profile picture">
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
                            <li class="list-group-item" <?php if (!empty($role) && $role == STUDENT): ?>
                                style="display:block !important;">
                                <b>Tutor</b>
                                <a id="tutor-email" class="pull-right open-mes-modal" href="#mes-modal"
                                   data-toggle="modal"
                                   data-senderId="<?= $userId; ?>"
                                   data-senderRole="<?= $roleId; ?>"
                                   data-receiverId="<?= $tutorId; ?>"
                                   data-receiverRole="<?= $tutorRoleId; ?>"
                                   data-email="<?= $tutorEmail; ?>">
                                    <?= $tutorEmail; ?>
                                </a>
                            </li><?php endif; ?>
                            <li class="list-group-item">
                                <b>Upload Avatar</b>
                                <form action="<?= base_url() . 'uploadAvatar/' . $userId; ?>" method="post"
                                      enctype="multipart/form-data">
                                    <input type="file" name="userfile" style="margin-top: 5px"/>
                                    <br/>
                                    <input class="btn btn-primary" type="submit" value="Upload"/>
                                </form>
                            </li>
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
                        <li class="<?= ($active == "inbox") ? "active" : "" ?>"
                            style="display:<?= ($role == STUDENT || $role == TUTOR) ? "block" : "none" ?> !important;">
                            <a href="#inbox" data-toggle="tab">Inbox</a>
                        </li>
                        <li class="<?= ($active == "sent") ? "active" : "" ?>"
                            style="display:<?= ($role == STUDENT || $role == TUTOR) ? "block" : "none" ?> !important;">
                            <a href="#sent" data-toggle="tab">Sent</a>
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
                                                <input id="fname" class="form-control" name="fname" type="text"
                                                       placeholder="Full Name" maxlength="128"
                                                       value="<?= set_value('fname', $name); ?>"/>
                                                <input id="userId" name="userId" type="hidden"
                                                       value="<?php echo $userId; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="mobile">Mobile Number</label>
                                                <input type="text" class="form-control" id="mobile" name="mobile"
                                                       placeholder="Mobile"
                                                       value="<?= set_value('mobile', $mobile); ?>"
                                                       maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" id="email" name="email"
                                                       placeholder="Email"
                                                       value="<?= set_value('email', $email); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                       placeholder="Address"
                                                       value="<?= set_value('address', $address); ?>">
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
                        <div class="<?= ($active == "inbox") ? "active" : "" ?> tab-pane" id="inbox">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-body table-responsive">
                                            <table id="tbl-inbox" class="table display" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Content</th>
                                                    <th>Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (isset($inboxRecords)) {
                                                    foreach ($inboxRecords as $record) { ?>
                                                        <tr>
                                                            <td><?= $record->subject ?></td>
                                                            <td><?= $record->content ?></td>
                                                            <td><?php echo date("d-m-Y H:s", strtotime($record->createdDate)) ?></td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="<?= ($active == "sent") ? "active" : "" ?> tab-pane" id="sent">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-body table-responsive">
                                            <table id="tbl-sent" class="table display" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Content</th>
                                                    <th>Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (isset($sentRecords)) {
                                                    foreach ($sentRecords as $record) { ?>
                                                        <tr>
                                                            <td><?= $record->subject ?></td>
                                                            <td><?= $record->content ?></td>
                                                            <td><?php echo date("d-m-Y H:s", strtotime($record->createdDate)) ?></td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                <form role="form" id="send-message" action="#" method="post" onsubmit="return false;">
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
                        <input type="reset" id="messageBoxReset" class="btn btn-default" value="Reset"/>
                        <button type="submit" id="closeMessageBox" class="btn btn-danger btn-default pull-left" data-dismiss="modal" style="display: none;"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
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

        $('#tbl-inbox').DataTable({
            'info': true,
            'searching': false,
            'paging': true,
            'lengthChange': false,
        });

        $('#tbl-sent').DataTable({
            'info': true,
            'searching': false,
            'paging': true,
            'lengthChange': false,
        });

    });
</script>
