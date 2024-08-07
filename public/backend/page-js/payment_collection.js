function getUpcomingFriday() {
    var today = new Date();
    var dayOfWeek = today.getDay(); // 0 (Sunday) to 6 (Saturday)
    var daysUntilFriday = 5 - dayOfWeek; // 5 is Friday
    if (daysUntilFriday < 0) {
        daysUntilFriday += 7; // go to the next week's Friday
    }
    var upcomingFriday = new Date(today.getFullYear(), today.getMonth(), today.getDate() + daysUntilFriday);
    return upcomingFriday;
}

$(document).ready(function () {
    $('#collection_date').datepicker({
        beforeShowDay: function(date) {
            return [date.getDay() == 5];
        },
        dateFormat: 'dd-mm-yy',
        minDate: new Date(2024, 5, 1),
        maxDate: getUpcomingFriday(),
        defaultDate: getUpcomingFriday(),
    });

    $("#collection_date").datepicker("setDate", getUpcomingFriday());

    // initialize datatable
    let paymentColllectionsTable = $('#payment_collections_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getPaymentCollectionsUrl,
            data: function (d) {
                d.selectCollection = $('#collection_date').val()
            },
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'name', name: 'name', orderable:false, sorting:false, className:'text-center'},
            {data: 'installment_date', name: 'installment_date', orderable:false, sorting:false, className:'text-center'},
            {data: 'payment', name: 'payment', orderable:false, sorting:false, className:'text-center'},
            {data: 'status', name: 'status', orderable:false, sorting:false, className:'text-end'},
        ],
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Check the number of rows in the table
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes().length;

            // Get the button element
            var $button = $('#upload_bank_receipt_btn'); // Replace with your button's ID or selector

            // Disable or enable the button based on the number of rows
            if (rows > 0) {
                $button.removeClass('d-none'); // Enable the button
            } else {
                $button.addClass('d-none'); // Disable the button
            }
        },
    });

    $('body').on('change', '#collection_date', function () {
        paymentColllectionsTable.draw();
    });

    $('#upload_bank_receipt_btn').click(function () {
        $('#add-form').trigger("reset");
        $('#addModal .modal-title span').html('Add');
        $('#addModal #installment_date').val($('#collection_date').val());
        $('#id').val('');
        $('.error').html('');
        $('#addModal').modal('show');
    });

    // Save bank receipt
    $('#add-form').submit(function (e) {
        e.preventDefault();
        $('.error').html('');
        var formData = new FormData($('#add-form')[0]);
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            beforeSend:function(){
                $('#addorUpdateBtn').prop('disabled', true);
                showLoader();
            },
            success: function (response) {
                $('#addorUpdateBtn').prop('disabled', false);
                hideLoader();
                if(response.status == true){
                    $('#add-form').trigger("reset");
                    $('#addModal').modal('hide');
                    showToastMessage('success', response.message);
                    paymentColllectionsTable.draw();
                }else if(response.status == 'error'){
                    var firstInput = "";
                    $.each(response.message, function(key, value){
                        if(firstInput == "") firstInput = key;
                        $('#error_'+key).html(value);
                    });
                    $('#'+firstInput).focus();
                }else{
                    showToastMessage('error', response.message);
                }
            },
        });
    });
});
