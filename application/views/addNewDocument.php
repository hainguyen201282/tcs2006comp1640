<div class="content-wrapper">
    <link href="<?php echo base_url() ?>assets/bootstrap-fileinput/css/fileinput.min.css?<?php echo time(); ?>" media="all" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/all.css?<?php echo time(); ?>" media="all" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/jquery-confirm.min.css?<?php echo time(); ?>" media="all" rel="stylesheet" type="text/css" />
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-book"></i> Document Management
        <small>Add / Edit Document</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group" style="margin-top: 34px"></div>
            </div>
        </div>
        <div class="row">
            <!-- left column -->
            <div class="col-xs-12">
              <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Upload documents</h3>
                    </div><!-- /.box-header -->

                    <!-- <p id="msg"></p>
                    <input type="file" id="multiFiles" name="documentFiles[]" multiple="multiple"/>
                    <button id="upload">Upload</button> -->

                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addDocument" action="<?php echo base_url() ?>submitNewDocument" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="topic">Topic *</label>
                                        <input id="topic" class="form-control topic" type="text"
                                               name="topic" value="<?php echo set_value('topic'); ?>"
                                               maxlength="128" required>
                                        <input type="hidden" name="fileIDs" id="fileIDs"/>
                                    </div>
                            </div>
                            <div class="col-md-12">
                                <div class="file-loading">
                                    <input id="input-res-3" name="input-res-3[]" type="file" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit"/>
                        </div>
                    </form>
                    
                    <script>
                    $(document).ready(function() {
                        var $el1 = $("#input-res-3");
                        $el1.fileinput({
                            // allowedFileExtensions: ['jpg', 'png', 'gif'],
                            uploadUrl: "<?php echo base_url(); ?>uploadDocument",
                            showBrowse: false,
                            browseOnZoneClick: true,
                            fileActionSettings: {
                                showZoom: false,
                                showDownload: true, // show download button
                                showDrag: false
                            },
                            initialPreviewShowDelete: true,
                            showCaption: false,
                            uploadAsync: true,
                            showUpload: false, // hide upload button
                            showRemove: false, // hide remove button
                            enableResumableUpload: false,
                            minFileCount: 1,
                            maxFileCount: 10,
                            theme: 'fas',
                            deleteUrl: '<?php echo base_url(); ?>deleteDocument',
                            // required: true,
                            uploadExtraData: {
                                //uploadToken: "SOME_VALID_TOKEN"
                            },
                            overwriteInitial: false,
                            initialPreview: [
                                // // TEXT DATA
                                // "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut mauris ut libero fermentum feugiat eu et dui. Mauris condimentum rhoncus enim, sed semper neque vestibulum id. Nulla semper, turpis ut consequat imperdiet, enim turpis aliquet orci, eget venenatis elit sapien non ante. Aliquam neque ipsum, rhoncus id ipsum et, volutpat tincidunt augue. Maecenas dolor libero, gravida nec est at, commodo tempor massa. Sed id feugiat massa. Pellentesque at est eu ante aliquam viverra ac sed est.",
                                // // PDF DATA
                                // 'https://kartik-v.github.io/bootstrap-fileinput-samples/samples/pdf-sample.pdf',
                                // // VIDEO DATA
                                // "https://kartik-v.github.io/bootstrap-fileinput-samples/samples/small.mp4"
                            ],
                            initialPreviewAsData: true, // defaults markup  
                            initialPreviewConfig: [
                                // {caption: "Lorem Ipsum.txt", filetype: "text/plain", size: 1430, key: 12, downloadUrl: "/site/file-delete"}, // , url: "/site/file-delete"
                                // {filetype: "application/pdf", size: 8000, caption: "PDF Sample.pdf", key: 14, downloadUrl: "/site/file-delete"}, // , url: "/site/file-delete"
                                // {size: 375000, filetype: "video/mp4", caption: "Krajee Sample.mp4", key: 15, downloadUrl: "/site/file-delete"} // , url: "/site/file-delete"
                            ],
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
                        }).on("filebatchselected", function(event, files) {
                            $el1.fileinput("upload");
                        })
                        .on('filebeforedelete', function() {
                            return new Promise(function(resolve, reject) {
                                $.confirm({
                                    title: 'Confirmation!',
                                    content: 'Are you sure you want to delete this file?',
                                    type: 'red',
                                    buttons: {   
                                        ok: {
                                            btnClass: 'btn-primary text-white',
                                            keys: ['enter'],
                                            action: function(){
                                                resolve();
                                            }
                                        },
                                        cancel: function(){
                                            // $.alert('File deletion was aborted! ');
                                        }
                                    }
                                });
                            });
                        }).on('filedeleted', function(vKey, jqXHR, extraData) {
                            // setTimeout(function() {
                            //     $.alert('File deletion was successful! ');
                            // }, 900);

                            const strIDs = $("#fileIDs").val();
                            var arrIDs = strIDs.split(",");
                            const indexOfDeletedId = arrIDs.indexOf(extraData.responseJSON.dbFileId);
                            if ( arrIDs.length > 0 && indexOfDeletedId > -1) {
                                arrIDs.splice(indexOfDeletedId, 1);
                            }

                            $("#fileIDs").val(arrIDs.toString());
                        }).on('fileuploaded', function(event, previewId, index, fileId) {
                            //console.log(previewId.response.dbFileId);
                            // console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
                            const strIDs = $("#fileIDs").val();
                            var arrIDs = strIDs.split(",");
                            console.log(arrIDs);

                            if ( arrIDs.length == 0 || arrIDs.indexOf(previewId.response.dbFileId) == -1) {
                                arrIDs[arrIDs.length] = previewId.response.dbFileId;
                            }
                            
                            if (!arrIDs[0] || arrIDs[0] == "") {
                                arrIDs.shift();
                            }

                            $("#fileIDs").val(arrIDs.toString());
                        });

                        var krajeeGetCount = function(id) {
                            var cnt = $('#' + id).fileinput('getFilesCount');
                            return cnt === 0 ? 'You have no files remaining.' :
                                'You have ' +  cnt + ' file' + (cnt > 1 ? 's' : '') + ' remaining.';
                        };
                        // .on('fileuploaded', function(event, previewId, index, fileId) {
                        //     console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
                        // }).on('fileuploaderror', function(event, previewId, index, fileId) {
                        //     console.log('File Upload Error', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
                        // }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
                        //     console.log('File Batch Uploaded', preview, config, tags, extraData);
                        // });
                     
                        // uncomment below if you need to monitor file upload chunk status or take actions based on events
                        /*
                        $("#input-res-3").on('filechunkbeforesend', function(event, fileId, index, retry, fm, rm, outData) {
                            console.log('File Chunk Before Send', 'ID: ' + fileId + ', Index: ' + index + ', Retry: ' + retry);
                        }).on('filechunksuccess', function(event, fileId, index, retry, fm, rm, outData) {
                            console.log('File Chunk Success', 'ID: ' + fileId + ', Index: ' + index + ', Retry: ' + retry);
                        }).on('filechunkerror', function(event, fileId, index, retry, fm, rm, outData) {
                            console.log('File Chunk Error', 'ID: ' + fileId + ', Index: ' + index + ', Retry: ' + retry);
                        }).on('filechunkajaxerror', function(event, fileId, index, retry, fm, rm, outData) {
                            console.log('File Chunk Ajax Error', 'ID: ' + fileId + ', Index: ' + index + ', Retry: ' + retry);
                        }).on('filechunkcomplete', function(event, fileId, index, retry, fm, rm, outData) {
                            console.log('File Chunk Complete', 'ID: ' + fileId + ', Index: ' + index + ', Retry: ' + retry);
                        });
                        */
                     
                        // uncomment below if you need file's test status (via resumableUploadOptions.testUrl) or take actions based on events
                        /*
                        $("#input-res-3").on('filetestbeforesend', function(event, fileId, fm, rm, outData) {
                            console.log('File Test Before Send', 'ID: ' + fileId);
                        }).on('filetestsuccess', function(event, fileId, fm, rm, outData) {
                            console.log('File Test Success', 'ID: ' + fileId);
                        }).on('filetesterror', function(event, fileId, fm, rm, outData) {
                            console.log('File Test Error', 'ID: ' + fileId);
                        }).on('filetestajaxerror', function(event, fileId, fm, rm, outData) {
                            console.log('File Test Ajax Error', 'ID: ' + fileId);
                        }).on('filetestcomplete', function(event, fileId, fm, rm, outData) {
                            console.log('File Test Complete', 'ID: ' + fileId);
                        });;
                        */
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
