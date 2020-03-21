$(document).ready(function () {
    const rows_selected = [];

    const table = $('#tbl-student-allocate').DataTable({
        'info': false,
        'searching': false,
        'paging': true,
        'lengthChange': false,
        'columnDefs': [{
            'targets': 0,
            'searchable': false,
            'orderable': false,
            'width': '1%',
            'className': 'dt-body-center',
            'render': function (data, type, full, meta) {
                return '<input type="checkbox">';
            }
        }],
        'order': [[1, 'asc']],
        'rowCallback': function (row, data, dataIndex) {
            // Get row ID
            const rowId = data[0];

            // If row ID is in the list of selected row IDs
            if ($.inArray(rowId, rows_selected) !== -1) {
                $(row).find('input[type="checkbox"]').prop('checked', true);
                $(row).addClass('selected');
            }
        }
    });

    // Handle click on checkbox
    $('#tbl-student-allocate tbody').on('click', 'input[type="checkbox"]', function (e) {
        const $row = $(this).closest('tr');

        // Get row data
        const data = table.row($row).data();

        // Get row ID
        const rowId = data[0];

        // Determine whether row ID is in the list of selected row IDs
        const index = $.inArray(rowId, rows_selected);

        // If checkbox is checked and row ID is not in list of selected row IDs
        if (this.checked && index === -1) {
            rows_selected.push(rowId);

            // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
        } else if (!this.checked && index !== -1) {
            rows_selected.splice(index, 1);
        }

        if (this.checked) {
            $row.addClass('selected');
        } else {
            $row.removeClass('selected');
        }

        // Update state of "Select all" control
        updateDataTableSelectAllCtrl(table);

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    // Handle click on table cells with checkboxes
    $('#tbl-student-allocate').on('click', 'tbody td, thead th:first-child', function (e) {
        $(this).parent().find('input[type="checkbox"]').trigger('click');
    });

    // Handle click on "Select all" control
    $('thead input[name="select_all"]', table.table().container()).on('click', function (e) {
        if (this.checked) {
            $('#tbl-student-allocate tbody input[type="checkbox"]:not(:checked)').trigger('click');
        } else {
            $('#tbl-student-allocate tbody input[type="checkbox"]:checked').trigger('click');
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
    $('#btn-assign').on('click', function () {
        let hitURL = baseURL + "assignTutor";

        const tutorId = $('#tutor').val();
        if (tutorId <= 0) {
            alert("Please choose Tutor!");
            return;
        }

        $.ajax({
            type: 'POST',
            url: hitURL,
            dataType: "json",
            data: {
                "studentIds": rows_selected,
                "tutorId": tutorId
            }
        }).done(function (data) {
            if (data.status === true) {
                alert("Students successfully allocated");
            } else if (data.status === false) {
                alert("Students allocation failed");
            }
            location.reload();
        });
        return false;
    });
});
