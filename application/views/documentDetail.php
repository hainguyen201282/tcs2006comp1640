<?php
$documentId = isset($documentInfo[0]->documentId) ? $documentInfo[0]->documentId : '-1';
$topic = ($documentInfo) ? $documentInfo[0]->topic : '';
$id = ($documentInfo) ? $documentInfo[0]->documentId : '';

$publishDate = isset($documentInfo[0]->createdDtm) ? $documentInfo[0]->createdDtm : '0000-00-00 00:00:00';
$author = isset($authorInfo->name) ? $authorInfo->name : 'Unknown';
$joined = isset($authorInfo->createdDtm) ? $authorInfo->createdDtm : '0000-00-00 00:00:00';
$description = isset($authorInfo->description) ? $authorInfo->description : 'N/A';
$imgAvatar = isset($authorInfo->imgAvatar) ? $authorInfo->imgAvatar : 'avatar.png';
?>

<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/blogDetail.css">

<div class="content-wrapper">
    <link href="<?php echo base_url() ?>assets/bootstrap-fileinput/css/fileinput.min.css?<?php echo time(); ?>" media="all" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/all.css?<?php echo time(); ?>" media="all" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/jquery-confirm.min.css?<?php echo time(); ?>" media="all" rel="stylesheet" type="text/css" />
    <section class="ftco-section ftco-degree-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 ftco-animate">
                    <h1 class="mb-3 bread"><?= $topic; ?></h1>
                </div>
                <div class="col-lg-8 ftco-animate">
                    <p class="publish-date"><?php echo date($publishDate) ?></p>
                </div>
                <div class="col-lg-8 ftco-animate">
                    <div class="file-loading">
                        <input id="input-res-3" name="input-res-3[]" type="file" multiple>
                    </div>

                    <!-- <div class="tag-widget post-tag-container mb-5 mt-5">
                        <div class="tagcloud">
                            <a href="javascript:void(0)" class="tag-cloud-link"><?= $topic ?></a>
                        </div>
                    </div> -->

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

                    <?php if (!empty($documentComments)) { ?>
                        <div class="pt-5 mt-5">
                            <h3 class="mb-5"><?php echo count($documentComments) ?> Comments</h3>
                            <ul class="comment-list">
                                <?php foreach ($documentComments as $record) { ?>
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
                        <form class="p-5 bg-light" action="<?php echo base_url(); ?>submitDocumentComment" method="post"
                              role="form">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input id="documentId" class="form-control" type="hidden"
                                       name="documentId" value="<?= $documentId ?>">
                                <input id="name" class="form-control" type="text"
                                       name="name" value="<?= empty($name) ? '' : $name ?>" required>
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
                    url: baseURL + "deleteDocumentComment",
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
            url: baseURL + "updateDocumentComment",
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

<script>
                    $(document).ready(function() {
                        var $el1 = $("#input-res-3");
                        $el1.fileinput({
                            // allowedFileExtensions: ['jpg', 'png', 'gif'],
                            uploadUrl: "<?php echo base_url(); ?>uploadDocument",
                            showBrowse: false,
                            browseOnZoneClick: false,
                            fileActionSettings: {
                                showZoom: false,
                                showDownload: true, // show download button
                                showDrag: false
                            },
                            initialPreviewShowDelete: false,
                            showCaption: false,
                            uploadAsync: true,
                            showUpload: false, // hide upload button
                            showRemove: false, // hide remove button
                            enableResumableUpload: false,
                            minFileCount: 1,
                            maxFileCount: 10,
                            theme: 'fas',
                            // deleteUrl: '<?php //echo base_url(); ?>deleteDocument',
                            // required: true,
                            uploadExtraData: {
                                //uploadToken: "SOME_VALID_TOKEN"
                            },
                            overwriteInitial: false,
                            initialPreview: [
                                '<?= implode("','", $arrInitialPreviewData);?>'
                            ],
                            initialPreviewAsData: true, // defaults markup  
                            initialPreviewConfig: <?= $arrInitialPreviewDataConfig;?>,
                            preferIconicPreview: true,
                            previewFileIconSettings: { // configure your icon file extensions
                                'doc': '<i class="fas fa-file-word text-primary"></i>',
                                'xls': '<i class="fas fa-file-excel text-success"></i>',
                                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                                'htm': '<i class="fas fa-file-code text-info"></i>',
                                'txt': '<i class="fas fa-file-alt text-info"></i>',
                                'mov': '<i class="fas fa-file-video text-warning"></i>',
                                'mp3': '<i class="fas fa-file-audio text-warning"></i>',
                                // note for these file types below no extension determination logic 
                                // has been configured (the keys itself will be used as extensions)
                                'jpg': '<i class="fas fa-file-image text-danger"></i>', 
                                'gif': '<i class="fas fa-file-image text-muted"></i>', 
                                'png': '<i class="fas fa-file-image text-primary"></i>'    
                            },
                            previewFileExtSettings: { // configure the logic for determining icon file extensions
                                'doc': function(ext) {
                                    return ext.match(/(doc|docx)$/i);
                                },
                                'xls': function(ext) {
                                    return ext.match(/(xls|xlsx)$/i);
                                },
                                'ppt': function(ext) {
                                    return ext.match(/(ppt|pptx)$/i);
                                },
                                'zip': function(ext) {
                                    return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
                                },
                                'htm': function(ext) {
                                    return ext.match(/(htm|html)$/i);
                                },
                                'txt': function(ext) {
                                    return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                                },
                                'mov': function(ext) {
                                    return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
                                },
                                'mp3': function(ext) {
                                    return ext.match(/(mp3|wav)$/i);
                                }
                            }
                        });
                    });
                    </script>

<!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you 
    wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="<?php echo base_url() ?>assets/bootstrap-fileinput/js/plugins/piexif.min.js?<?php echo time(); ?>" type="text/javascript"></script>
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. 
    This must be loaded before fileinput.min.js -->
<!-- <script src="<?php echo base_url() ?>assets/bootstrap-fileinput/js/plugins/sortable.min.js?<?php echo time(); ?>" type="text/javascript"></script> -->
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for 
    HTML files. This must be loaded before fileinput.min.js -->
<script src="<?php echo base_url() ?>assets/bootstrap-fileinput/js/plugins/purify.min.js?<?php echo time(); ?>" type="text/javascript"></script>
<!-- popper.min.js below is needed if you use bootstrap 4.x. You can also use the bootstrap js 
   3.3.x versions without popper.min.js. -->
<script src="<?php echo base_url() ?>assets/js/popper.min.js?<?php echo time(); ?>"></script>
<!-- the main fileinput plugin file -->
<script src="<?php echo base_url() ?>assets/bootstrap-fileinput/js/fileinput.min.js?<?php echo time(); ?>"></script>
<!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
<script src="<?php echo base_url() ?>assets/bootstrap-fileinput/themes/fa/theme.js?<?php echo time(); ?>"></script>
<!-- optionally if you need translation for your language then include  locale file as mentioned below -->
<script src="<?php echo base_url() ?>assets/bootstrap-fileinput/js/locales/LANG.js?<?php echo time(); ?>"></script>

<script src="<?php echo base_url() ?>assets/js/jquery-confirm.min.js?<?php echo time(); ?>"></script>