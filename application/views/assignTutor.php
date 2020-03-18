<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap"></i> Assign Tutor Management
            <small>Allocate, Reallocate</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group" style="margin-top: 34px"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <div class="nav-tabs-custom">
                            <ul id="student-tab" class="nav nav-tabs">
                                <li class="active"><a href="#allocate" data-toggle="tab">Allocate</a></li>
                                <li><a href="#reallocate" data-toggle="tab">Reallocate</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="allocate" class="tab-pane fade in active">
                                    <?php $this->load->helper('form'); ?>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="tutor">Tutor</label>
                                                <select class="form-control required" id="tutor" name="tutor">
                                                    <option value="0">Select Tutor</option>
                                                    <?php
                                                    if (!empty($tutors)) {
                                                        foreach ($tutors as $item) {
                                                            ?>
                                                            <option value="<?php echo $item->userId ?>"
                                                                <?php
                                                                if ($item->userId == set_value('name')) {
                                                                    echo "selected=selected";
                                                                }
                                                                ?>>
                                                                <?php echo $item->name ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box-body table-responsive">
                                                    <table id="tbl-student-allocate" class="table display"
                                                           style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th>
                                                                <label>
                                                                    <input name="select_all" value="1"
                                                                           type="checkbox"/>
                                                                </label>
                                                            </th>
                                                            <th>Student Name</th>
                                                            <th>Email</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if (!empty($studentRecords)) {
                                                            foreach ($studentRecords as $record) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $record->studentId ?></td>
                                                                    <td><?php echo $record->name ?></td>
                                                                    <td><?php echo $record->email ?></td>
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
                                        <div class="box-footer">
                                            <input id="btn-assign" class="btn btn-primary" type="submit"
                                                   value="Submit"/>
                                            <input class="btn btn-default" type="reset" value="Reset"/>
                                        </div>
                                    </div>
                                </div>
                                <div id="reallocate" class="tab-pane fade">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="tutor">Tutor</label>
                                                <select class="form-control required" id="tutor-reallocate"
                                                        name="tutor">
                                                    <option value="0">Select Tutor</option>
                                                    <?php
                                                    if (!empty($tutors)) {
                                                        foreach ($tutors as $item) {
                                                            ?>
                                                            <option value="<?php echo $item->userId ?>"
                                                                <?php
                                                                if ($item->userId == set_value('name')) {
                                                                    echo "selected=selected";
                                                                }
                                                                ?>>
                                                                <?php echo $item->name ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="box-body table-responsive">
                                                <table id="tbl-student-reallocate" class="table display"
                                                       style="width:100%">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <input type="button" class="btn btn-primary" value="Submit"/>
                                        <input type="reset" class="btn btn-default" value="Reset"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/allocateStudent.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#tutor-reallocate').change(function () {
            // selected value
            const tutorId = $(this).val();

            const result = [];
            $.ajax({
                type: 'POST',
                url: baseURL + "getAllStudentByTutorId",
                dataType: "json",
                data: {
                    "tutorId": tutorId
                }
            }).done(function (data) {
                result.push(data.result);
            });

            setTimeout(function () {
                console.log(result[0]);

                // refresh datatable
                // const datatable = $("#tbl-student-reallocate").DataTable({
                //     'info': false,
                //     'searching': false,
                //     'paging': true,
                //     'lengthChange': false,
                //     'data' : result[0],
                //     "columns": [
                //         {
                //             title: "Email", "render": function (data) {
                //                 console.log(data);
                //                 return '<span>sdadasd</span>';
                //             }
                //         },
                //     ]
                // });
                // datatable.clear().draw();
                // datatable.rows.add(result[0]); // add new data
                // datatable.columns.adjust().draw();
            }, 1000);
        });
    });
</script>
