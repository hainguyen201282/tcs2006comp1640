<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-handshake-o"></i> Conference Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewConference">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <table id="tbl-conference" class="table display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Appointment Time</th>
                                <th>Location</th>
                                <th>Topic</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Created Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($conferenceRecords)) {
                                foreach ($conferenceRecords as $record) {
                                    ?>
                                    <tr>
                                        <td><?php echo $record->appTime ?></td>
                                        <td><?php echo $record->location ?></td>
                                        <td><?php echo $record->topic ?></td>
                                        <td><?php echo $record->type ?></td>
                                        <td><?php echo $record->status ?></td>
                                        <td><?php echo $record->description ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->createdDate)) ?></td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-info" title="Edit"
                                               href="<?php echo base_url() . 'editConferenceView/' . $record->id; ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a> |
                                            <a class="btn btn-sm btn-danger deleteConference" href="#"
                                               data-conferenceId="<?php echo $record->id; ?>" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl-conference').DataTable({
            'info': true,
            'searching': true,
            'paging': true,
            'lengthChange': true,
        });

        $(this).on("click", ".deleteConference", function () {
            const id = $(this).attr("data-conferenceId");

            let confirmation = confirm("Are you sure to delete this Conference?");
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: baseURL + "deleteConference",
                    data: {
                        id: id
                    },
                    dataType: "json"
                }).done(function (data) {
                    console.log(data);
                    if (data.status) {
                        alertMessage("Conference successfully deleted");
                    } else {
                        alertMessage("Conference delete failed");
                    }
                });
            }
        });
    });

    function alertMessage($message) {
        let confirmation = confirm($message);
        if (confirmation) {
            location.reload();
        }
    }
</script>

