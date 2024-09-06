$(document).ready(function () {
    // Initialize DataTable
    let interestRateTable = $('#interest_rate_table').DataTable({
        language: language_check(),
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getInterestRatesUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'company_name', name: 'company_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'interest_rate', name: 'interest_rate', orderable:false, sorting:false, className:'text-center'},
            {data: 'action', name: 'action', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    // Save or update company
    $('#add-form').submit(function (e) {
        e.preventDefault();
        $('.error').html('');
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
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
                    interestRateTable.draw();
                }else if(response.status == 'error'){
                    var firstInput = "";
                    $.each(response.message, function(key, value){
                        if(firstInput == "") firstInput = key;
                        $('#add-form #error_'+key).html(value);
                    });
                    $('#'+firstInput).focus();
                }else{
                    showToastMessage('error', response.message);
                }
            },
        });
    });

    // Edit interest rate
    $('body').on('click', '.editInterestRate', function () {
        $('#add-form').trigger("reset");
        $('.error').html('');
        var interestRateId = $(this).data('id');
        var url = getInterestRateDetailsUrl;
        url = url.replace('__ID__', interestRateId);
        $.get(url, function (response) {
            if(response.status == true){
                $('#addModal .modal-title span').html('Edit');
                $('#interest_rate_id').val(response.interestRateData.id);
                $('#name').val(response.interestRateData.company_name);
                $('#interest_rate').val(response.interestRateData.interest_rate);
                $('#addModal').modal('show');
            }else{
                showToastMessage('error', response.message);
            }
        })
    });
});
