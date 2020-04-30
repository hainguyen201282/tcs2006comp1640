<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-files-o"></i> Blog Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewBlog">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <table id="tbl-blog" class="table display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Blog Name</th>
                                <th>Topic</th>
                                <th>Content</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($blogRecords)) {
                                foreach ($blogRecords as $record) {
                                    ?>
                                    <tr>
                                        <td><?php echo $record->title ?></td>
                                        <td><?php echo $record->topic ?></td>
                                        <td><?php echo $record->content ?></td>
                                        <td><?php echo $record->status ?></td>
                                        <td class="text-center">
                                            <?php if (isset($vendorId) && $vendorId != $record->author && $record->status == PUBLISH) { ?>
                                                <a class="btn btn-sm btn-primary" title="Blog Detail"
                                                   href="<?php echo base_url() . 'blogDetail/' . $record->id; ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            <?php } elseif($vendorId == $record->author) { ?>
                                                <a class="btn btn-sm btn-primary" title="Blog Detail"
                                                   href="<?php echo base_url() . 'blogDetail/' . $record->id; ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a> |
                                                <a class="btn btn-sm btn-info" title="Edit"
                                                   href="<?php echo base_url() . 'editViewBlog/' . $record->id; ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger deleteBlog" href="#" title="Delete"
                                                   data-blogId="<?= $record->id; ?>">
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

        $('#tbl-blog').DataTable({
            'info': true,
            'searching': true,
            'paging': true,
            'lengthChange': true,
        });

        $(this).on("click", ".deleteBlog", function () {
            const id = $(this).attr("data-blogId");

            let confirmation = confirm("Are you sure to delete this blog?");
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: baseURL + "deleteBlog",
                    data: {
                        blogId: id
                    },
                    dataType: "json"
                }).done(function (data) {
                    console.log(data);
                    if (data.status) {
                        alertMessage("Blog successfully deleted");
                    } else {
                        alertMessage("Blog delete failed");
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
