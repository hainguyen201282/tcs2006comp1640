<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-comments"></i> Message Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewMessage"><i class="fa fa-plus"></i>
                        Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">INBOX</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="tbl-message" class="table display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Received From</th>
                                <th>Subject</th>
                                <th>Content</th>
                                <th>Sent On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($messageRecords1)) {
                                foreach ($messageRecords1 as $record1) {
                                    ?>
                                    <tr>
                                        <td><?php echo $record1->senderByUserId ?></td>
                                        <td><?php echo $record1->subject ?></td>
                                        <td><?php echo $record1->messageContent ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record1->createdDtm)) ?></td>
                                        <td class="text-center">

<!--                                            <a class="btn btn-sm btn-info"-->
<!--                                               href="--><?php //echo base_url() . 'editOldMessage/' . $record1->id; ?><!--"-->
<!--                                               title="Edit"><i class="fa fa-pencil"></i></a> |-->

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
        <!--        fdasfsa-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">SENT MESAGES</h3>

                    </div>
                    <div class="box-body table-responsive">
                        <table id="tbl-message" class="table display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Sent to</th>
                                <th>Subject</th>
                                <th>Content</th>
                                <th>Sent On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($messageRecords2)) {
                                foreach ($messageRecords2 as $record2) {
                                    ?>
                                    <tr>
                                        <td><?php echo $record2->receiverByUserId ?></td>
                                        <td><?php echo $record2->subject ?></td>
                                        <td><?php echo $record2->messageContent ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record2->createdDtm)) ?></td>
                                        <td class="text-center">

<!--                                            <a class="btn btn-sm btn-info"-->
<!--                                               href="--><?php //echo base_url() . 'editOldMessage/' . $record2->id; ?><!--"-->
<!--                                               title="Edit"><i class="fa fa-pencil"></i></a> |-->

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


        <!--        fdsafsd-->
    </section>
</div>

<link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

<script type="text/javascript" rel='stylesheet' src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" rel='stylesheet'
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" rel='stylesheet'
        src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript" defer>
    $(document).ready(function () {
        $('#tbl-message').DataTable({
            'initComplete': function () {
            }
        });
    });

    // jQuery(document).ready(function () {
    //     jQuery('ul.pagination li a').click(function (e) {
    //         e.preventDefault();
    //         const link = jQuery(this).get(0).href;
    //         const value = link.substring(link.lastIndexOf('/') + 1);
    //         jQuery("#searchList").attr("action", baseURL + "messageListing/" + value);
    //         jQuery("#searchList").submit();
    //     });
    // });
</script>

