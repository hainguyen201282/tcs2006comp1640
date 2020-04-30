<?php
$blogId = isset($blogInfo->id) ? $blogInfo->id : '-1';
$title = isset($blogInfo->title) ? $blogInfo->title : '';
$topic = isset($blogInfo->topic) ? $blogInfo->topic : '';
$content = isset($blogInfo->content) ? $blogInfo->content : '';
$coverImg = isset($blogInfo->coverImg) ? $blogInfo->coverImg : 'cover.png';
$publishDate = isset($blogInfo->createdDate) ? $blogInfo->createdDate : '0000-00-00 00:00:00';
$author = isset($authorInfo->name) ? $authorInfo->name : 'Unknown';
$joined = isset($authorInfo->createdDtm) ? $authorInfo->createdDtm : '0000-00-00 00:00:00';
$description = isset($authorInfo->description) ? $authorInfo->description : 'N/A';
$imgAvatar = isset($authorInfo->imgAvatar) ? $authorInfo->imgAvatar : 'avatar.png';
?>

<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/blogDetail.css">

<div class="content-wrapper">
    <section class="hero-wrap hero-wrap-2 js-fullheight" data-stellar-background-ratio="0.5">
        <div class="overlay"
             style="background-image: url('<?php echo base_url() . COVER_PATH . $coverImg; ?>');"></div>
    </section>

    <section class="ftco-section ftco-degree-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 ftco-animate">
                    <h1 class="mb-3 bread"><?= $title; ?></h1>
                </div>
                <div class="col-lg-8 ftco-animate">
                    <p class="publish-date"><?php echo date($publishDate) ?></p>
                </div>
                <div class="col-lg-8 ftco-animate">
                    <?= $content; ?>

                    <div class="tag-widget post-tag-container mb-5 mt-5">
                        <div class="tagcloud">
                            <a href="javascript:void(0)" class="tag-cloud-link"><?= $topic ?></a>
                        </div>
                    </div>

                    <div class="about-author d-flex p-4 bg-light">
                        <div class="bio mr-5 col-lg-4">
                            <img class="img-fluid mb-4 img-responsive img-circle" alt="Author picture" style="max-width: 100px; max-height: 114px;"
                                 src="<?php echo base_url() . AVATAR_PATH . $imgAvatar; ?>">
                        </div>
                        <div class="desc">
                            <h3><?= $author ?></h3>
                            <p>Joined: <?= date($joined) ?></p>
                            <p><?= $description ?></p>
                        </div>
                    </div>

                    <?php if (!empty($blogComments)) { ?>
                        <div class="pt-5 mt-5">
                            <h3 class="mb-5"><?php echo count($blogComments) ?> Comments</h3>
                            <ul class="comment-list">
                                <?php foreach ($blogComments as $record) { ?>
                                    <li class="comment">
                                        <div class="vcard bio">
                                            <img alt="Comment user image"  style="max-width: 100px; max-height: 114px;" 
                                                 src="<?php echo base_url() . AVATAR_PATH . $record->imgAvatar; ?>">
                                        </div>
                                        <div class="comment-body">
                                            <h3><?= $record->name ?></h3>
                                            <div class="meta mb-3">
                                                <?= date($record->createdDate); ?>
                                            </div>
                                            <p <?php if (isset($vendorId) && $vendorId == $record->userId && $role == $record->userRole) { ?> onClick="showEditComment(this);" <?php }?>>
                                                <?= $record->content ?>      
                                            </p>
                                            <?php if (isset($vendorId) && $vendorId == $record->userId && $role == $record->userRole) { ?>
                                            <textarea name="my_comment" class="form-control" cols="10" rows="3" data-commentId="<?= $record->id; ?>" style="display: none; resize: none;" onblur="finishEditComment(this);" required><?= $record->content ?></textarea>
                                            <p>
                                                <a class="deleteComment" href="javascript:void(0)"
                                                   data-commentId="<?= $record->id; ?>">Delete</a>
                                            </p>
                                            <?php }?>
                                        </div>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <?php
                    } ?>

                    <div class="comment-form-wrap pt-5">
                        <h3 class="mb-5">Leave a comment</h3>
                        <?php $this->load->helper("form"); ?>
                        <form class="p-5 bg-light" action="<?php echo base_url(); ?>submitComment" method="post"
                              role="form">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input id="blogId" class="form-control" type="hidden"
                                       name="blogId" value="<?= $blogId ?>">
                                <input id="name" class="form-control" type="text"
                                       name="name" value="<?= empty($name) ? '' : $name ?>" required disabled>
                            </div>
                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" class="form-control" cols="30" rows="10" maxlength="65000"
                                          name="message" required><?php echo set_value('message'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Post Comment" class="btn py-3 px-4 btn-primary">
                            </div>
                        </form>
                    </div>
                </div> <!-- .col-md-8 -->
                <div class="col-md-4">
                    <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if ($this->session->flashdata('error') !== null) {
                        ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php } ?>
                    <?php
                    $success = $this->session->flashdata('success');
                    if ($success) {
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
                <!--Right Sidebar -->
                <div class="col-lg-4 sidebar pl-lg-5 ftco-animate" style="display: none">
                    <div class="sidebar-box ftco-animate">
                        <h3>Related Blog</h3>
                        <div class="block-21 mb-4 d-flex">
                            <a class="blog-img mr-4" style="background-image: url('<?php echo base_url(); ?>');"></a>
                            <div class="text">
                                <h3 class="heading">
                                    <a href="javascript:void(0)">Even the all-powerful Pointing has no control about the blind texts</a>
                                </h3>
                                <div class="meta">
                                    <div><a href="javascript:void(0)"><span class="icon-calendar"></span> Nov. 14, 2019</a></div>
                                    <div><a href="javascript:void(0)"><span class="icon-person"></span> Admin</a></div>
                                    <div><a href="javascript:void(0)"><span class="icon-chat"></span> 19</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-box ftco-animate">
                        <h3>Tag Topic</h3>
                        <div class="tagcloud">
                            <?php if (!empty($blogTopics)) {
                                foreach ($blogTopics as $record) { ?>
                                    <a href="javascript:void(0)" class="tag-cloud-link"><?= $record->topic ?></a>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> <!-- .section -->
</div>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $(this).on("click", ".deleteComment", function () {
            const id = $(this).attr("data-commentId");

            let confirmation = confirm("Are you sure to delete this comment?");
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: baseURL + "deleteComment",
                    data: {
                        commentId: id
                    },
                    dataType: "json"
                }).done(function (data) {
                    console.log(data);
                    if (data) {
                        alert("Comment successfully deleted");
                    } else {
                        alert("Comment delete failed");
                    }
                    location.reload();
                });
            }
        });
    });

    function showEditComment(thisElement){
        $(thisElement).next().show();
        $(thisElement).next().focus();
        $(thisElement).hide();
    }

    function finishEditComment(thisElement){
        $(thisElement).prev().text($(thisElement).val());
        $(thisElement).prev().show();
        $(thisElement).hide();

        const id = $(thisElement).attr("data-commentId");

        $.ajax({
            type: "POST",
            url: baseURL + "updateComment",
            data: {
                commentId: id,
                content: $(thisElement).val()
            },
            dataType: "json"
        }).done(function (data) {
            // console.log(data);
            if (data) {
                // alert("Comment successfully updated");
            } else {
                alert("Comment update failed");
            }
        });
    }
</script>