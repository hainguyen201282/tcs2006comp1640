<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> User Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                	<form action="<?php echo base_url(); ?>importUsers" method="post" class=" text-right" enctype="multipart/form-data">
	                    <button class="btn btn-primary btn-block btn-flat" style="width: fit-content; float: right;border-radius: 3px;" onclick="checkSubmit(event, this);"><i class="fa fa-cloud-upload"></i> Import</button>
	                    <input type="file" id="uploadUserExcelFile" style="display:none;" name="uploadUserData" accept=".xls,.xlsx">
	                </form>
	                <a class="btn btn-primary" href="<?php echo base_url(); ?>exportUsers" style="margin-right: 10px;float: right;"><i class="fa fa-cloud-download"></i> Export</a>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNew" style="margin-right: 10px;"><i class="fa fa-plus"></i> Add New</a>
                  	
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <table id="tbl-user" class="table display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Role</th>
                                <th>Created On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($userRecords)) {
                                foreach ($userRecords as $record)
                                { ?>
                                    <tr>
                                        <td><?php echo $record->name ?></td>
                                        <td><?php echo $record->email ?></td>
                                        <td><?php echo $record->mobile ?></td>
                                        <td><?php echo $record->role ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-primary"
                                               href="<?= base_url() . 'login-history/' . $record->userId; ?>"
                                               title="Login history"><i class="fa fa-history"></i></a> |
                                            <a class="btn btn-sm btn-info"
                                               href="<?php echo base_url() . 'editOld/' . $record->userId; ?>"
                                               title="Edit"><i class="fa fa-pencil"></i></a>
                                            <a class="btn btn-sm btn-danger deleteUser" href="#"
                                               data-userid="<?php echo $record->userId; ?>" title="Delete"><i
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl-user').DataTable({
            'info': true,
            'searching': true,
            'paging': true,
            'lengthChange': true,
        });        
    })
    function checkSubmit(event, thisElement){
    	event.preventDefault();
	    let nextSibling  = $(thisElement).next();
	    nextSibling.trigger('click');
	}

	$("#uploadUserExcelFile").change(function (){
       let uploadForm = $(this).parent();
       $(uploadForm).submit();
     });
</script>
