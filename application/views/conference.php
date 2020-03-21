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
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewConference"><i class="fa fa-plus"></i>
                        Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Conference List</h3>
                        <!--                        <div class="box-tools">-->
                        <!--                            <form action="--><?php //echo base_url() ?><!--conferenceListing" method="POST" id="searchList">-->
                        <!--                                <div class="input-group">-->
                        <!--                                    <input type="text" name="searchText" value="--><?php //echo $searchText; ?><!--"-->
                        <!--                                           class="form-control input-sm pull-right" style="width: 150px;"-->
                        <!--                                           placeholder="Search"/>-->
                        <!--                                    <div class="input-group-btn">-->
                        <!--                                        <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i>-->
                        <!--                                        </button>-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </form>-->
                        <!--                        </div>-->
                    </div>
                    <div class="box-body table-responsive">
                        <table id="tbl-conference" class="table display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Conference ID</th>
                                <th>Appointment Time</th>
                                <th>Location</th>
                                <th>Topic</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Created On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($conferenceRecords)) {
                                foreach ($conferenceRecords as $record) {
                                    ?>
                                    <tr>
                                        <td><?php echo $record->id ?></td>
                                        <td><?php echo $record->appointmentTime ?></td>
                                        <td><?php echo $record->location ?></td>
                                        <td><?php echo $record->topic ?></td>
                                        <td><?php echo $record->type ?></td>
                                        <td><?php echo $record->cstatus ?></td>
                                        <td><?php echo $record->description ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-info"
                                               href="<?php echo base_url() . 'editOldConference/' . $record->id; ?>"
                                               title="Edit"><i class="fa fa-pencil"></i></a> |
                                            <a class="btn btn-sm btn-danger deleteConference" href="#"
                                               data-conferenceId="<?php echo $record->id; ?>" title="Delete"><i
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

<!--<script type="text/javascript">-->
<!--    jQuery(document).ready(function(){-->
<!--        jQuery(document).on("click", ".deleteConference", function(){-->
<!--            var id = $(this).data("id"),-->
<!--                hitURL = baseURL + "deleteConference",-->
<!--                currentRow = $(this);-->
<!---->
<!--            var confirmation = confirm("Are you sure to delete this conference ?");-->
<!---->
<!--            if(confirmation)-->
<!--            {-->
<!--                jQuery.ajax({-->
<!--                    type : "POST",-->
<!--                    dataType : "json",-->
<!--                    url : hitURL,-->
<!--                    data : { id : id }-->
<!--                }).done(function(data){-->
<!--                    console.log(data);-->
<!--                    currentRow.parents('tr').remove();-->
<!--                    if(data.status = true) { alert("Conference successfully deleted"); }-->
<!--                    else if(data.status = false) { alert("Conference deletion failed"); }-->
<!--                    else { alert("Access denied..!"); }-->
<!--                });-->
<!--            }-->
<!--        });-->
<!---->
<!--        jQuery(document).on("click", ".searchList", function(){-->
<!---->
<!--        });-->
<!---->
<!--    });-->
<!--</script>-->

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "conferenceListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>

