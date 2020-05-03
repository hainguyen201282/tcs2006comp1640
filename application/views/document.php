<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> Document Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewDocument">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <table id="tbl-document" class="table display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Topic</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($documentRecords)) {
                                foreach ($documentRecords as $record) {
                                    ?>
                                    <tr>
                                        <td><?php echo $record->topic ?></td>
                                        <td class="text-center">
                                            <?php if (isset($vendorId) && ($vendorId != $record->vendorId || $role != $record->vendorRole) ) { ?>
                                                <a class="btn btn-sm btn-primary" title="Document Detail"
                                                   href="<?php echo base_url() . 'documentDetail/' . $record->documentId; ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            <?php } elseif($vendorId == $record->vendorId && $role == $record->vendorRole) { ?>
                                                <a class="btn btn-sm btn-primary" title="Document Detail"
                                                   href="<?php echo base_url() . 'documentDetail/' . $record->documentId; ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a> |
                                                <a class="btn btn-sm btn-info" title="Edit"
                                                   href="<?php echo base_url() . 'editViewDocument/' . $record->documentId; ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger deleteDocument" href="#" title="Delete"
                                                   data-documentId="<?= $record->documentId; ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php } ?>
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

<style>
    * {
        box-sizing: border-box;
    }

    /* Create three equal columns that floats next to each other */
    .column {
        float: left;
        width: 33.33%;
        padding: 10px;
        height: 300px;
        /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
</style>

<script type="text/javascript" charset="utf-8">

    $(document).ready(function () {

        $('#tbl-document').DataTable({
            'info': true,
            'searching': true,
            'paging': true,
            'lengthChange': true,
        });

        $(this).on("click", ".deleteDocument", function () {
            const id = $(this).attr("data-documentId");

            let confirmation = confirm("Are you sure to delete this document?");
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: baseURL + "deleteDocument",
                    data: {
                        documentId: id
                    },
                    dataType: "json"
                }).done(function (data) {
                    console.log(data);
                    if (data.status) {
                        alertMessage("Document successfully deleted");
                    } else {
                        alertMessage("Document delete failed");
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
