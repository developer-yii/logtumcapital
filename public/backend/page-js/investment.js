$(document).ready(function () {
    // Initialize DataTable
    let investmentsTable = $('#investments_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getInvestmentsUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'name', name: 'name', orderable:false, sorting:false, className:'text-center'},
            {data: 'contributions', name: 'contributions', orderable:false, sorting:false, className:'text-center'},
            {data: 'interest_rate', name: 'interest_rate', orderable:false, sorting:false, className:'text-center'},
            {data: 'interest_earnings', name: 'interest_earnings', orderable:false, sorting:false, className:'text-center'},
            {data: 'total_amount', name: 'total_amount', orderable:false, sorting:false, className:'text-center'},
            {data: 'action', name: 'action', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    // Create new investment
    $('#add-new-btn').click(function () {
        $('#add-form').trigger("reset");
        $('#addModal .modal-title span').html('Add');
        $('#id').val('');
        $('.error').html('');
    });

    // Save or update investment
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
                    investmentsTable.draw();
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

    // Edit investment
    $('body').on('click', '.editInvestment', function () {
        $('#add-form').trigger("reset");
        $('#id').val('');
        $('.error').html('');
        var investmentId = $(this).data('id');
        getInvestmentDetailsUrl.replace('__ID__', investmentId);
        $.get(getInvestmentDetailsUrl, function (response) {
            if(response.status == true){
                $('#addModal .modal-title span').html('Edit');
                $('#investment_id').val(response.data.investmentDetails.id);
                $('#name').val(response.data.investmentDetails.name);
                $('#contributions').val(response.data.investmentDetails.contributions);
                $('#interest_rate').val(response.data.investmentDetails.interest_rate);
                $('#interest_earnings').val(response.data.investmentDetails.interest_earnings);
                $('#total_amount').val(response.data.investmentDetails.total_amount);
                $('#addModal').modal('show');
            }else{
                showToastMessage('error', response.message);
            }
        })
    });


    // Delete investment
    $('body').on('click', '.deleteInvestment', function () {
        if (confirm("Are you sure you want to delete?")) {
            var postData = {
                investmentId : $(this).data('id'),
            };
            $.post(deleteInvestmentUrl, postData, function (response) {
                if(response.status == true){
                    investmentsTable.draw();
                    showToastMessage('success', response.message);
                }else{
                    showToastMessage('error', response.message);
                }
            });
        }else{
            investmentsTable.draw();
        }
    });
});
