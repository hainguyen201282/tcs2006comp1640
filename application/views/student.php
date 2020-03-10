<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap"></i> Student Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewStudent"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Student List</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>studentListing" method="POST" id="searchList">
                                <div class="input-group">
                                    <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Email</th>
                                <th>Student Name</th>
                                <th>Mobile Phone Number</th>
<!--                                <th>roleId</th>-->
                                <th>Gender</th>
                                <th>TutorID</th>
                                <th>Created On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            <?php
                            if(!empty($studentRecords))
                            {
                                foreach($studentRecords as $record)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $record->email ?></td>
                                        <td><?php echo $record->name ?></td>
                                        <td><?php echo $record->mobile ?></td>
<!--                                        <td>--><?php //echo $record->roleId ?><!--</td>-->
                                        <td><?php echo $record->gender ?></td>
                                        <td><?php echo $record->tutorId ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-primary" href="<?= base_url().'login-history/'.$record->studentId; ?>" title="Login history"><i class="fa fa-history"></i></a> |
                                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editOldStudent/'.$record->studentId; ?>" title="Edit"><i class="fa fa-pencil"></i></a> |
                                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'assignOldStudent/'.$record->studentId; ?>" title="Asssign"><i class="fa fa-puzzle-piece"></i></a> |
                                            <a class="btn btn-sm btn-danger deleteStudent" href="#" data-studentId="<?php echo $record->studentId; ?>" title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>

                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "studentListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>

