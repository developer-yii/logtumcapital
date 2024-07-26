$(document).ready(function () {
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
                d.selectCollection = $('#collected_date').val()
            },
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'name', name: 'name', orderable:false, sorting:false, className:'text-center'},
            {data: 'installment_date', name: 'installment_date', orderable:false, sorting:false, className:'text-center'},
            {data: 'payment', name: 'payment', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    $('body').on('change', '#collected_date', function () {
        console.log($(this).val());
        paymentColllectionsTable.draw();
    });

    $('#upload_bank_receipt_btn').click(function () {
        $('#add-form').trigger("reset");
        $('#addModal .modal-title span').html('Add');
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
                    paymentCollectorsTable.draw();
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
