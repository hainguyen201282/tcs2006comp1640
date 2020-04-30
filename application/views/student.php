<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap"></i> Student Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row" <?php if ($role == AUTHORISED_STAFF || $role == STAFF): ?>style="display:block !important;">
            <div class="col-xs-6 text-left">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>viewAssignTutor"
                       style="margin-top: 3px;">
                        Allocate / Reallocate Tutor
                    </a>
                </div>
            </div>
            <div class="col-xs-6 text-right">
                <div class="form-group">
                    <form action="<?php echo base_url(); ?>importStudents" method="post" class=" text-right"
                          enctype="multipart/form-data">
                        <button class="btn btn-primary btn-block btn-flat"
                                style="width: fit-content; float: right;border-radius: 3px;margin-left: 10px;margin-top: 3px;"
                                onclick="checkSubmit(event, this);">
                            <i class="fa fa-cloud-upload"></i> Import
                        </button>
                        <input type="file" id="uploadStudentExcelFile" style="display:none;" name="uploadStudentData"
                               accept=".xls,.xlsx">
                    </form>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>exportStudents"
                       style="float: right;margin-left: 10px;margin-top: 3px;">
                        <i class="fa fa-cloud-download"></i>
                        Export
                    </a>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewStudent"
                       style="margin-left: 10px;margin-top: 3px;">
                        <i class="fa fa-plus"></i>
                        Add New
                    </a>
                </div>
            </div>
        </div><?php endif; ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <table id="tbl-student" class="table display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>Student Name</th>
                                <th>Mobile Phone Number</th>
                                <th>Gender</th>
                                <th>Tutor Name</th>
                                <th>Created On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($studentRecords)) {
                                foreach ($studentRecords as $record) {
                                    ?>
                                    <tr>
                                        <td><?= $record->email ?></td>
                                        <td><?= $record->name ?></td>
                                        <td><?= $record->mobile ?></td>
                                        <td><?= $record->gender ?></td>
                                        <td><?= $record->tutorName == '' ? 'N/A' : $record->tutorName ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                        <td class="text-center">
                                            <?php if ($role == AUTHORISED_STAFF || $role == STAFF): ?>
                                            	<?php if ($role == AUTHORISED_STAFF) { ?>
                                                <a class="btn btn-sm btn-primary"
                                                   href="<?php echo base_url() . 'studentDashboard/' . $record->studentId; ?>"
                                                   title="Dashboard"><i class="fa fa-dashboard"></i>
                                                </a> |
                                            	<?php } ?>
                                                <a class="btn btn-sm btn-info"
                                                   href="<?php echo base_url() . 'editOldStudent/' . $record->studentId; ?>"
                                                   title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger deleteStudent" href="#"
                                                   data-studentId="<?= $record->studentId; ?>"
                                                   title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php elseif ($role == TUTOR): ?>
                                                <a class="btn btn-sm btn-primary open-mes-modal" href="#mes-modal"
                                                   data-toggle="modal"
                                                   data-senderId="<?= $vendorId; ?>"
                                                   data-senderRole="<?= $role; ?>"
                                                   data-receiverId="<?= $record->studentId; ?>"
                                                   data-receiverRole="<?= $record->roleId; ?>"
                                                   data-email="<?= $record->email; ?>">
                                                    <i class="fa fa-paper-plane-o"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <?php echo $this->pagination->create_links(); ?>
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
                                           type="text" placeholder="To" maxlength="20" value="" disabled>
                                </div>
                                <div class="form-group">
                                    <input id="subject" name="subject" class="form-control required text-line"
                                           type="text" placeholder="Subject" maxlength="128" value="" required>
                                </div>
                            </div>
                        </div>
                        <div>
                            <textarea id="content" name="messageContent" class="form-control required"
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js?<?php echo time(); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sendMessage.js?<?php echo time(); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl-student').DataTable({
            'info': true,
            'searching': true,
            'paging': true,
            'lengthChange': true,
        });

        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: '<?php echo
            site_url('assets/js/ckfinder/ckfinder.html');?>',
            filebrowserUploadUrl: '<?php echo
            site_url('assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files');?>',
        });
    });

    function checkSubmit(event, thisElement) {
        event.preventDefault();
        let nextSibling = $(thisElement).next();
        nextSibling.trigger('click');
    }

    $("#uploadStudentExcelFile").change(function () {
        let uploadForm = $(this).parent();
        $(uploadForm).submit();
    });
</script>
