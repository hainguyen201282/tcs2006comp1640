<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap"></i> Student Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div <?php if ($role == AUTHORISED_STAFF || $role == STAFF): ?>
                style="display:block !important;" class="row">
            <div class="col-xs-6 text-left">
                <a class="btn btn-primary" href="<?php echo base_url(); ?>viewAssignTutor">
                    Allocate / Reallocate Tutor
                </a>
            </div>
            <div class="col-xs-6 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewStudent"><i class="fa fa-plus"></i>
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
                                <th>TutorID</th>
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
                                        <td><?php echo $record->email ?></td>
                                        <td><?php echo $record->name ?></td>
                                        <td><?php echo $record->mobile ?></td>
                                        <td><?php echo $record->gender ?></td>
                                        <td><?php echo $record->tutorId ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                        <td <?php if ($role == AUTHORISED_STAFF || $role == STAFF): ?>
                                                style="display:block !important;" class="text-center">
                                            <a class="btn btn-sm btn-primary"
                                               href="<?= base_url() . 'login-history/' . $record->studentId; ?>"
                                               title="Login history">
                                                <i class="fa fa-history"></i>
                                            </a> |
                                            <a class="btn btn-sm btn-info"
                                               href="<?php echo base_url() . 'editOldStudent/' . $record->studentId; ?>"
                                               title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a> |
                                            <a class="btn btn-sm btn-danger deleteStudent" href="#"
                                               data-studentId="<?php echo $record->studentId; ?>"
                                               title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                        <td <?php elseif ($role == TUTOR): ?> class="text-center">
                                            <a class="btn btn-sm btn-primary open-mes-modal" href="#mes-modal"
                                               data-toggle="modal"
                                               data-senderId="<?php echo $vendorId; ?>"
                                               data-senderRole="<?php echo $role; ?>"
                                               data-receiverId="<?php echo $record->studentId; ?>"
                                               data-receiverRole="<?php echo $record->roleId; ?>"
                                               data-email="<?php echo $record->email; ?>">
                                                <i class="fa fa-paper-plane-o"></i>
                                            </a>
                                        </td>
                                        <?php endif; ?>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl-student').DataTable({
            'info': true,
            'searching': true,
            'paging': true,
            'lengthChange': true,
        });

        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: '<?php echo site_url('assets/js/ckfinder/ckfinder.html');?>',
            filebrowserUploadUrl: '<?php echo site_url('assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files');?>',
        });

        // get student info when click button
        let studentInfo;
        $('.open-mes-modal').click(function () {
            studentInfo = {

                email: $(this).attr('data-email'),

                senderId: $(this).attr('data-senderId'),
                senderRole: $(this).attr('data-senderRole'),

                receiverId: $(this).attr('data-receiverId'),
                receiverRole: $(this).attr('data-receiverRole'),
            };
        });

        // display email
        $('#mes-modal').on('show.bs.modal', function () {
            $('#receiver').val(studentInfo.email);
        }).submit(function () {
            // get value from submit form
            const email = $('#receiver').val();
            const subject = $('#subject').val();
            const content = $('#content').val();

            const senderId = studentInfo.senderId;
            const senderRole = studentInfo.senderRole;

            const receiverId = studentInfo.receiverId;
            const receiverRole = studentInfo.receiverRole;

            // message entity
            const message = {
                subject: subject,
                content: content,
                senderId: senderId,
                senderRole: senderRole,

            };

            // message_attr entity
            const messageAttr = {
                receiverId: receiverId,
                receiverRole: receiverRole,
            };

            $.ajax({
                type: "POST",
                dataType: "json",
                url: baseURL + "sendMessage",
                data: {
                    email: email,
                    message: message,
                    messageAttr: messageAttr,
                },
            }).done(function (data) {
                alert(data.result);
                if (data.result === true) {
                    alert("Send message successfully");
                } else {
                    alert("Send message failure");
                }
            });
        })
    });

</script>

