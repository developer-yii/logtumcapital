$(document).ready(function () {
    // Initialize DataTable
    let companiesTable = $('#companies_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getCompaniesUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'name', name: 'name', orderable:false, sorting:false, className:'text-center'},
            {data: 'phone_number', name: 'phone_number', orderable:false, sorting:false, className:'text-center'},
            {data: 'authorized_credit_limit', name: 'authorized_credit_limit', orderable:false, sorting:false, className:'text-center'},
            {data: 'status', name: 'status', orderable:false, sorting:false, className:'text-center'},
            {data: 'action', name: 'action', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    // Create new company
    $('#add-new-btn').click(function () {
        $('#add-form').trigger("reset");
        $('.show-edit-document').addClass('d-none');
        $('.show-edit-document a').attr('href', '');
        $('#addModal .modal-title span').html('Add');
        $('#company_id').val('');
        $('.error').html('');
    });

    // Save or update company
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
                    companiesTable.draw();
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
    $('body').on('click', '.editCompany', function () {
        $('#add-form').trigger("reset");
        $('#company_id').val('');
        $('.error').html('');
        var companyId = $(this).data('id');
        var url = getCompanyDetailsUrl;
        url = url.replace('__ID__', companyId);
        $.get(url, function (response) {
            if(response.status == true){
                $('#addModal .modal-title span').html('Edit');
                $('#company_id').val(response.data.companyDetails.id);
                $('#name').val(response.data.companyDetails.name);
                $('#phone_number').val(response.data.companyDetails.phone_number);
                $('#address').val(response.data.companyDetails.address);
                $('#authorized_credit_limit').val(response.data.companyDetails.authorized_credit_limit);
                let consetutive_act_doc = basePath + response.data.companyDetails.consetutive_act;
                let ine_doc = basePath + response.data.companyDetails.ine;
                let ioweyou_doc = basePath + response.data.companyDetails.ioweyou;
                $('.show-edit-document #download_constitutive_act_document').attr('href', basePath + response.data.companyDetails.consetutive_act);
                $('#download_ine_document').attr('href', basePath + response.data.companyDetails.ine);
                $('#download_ioweyou_document').attr('href', basePath + response.data.companyDetails.ioweyou);
                $('.show-edit-document').removeClass('d-none');
                // console.log(basePath + response.data.companyDetails.consetutive_act);
                // console.log(basePath + response.data.companyDetails.ine);
                // console.log(basePath + response.data.companyDetails.ioweyou);
                $('#addModal').modal('show');
            }else{
                showToastMessage('error', response.message);
            }
        })
    });


    // Delete company
    $('body').on('click', '.deleteCompany', function () {
        if (confirm("Are you sure you want to delete?")) {
            var postData = {
                companyId : $(this).data('id'),
            };
            $.post(deleteCompanyUrl, postData, function (response) {
                if(response.status == true){
                    companiesTable.draw();
                    showToastMessage('success', response.message);
                }else{
                    showToastMessage('error', response.message);
                }
            });
        }else{
            companiesTable.draw();
        }
    });

    // change status of company
    $('body').on('change', '.change-status', function(){
        if (confirm("Are you sure you want to change status of company?")) {
            var postData = {
                companyId : $(this).data('id'),
                status: $(this).val(),
            };
            $.post(changeCompanyStatusUrl, postData, function (response) {
                if(response.status == true){
                    companiesTable.draw();
                    showToastMessage('success', response.message);
                }else{
                    showToastMessage('error', response.message);
                }
            });
        }else{
            companiesTable.draw();
        }
    });
});
