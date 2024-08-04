$(document).ready(function () {
    // Initialize DataTable
    let fundRequestTable = $('#requested_fund_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        order: [7],
        ajax: {
            type: 'GET',
            url: getFundRequestsUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'company_name', name:'company_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'employee_name', name:'employee_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'bank_name', name:'bank_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'account_number', name:'account_number', orderable:false, sorting:false, className:'text-center'},
            {data: 'amount', name: 'amount', orderable:false, sorting:false, className:'text-center'},
            {data: 'duration', name: 'duration', orderable:false, sorting:false, className:'text-center'},
            {data: 'ioweyou', name: 'ioweyou', orderable:false, sorting:false, className:'text-center'},
            {data: 'status', name: 'status', orderable:false, sorting:false, className:'text-center'},
            {data: 'created_at', name: 'created_at', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    // change fund request status
    $('body').on('change', '.change-fund-request-status', function(){
        var changedStatus = $(this).val();
        if (confirm("Are you sure you want to change status?")) {
            if(changedStatus == 2){
                $('#upload_ioweyou_form').trigger("reset");
                $('#upload_ioweyou_modal .error').html('');
                $('#upload_ioweyou_modal #fund_request_id').val($(this).data('id'));
                $('#upload_ioweyou_modal #status').val($(this).val());
                $('#upload_ioweyou_modal').modal('show');
            }else{
                var postData = {
                    requestId : $(this).data('id'),
                    status : changedStatus
                };
                $.ajax({
                    url: rejectRequestStatusUrl,
                    type: 'POST',
                    data: postData,
                    beforeSend: function() {
                        $('.loan-request-status').prop('disabled', true);
                        showLoader();
                    },
                    success:function(response){
                        $('.loan-request-status').prop('disabled', false);
                        hideLoader();
                        if(response.status == true){
                            fundRequestTable.draw();
                            showToastMessage('success', response.message);
                        }else{
                            showToastMessage('error', response.message);
                        }
                    }
                });
            }
        }else{
            fundRequestTable.draw();
        }
    });

    // open upload iowe you modal
    // $('body').on('click', '.upload-ioweyou', function(){
    //     $('#upload_ioweyou_form').trigger("reset");
    //     $('#upload_ioweyou_modal .error').html('');
    //     $('#upload_ioweyou_modal #fund_request_id').val($(this).data('id'));
    //     $('#upload_ioweyou_modal').modal('show');
    // });

    $('body').on('submit', '#upload_ioweyou_form', function(e){
        e.preventDefault();
        $('#upload_ioweyou_modal .error').html('');
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#save_btn').prop('disabled', true);
                showLoader();
            },
            success:function(response){
                $('#save_btn').prop('disabled', false);
                hideLoader();
                if(response.status == true){
                    $('#upload_ioweyou_modal').modal('hide');
                    fundRequestTable.draw();
                    showToastMessage('success', response.message);
                }else if(response.status == 'error'){
                    var firstInput = "";
                    $.each(response.message, function(key, value){
                        if(firstInput == "") firstInput = key;
                        $('#error_'+key).html(value);
                    });
                    $('#'+firstInput).focus();
                }else{
                    $('#upload_ioweyou_modal').modal('hide');
                    showToastMessage('error', response.message);
                }
            }
        });
    });

    // show enlarge image on click
    $('body').on('click', '.enlarge-image', function() {
        showLoader();
        var imgSrc = $(this).attr('src');
        $('#enlarged_image').attr('src', imgSrc);
        $('#enlarged_image_modal').modal('show');
    });
    $('#enlarged_image_modal').on('shown.bs.modal', function() {
        hideLoader();
    });
});
