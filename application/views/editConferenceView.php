<?php
$id = $conferenceInfo->id;
$appointmentTime = $conferenceInfo->appTime;
$location = $conferenceInfo->location;
$title = $conferenceInfo->title;
$topic = $conferenceInfo->topic;
$type = $conferenceInfo->type;
$description = $conferenceInfo->description;
?>
<style>
    fieldset {
        border: 1px groove rgba(60, 63, 65, 0.96) !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 1.5em 1.5em 0 !important;
        -webkit-box-shadow: 0 0 0 0 #636363;
        box-shadow: 0 0 0 0 #636363;
    }

    legend {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width: initial; /* Or auto */
        padding: 0 10px; /* To give a bit of padding on the left and right */
        border-bottom: none;
    }

    /* Autocomplete */
    .clear {
        clear: both;
        margin-top: 20px;
    }

    #searchResult {
        list-style: none;
        padding: 0;
        width: 250px;
        position: initial;
        margin: 0;
    }

    #searchResult li {
        background: lavender;
        padding: 4px;
        margin-bottom: 1px;
    }

    #searchResult li:nth-child(even) {
        background: #3c8dbc;
        color: white;
    }

    #searchResult li:hover {
        cursor: pointer;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-handshake-o"></i> Conference Management
            <small>Add / Edit Conference</small>
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
                        <h3 class="box-title">Enter Conference Details</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-7">
                            <!-- form start -->
                            <fieldset class="conference-information">
                                <legend>Conference Information</legend>
                                <?php $this->load->helper("form"); ?>
                                <form role="form" id="editConference"
                                      action="<?php echo base_url() ?>editConference" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="app-time">Appointment Time</label>
                                                <input id="app-time" type="text" class="form-control" name="appTime"
                                                       value="<?= $appointmentTime; ?>" disabled>
                                                <input id="id" type="hidden" value="<?= $id; ?>" name="id"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input id="title" type="text" class="form-control" name="title"
                                                       value="<?= $title; ?>" maxlength="128" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="type">Type</label>
                                                <input id="type" type="text" class="form-control" name="type"
                                                       value="<?= $type; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="location">Location</label>
                                                <input id="location" type="text" class="form-control" name="location"
                                                       value="<?= $location; ?>" maxlength="256" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="topic">Topic</label>
                                                <input id="topic" type="text" class="form-control" name="topic"
                                                       value="<?= $topic; ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="mobile">Description</label>
                                                <textarea id="description" class="form-control"
                                                          aria-label="With textarea"
                                                          name="description"
                                                          maxlength="65000"><?= $description; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-primary" value="Submit"/>
                                        <input type="reset" class="btn btn-default" value="Reset"/>
                                    </div>
                                </form>
                            </fieldset>
                        </div>

                        <div class="col-md-5">
                            <fieldset class="attend">
                                <legend>Attender</legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="#" method="post">
                                            <div class="form-group">
                                                <input id="search" class="form-control" type="text" placeholder="Search"
                                                       autocomplete="off" name="search">
                                                <div style="clear: left">
                                                    <ul id="searchResult"></ul>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="box-body table-responsive">
                                            <table id="tbl-conference" class="table display" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if (!empty($attenderRecords)) {
                                                    foreach ($attenderRecords as $record) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $record->name ?></td>
                                                            <td class="text-center">
                                                                <a class="btn btn-sm btn-danger deleteAttender" href="#"
                                                                   title="Delete"
                                                                   data-studentId="<?= $record->id; ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if ($error) {
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
            </div>
    </section>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        CKEDITOR.replace('description', {
            filebrowserBrowseUrl: '<?php echo site_url('assets/js/ckfinder/ckfinder.html');?>',
            filebrowserUploadUrl: '<?php echo site_url('assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files');?>',
        });

        $('#tbl-conference').DataTable({
            'info': true,
            'searching': false,
            'paging': true,
            'lengthChange': false,
        });

        $('#search').keyup(function () {
            const search = $(this).val();

            if (search !== "") {
                $.ajax({
                    type: 'POST',
                    url: baseURL + "searchUser",
                    data: {
                        "search": search,
                        "conferenceId": <?= $id; ?>,
                    },
                    dataType: "json"
                }).done(function (response) {
                    $("#searchResult").empty();

                    for (let i = 0; i < response.length; i++) {
                        const id = response[i]['userId'];
                        const name = response[i]['name'];
                        $('#searchResult').append('<li value="' + id + '">' + name + '</li>');
                    }

                    // binding click event to li
                    $('#searchResult li').bind("click", function () {
                        const idTag = document.getElementById('id');
                        addAttender(idTag.value, this);
                    });
                });
            }
        });

        $(this).on("click", ".deleteAttender", function () {
            const id = $(this).attr("data-studentId");

            let confirmation = confirm("Are you sure to delete this?");
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: baseURL + "deleteAttender",
                    data: {
                        attendId: id
                    },
                    dataType: "json"
                }).done(function (data) {
                    location.reload();
                });
            }
        });
    });

    // add new attender
    function addAttender(conferenceId, element) {
        console.log(conferenceId);
        let userId = $(element).val();
        let value = $(element).text();

        $("#searchResult").empty();
        $.ajax({
            type: 'POST',
            url: baseURL + 'addAttender',
            data: {
                "userId": userId,
                "id": conferenceId
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response === false) {
                    alert("Attender already exist in your list. Please try again.");
                } else {
                    const tableRef = document.getElementById('tbl-conference').getElementsByTagName('tbody')[0];
                    // insert a row in the table at the last row
                    const newRow = tableRef.insertRow();
                    // insert a cell in the row at index 0
                    const newCell = newRow.insertCell(0);
                    // append a text node to the cell
                    const newText = document.createTextNode(value);
                    newCell.appendChild(newText);
                }
            }
        });
    }
</script>
