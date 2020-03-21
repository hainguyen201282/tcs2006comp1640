<?php
$id = $conferenceInfo->id;
$appointmentTime = $conferenceInfo->appointmentTime;
$location = $conferenceInfo->location;
$topic = $conferenceInfo->topic;
$type = $conferenceInfo->type;
$cstatus = $conferenceInfo->cstatus;
$description = $conferenceInfo->description;

$studentId = $studentInfo->studentId;
$studentName = $studentInfo->studentName;

?>

<script type="text/javascript">      
    $(document).ready(function(){
        var ckeditor = CKEDITOR.replace('description', {
            filebrowserBrowseUrl: '<?php echo site_url('assets/js/ckfinder/ckfinder.html');?>',
            filebrowserUploadUrl: '<?php echo site_url('assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files');?>',
        });
    });   
</script>

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
                    <form role="form" id="editConference" action="<?php echo base_url() ?>editConference" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">Appointment Time</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $appointmentTime; ?>"
                                               id="appointmentTime" name="appointmentTime" placeholder="Appointment Time" maxlength="128">
                                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Location</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $location; ?>"
                                               id="email" name="location" maxlength="200">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Topic</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $topic; ?>"
                                               id="topic" name="topic" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="Real"  <?php if($type == "Real") echo "SELECTED";?>>Real</option>
                                            <option value="Virtual" <?php if($type == "Virtual") echo "SELECTED";?>>Virtual</option>
                                            <?php if(isset($_POST["type"])) { echo $_POST["type"]; } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Status</label>
                                        <select class="form-control" id="cstatus" name="cstatus">
                                            <option value="Activated"  <?php if($cstatus == "Activated") echo "SELECTED";?>>Activated</option>
                                            <option value="Deactivated"  <?php if($cstatus == "Deactivated") echo "SELECTED";?>>Deactivated</option>
                                            <?php if(isset($_POST["status"])) { echo $_POST["status"]; } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Description</label>
                                        <textarea class="form-control"  aria-label="With textarea"
                                                  id="description" name="description" maxlength="200"><?php echo $description; ?>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <!--Update 19-3-2020-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Student</label>
                                        <input type="text" class="form-control required"
                                               value="<?php echo $studentName; ?>"
                                               id="topic" name="topic" maxlength="50">
                                    </div>
                                </div>
                            </div>

                            <div class="box-body table-responsive">
                                <table id="tbl-student" class="table display" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
<!--                                    --><?php
//                                    if (!empty($studentInfo)) {
//                                        foreach ($studentInfo as $record) {
//                                            ?>
<!--                                            <tr>-->
<!--                                                <td>--><?php //echo $record->studentId ?><!--</td>-->
<!--                                                <td>--><?php //echo $record->studentName ?><!--</td>-->
<!---->
<!--                                            </tr>-->
<!--                                            --><?php
//                                        }
//                                    }
//                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End of Update 19-3-2020-->
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

<script src="<?php echo base_url(); ?>assets/js/editConference.js" type="text/javascript"></script>


