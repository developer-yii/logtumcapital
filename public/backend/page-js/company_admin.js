$(document).ready(function () {
    // Initialize DataTable
    let companyAdminsTable = $('#company_admins_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getCompanyAdminUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'company_name', name: 'company_name', orderable:false, sorting:false, className:'text-center'},
            {data: 'name', name: 'name', orderable:false, sorting:false, className:'text-center'},
            {data: 'email', name: 'email', orderable:false, sorting:false, className:'text-center'},
            {data: 'phone_number', name: 'phone_number', orderable:false, sorting:false, className:'text-center'},
            {data: 'action', name: 'action', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    // Create new company admin
    $('#add-new-btn').click(function () {
        $('#add-form').trigger("reset");
        $('#addModal .modal-title span').html('Add');
        $('#company_admin_id').val('');
        $('.error').html('');
    });

    // Save or update company admin
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
                    companyAdminsTable.draw();
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

    // Edit company
    $('body').on('click', '.editCompanyAdmin', function () {
        $('#add-form').trigger("reset");
        $('#company_admin_id').val('');
        $('.error').html('');
        var companyId = $(this).data('id');
        var url = getCompanyAdminDetailsUrl;
        url = url.replace('__ID__', companyId);
        $.get(url, function (response) {
            if(response.status == true){
                $('#addModal .modal-title span').html('Edit');
                $('#company_admin_id').val(response.data.companyAdminDetails.id);
                $('#company').val(response.data.companyAdminDetails.company_id);
                $('#first_name').val(response.data.companyAdminDetails.first_name);
                $('#middle_name').val(response.data.companyAdminDetails.middle_name);
                $('#last_name').val(response.data.companyAdminDetails.last_name);
                $('#email').val(response.data.companyAdminDetails.email);
                $('#phone_number').val(response.data.companyAdminDetails.phone_number);
                $('#address').val(response.data.companyAdminDetails.address);
                $('#addModal').modal('show');
            }else{
                showToastMessage('error', response.message);
            }
        })
    });


    // Delete company
    $('body').on('click', '.deleteCompanyAdmin', function () {
        if (confirm("All details related to this will be permanently deleted. Are you sure you want to proceed with the deletion?")) {
            var postData = {
                companyAdminId : $(this).data('id'),
            };
            $.post(deleteCompanyAdminUrl, postData, function (response) {
                if(response.status == true){
                    companyAdminsTable.draw();
                    showToastMessage('success', response.message);
                }else{
                    showToastMessage('error', response.message);
                }
            });
        }else{
            companyAdminsTable.draw();
        }
    });
});
