$(document).ready(function () {

    // listen drop-down list
    $('#tutor-reallocate').change(function () {
        $("#tbl-student-reallocate").DataTable().destroy();

        // selected value
        let tutorId = $(this).val();

        // call api get all students by tutor id
        $.ajax({
            type: 'POST',
            url: baseURL + "getAllStudentByTutorId",
            dataType: "json",
            data: {
                "tutorId": tutorId
            }
        }).done(function (data) {
            renderTableReallocate(data.result);
        });
    });

    const rows_selected = [];

    function renderTableReallocate(data) {
        // setTimeout(function () {
        // }, 1000);

        // refresh datatable
        const table = $("#tbl-student-reallocate").DataTable({
            'info': false,
            'searching': false,
            'paging': true,
            'lengthChange': false,
            'data': data,
            'order': [],
            'columns': [
                {
                    "targets": 'no-sort',
                    "orderable": false,
                    data: 'studentId',
                    render: function (data) {
                        return '<input type="checkbox" value="' + data + '">';
                    }
                },
                {
                    data: 'name',
                    render: function (data) {
                        return data;
                    }
                },
                {
                    data: 'email',
                    render: function (data) {
                        return data;
                    }
                }
            ],
        });

        // Handle click on checkbox
        $('#tbl-student-reallocate tbody').on('click', 'input[type="checkbox"]', function (e) {
            const $row = $(this).closest('tr');

            // Get row data
            const data = table.row($row).data();

            // Get row ID
            const rowId = table.row($row)[0];

            // Determine whether row ID is in the list of selected row IDs
            const index = $.inArray(rowId, rows_selected);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
                rows_selected.push(data.studentId);
                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1) {
                rows_selected.splice(rowId, 1);
            }

            if (this.checked) {
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }

            //console.log(rows_selected);
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on table cells with checkboxes
        $('#tbl-student-reallocate').on('click', 'tbody td, thead th:first-child', function (e) {
            $(this).parent().find('input[type="checkbox"]').trigger('click');
        });

        // Handle click on "Select all" control
        $('thead input[name="select_all"]', table.table().container()).on('click', function (e) {
            if (this.checked) {
                $('#tbl-student-reallocate tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
                $('#tbl-student-reallocate tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        table.on('draw', function () {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
        });

        // Handle submission event
        $('#btn-unassign').on('click', function () {
            let hitURL = baseURL + "unassignTutor";

            $.ajax({
                type: 'POST',
                url: hitURL,
                dataType: "json",
                data: {
                    "studentIds": rows_selected,
                }
            }).done(function (data) {
                if (data.status === true) {
                    alert("Students successfully reallocated");
                } else if (data.status === false) {
                    alert("Students reallocation failed");
                }
                location.reload();
            });
            return false;
        });
    }
});
