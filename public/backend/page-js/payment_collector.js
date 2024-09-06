$(document).ready(function () {
    // Initialize DataTable
    let paymentCollectorsTable = $('#payment_collectors_table').DataTable({
        language: language_check(),
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getPaymentCollectorsUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'name', name: 'name', orderable:false, sorting:false, className:'text-center'},
            {data: 'email', name: 'email', orderable:false, sorting:false, className:'text-center'},
            {data: 'phone_number', name: 'phone_number', orderable:false, sorting:false, className:'text-center'},
            {data: 'action', name: 'action', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    // add new payment collector
    $('#add-new-btn').click(function () {
        $('#add-form').trigger("reset");
        $('#addModal .modal-title span').html('Add');
        $('#id').val('');
        $('.error').html('');
    });

    // Save or update employee
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

    // Edit payment collector
    $('body').on('click', '.editPaymentCollector', function () {
        $('#add-form').trigger("reset");
        $('#id').val('');
        $('.error').html('');
        var paymentcollectorId = $(this).data('id');
        var url = getPaymentCollectorDetailsUrl;
        url = url.replace('__ID__', paymentcollectorId);
        $.get(url, function (response) {
            if(response.status == true){
                $('#addModal .modal-title span').html('Edit');
                $('#id').val(response.data.paymentCollectorDetails.id);
                $('#first_name').val(response.data.paymentCollectorDetails.first_name);
                $('#middle_name').val(response.data.paymentCollectorDetails.middle_name);
                $('#last_name').val(response.data.paymentCollectorDetails.last_name);
                $('#email').val(response.data.paymentCollectorDetails.email);
                $('#phone_number').val(response.data.paymentCollectorDetails.phone_number);
                $('#address').val(response.data.paymentCollectorDetails.address);
                $('#addModal').modal('show');
            }else{
                showToastMessage('error', response.message);
            }
        })
    });


    // Delete payment collector
    $('body').on('click', '.deletePaymentCollector', function () {
        if (confirm(deleteConfirmMsg)) {
            var postData = {
                paymentCollectorId : $(this).data('id'),
            };
            $.post(deletePaymentCollector, postData, function (response) {
                if(response.status == true){
                    paymentCollectorsTable.draw();
                    showToastMessage('success', response.message);
                }else{
                    showToastMessage('error', response.message);
                }
            });
        }else{
            paymentCollectorsTable.draw();
        }
    });


    // // show enlarge image on click
    // $('body').on('click', '.enlarge-image', function() {
    //     showLoader();
    //     var imgSrc = $(this).attr('src');
    //     $('#enlarged_image').attr('src', imgSrc);
    //     $('#enlarged_image_modal').modal('show');
    // });
    // $('#enlarged_image_modal').on('shown.bs.modal', function() {
    //     hideLoader();
    // });
});
