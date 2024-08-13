$(document).ready(function () {
    // Initialize DataTable
    let completedLoansTable = $('#completed_loans_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getCompletedLoansUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'company_name', name:'company_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'employee_name', name:'employee_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'amount', name: 'amount', orderable:false, sorting:false, className:'text-center'},
            {data: 'duration', name: 'duration', orderable:false, sorting:false, className:'text-center'},
            {data: 'ioweyou', name: 'ioweyou', orderable:false, sorting:false, className:'text-center'},
            {data: 'action', name: 'action', orderable:false, sorting:false, className:'text-end'},
        ],
    });
});
