<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Message Management
            <small>Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewMessage"><i class="fa fa-paper-plane"></i> Send New Massage</a>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Message List</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>messageListing" method="POST" id="searchList">
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
                                <th>ReceiverId</th>
                                <th>Subject</th>
                                <th>MessageStatus</th>
                                <th>MessageContent</th>
                                <th>Sent On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            <?php
                            if(!empty($messageRecords))
                            {
                                foreach($messageRecords as $record)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $record->receiverId ?></td>
                                        <td><?php echo $record->subject ?></td>
                                        <td><?php echo $record->messageStatus ?></td>
                                        <td><?php echo $record->messageContent ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editOldMessage/'.$record->id; ?>" title="View Message"><i class="fa fa-eye"></i></a>
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
            jQuery("#searchList").attr("action", baseURL + "messageListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>

