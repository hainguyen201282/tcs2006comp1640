<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap"></i> Student Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
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
        </div>
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
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-primary"
                                               href="<?= base_url() . 'login-history/' . $record->studentId; ?>"
                                               title="Login history"><i class="fa fa-history"></i></a> |
                                            <a class="btn btn-sm btn-info"
                                               href="<?php echo base_url() . 'editOldStudent/' . $record->studentId; ?>"
                                               title="Edit"><i class="fa fa-pencil"></i></a> |
                                            <a class="btn btn-sm btn-danger deleteStudent" href="#"
                                               data-studentId="<?php echo $record->studentId; ?>" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
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

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#tbl-student').DataTable({
            'info': true,
            'searching': true,
            'paging': true,
            'lengthChange': true,
        });
    })
</script>

