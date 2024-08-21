$(document).ready(function () {
    // Initialize DataTable
    let collectedInstallmentTable = $('#collected_installments_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getCollectedInstallmentsUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'company_name', name:'company_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'collector_name', name:'collector_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'amount', name: 'amount', orderable:false, sorting:false, className:'text-center'},
            {data: 'bank_receipt', name: 'bank_receipt', orderable:false, sorting:false, className:'text-center'},
            {data: 'note', name: 'note', orderable:false, sorting:false, className:'text-center'},
            {data: 'created_at', name: 'created_at', orderable:false, sorting:false, className:'text-end'},
        ],
    });
});
