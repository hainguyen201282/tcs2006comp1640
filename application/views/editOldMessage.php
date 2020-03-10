<?php
$id = $messageInfo->id;
$receiverId = $messageInfo->receiverId;
$subject = $messageInfo->subject;
$messageStatus = $messageInfo->messageStatus;
$messageContent = $messageInfo->messageContent;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-comments"></i> Message
            <small>Message Detail</small>
        </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Message Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="editMessage" action="<?php echo base_url() ?>editMessage" method="get" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">ReceiverId</label>
                                        <input type="text" class="form-control required" disabled
                                               value="<?php echo $receiverId; ?>"
                                               id="appointmentTime" name="appointmentTime" placeholder="Appointment Time" maxlength="128">
                                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Subject</label>
                                        <input type="text" class="form-control required" disabled
                                               value="<?php echo $subject; ?>"
                                               id="email" name="location" maxlength="200">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Message Status</label>
                                        <input type="text" class="form-control required" disabled
                                               value="<?php echo $messageStatus; ?>"
                                               id="topic" name="topic" maxlength="50">
                                    </div>
                                </div>
                            </div>
                            </br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="mobile">Message Content</label>
                                        <textarea class="form-control" aria-label="With textarea" disabled
                                                  id="messageContent" name="messageContent" maxlength="200"><?php echo $messageContent; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
<!--                            <input type="submit" class="btn btn-primary" value="Send"/>-->
<!--                            <input type="reset" class="btn btn-default" value="Reset"/>-->
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if($error)
                {
                    ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
                <?php
                $success = $this->session->flashdata('success');
                if($success)
                {
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

<script src="<?php echo base_url(); ?>assets/js/editMessage.js" type="text/javascript"></script>


