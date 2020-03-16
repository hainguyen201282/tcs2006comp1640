<?php
$id = $blogInfo->id;
$title = $blogInfo->title;
$content = $blogInfo->content;
$topic = $blogInfo->topic;
$cover = $blogInfo->cover;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Blog Management
        <small>Add / Edit Blog</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Blog Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addBlog" action="<?php echo base_url() ?>editBlog" method="post">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control required" value="<?php echo $title; ?>" id="title" name="title" maxlength="128">
                                        <input type="hidden" value="<?php echo $id; ?>" name="blogId" id="blogId" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="topic">Topic</label>
                                        <input type="text" disabled class="form-control required topic" id="topic" value="<?php echo $topic; ?>" name="topic">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content">Content</label>
                                        <textarea id="content" style="width:100%" value="<?php echo $content; ?>" name="content" maxlength="65000"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Upload -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="file" name="userfile" size="20"/>
                                    </div>   
                                </div>
                            </div>
                            <!--  -->
                        </div>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit"/>
                            <input type="reset" class="btn btn-default" value="Reset"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>                    
                    </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
</div>