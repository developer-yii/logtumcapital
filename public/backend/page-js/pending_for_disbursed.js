$(document).ready(function () {
    // Initialize DataTable
    let pendingDisbursedLoansTable = $('#disbursed_pending_loans').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        order: [6, 'desc'],
        ajax: {
            type: 'GET',
            url: getDisbursedPendingLoansUrl,
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
            {data: 'status', name: 'status', orderable:false, sorting:false, className:'text-center'},
            {data: 'first_installment_date', name: 'first_installment_date', className:'text-center'},
            {data: 'action', name: 'action', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    // change loan status
    // $('body').on('change', '.change-loan-status', function(){
        // var changedStatus = $(this).val();
        // if (confirm("Are you sure you want to change status?")) {
        //     var postData = {
        //         requestId : $(this).data('id'),
        //         status : changedStatus
        //     };
        //     $.ajax({
        //         url: changeLoanStatusUrl,
        //         type: 'POST',
        //         data: postData,
        //         beforeSend: function() {
        //             $('.change-loan-status').prop('disabled', true);
        //             showLoader();
        //         },
        //         success:function(response){
        //             $('.change-loan-status').prop('disabled', false);
        //             hideLoader();
        //             if(response.status == true){
        //                 pendingDisbursedLoansTable.draw();
        //                 showToastMessage('success', response.message);
        //             }else{
        //                 showToastMessage('error', response.message);
        //             }
        //         }
        //     });
        // }else{
        //     pendingDisbursedLoansTable.draw();
        // }
    // });

    // get more details of loan
    $('body').on('click', '.view-loan-details', function(){
        var requestId = $(this).data('id');
        $.ajax({
            url: viewLoanDetailsUrl+'?id='+requestId,
            type: 'GET',
            beforeSend: function() {
                $('.view-loan-details').prop('disabled', true);
                showLoader();
            },
            success:function(response){
                $('.view-loan-details').prop('disabled', false);
                hideLoader();
                if(response.status == true){
                    if(response.loanDetails){
                        $('#first_installment_date').val(response.loanDetails.first_installment_date);
                        $('#last_installment_date').val(response.loanDetails.last_installment_date);
                        $('#yearly_interest_rate').val(response.loanDetails.yearly_interest_rate);
                        $('#loan_interest_rate').val(response.loanDetails.loan_interest_rate);
                        $('#weekly_interest_rate').val(response.loanDetails.weekly_interest_rate);
                    }
                    $('#view_loan_details_modal').modal('show');
                }else{
                    showToastMessage('error', response.message);
                }
            }
        });
    });
});
