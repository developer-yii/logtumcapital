$(document).ready(function () {
    // Initialize DataTable
    let employeesTable = $('#employees_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getEmployeesUrl+'?cid='+companyId,
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
            {data: 'authorized_credit_limit', name: 'authorized_credit_limit', orderable:false, sorting:false, className:'text-center'},
            {data: 'action', name: 'action', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    let companyEmployeesTable = $('#company_employees_table').DataTable({
        searching: false,
        pageLength: 10,
        processing: true,
        serverSide: true,
        scrollX: true,
        "bLengthChange": false,
        ajax: {
            type: 'GET',
            url: getCompanyEmployeesUrl,
        },
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        columns: [
            {data: 'DT_RowIndex', name:'id', orderable:false, sorting:false, className:'text-left'},
            {data: 'name', name: 'name', orderable:false, sorting:false, className:'text-center'},
            {data: 'email', name: 'email', orderable:false, sorting:false, className:'text-center'},
            {data: 'phone_number', name: 'phone_number', orderable:false, sorting:false, className:'text-center'},
            {data: 'authorized_credit_limit', name: 'authorized_credit_limit', orderable:false, sorting:false, className:'text-center'},
            {data: 'available_credit_limit', name: 'available_credit_limit', orderable:false, sorting:false, className:'text-center'},
            {data: 'used_credit_limit', name: 'used_credit_limit', orderable:false, sorting:false, className:'text-center'},
            {data: 'ine', name: 'ine', orderable:false, sorting:false, className:'text-center'},
            {data: 'proof_of_address', name: 'proof_of_address', orderable:false, sorting:false, className:'text-end'},
        ],
    });

    // Create new employee
    $('#add-new-btn').click(function () {
        $('#add-form').trigger("reset");
        $('.show-edit-document').addClass('d-none');
        $('.show-edit-document a').attr('href', '');
        $('#addModal .modal-title span').html('Add');
        $('#employee_id').val('');
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
                    employeesTable.draw();
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

    // Edit employee
    $('body').on('click', '.editEmployee', function () {
        $('#add-form').trigger("reset");
        $('#employee_id').val('');
        $('.error').html('');
        var employeeId = $(this).data('id');
        var url = getEmployeeDetailsUrl;
        url = url.replace('__ID__', employeeId);
        $.get(url, function (response) {
            if(response.status == true){
                $('#addModal .modal-title span').html('Edit');
                $('#employee_id').val(response.data.employeeDetails.id);
                $('#company').val(response.data.employeeDetails.company_id);
                $('#first_name').val(response.data.employeeDetails.first_name);
                $('#middle_name').val(response.data.employeeDetails.middle_name);
                $('#last_name').val(response.data.employeeDetails.last_name);
                $('#email').val(response.data.employeeDetails.email);
                $('#phone_number').val(response.data.employeeDetails.phone_number);
                $('#address').val(response.data.employeeDetails.address);
                $('#authorized_credit_limit').val(response.data.employeeDetails.authorized_credit_limit);
                let proof_of_address_doc = basePath + response.data.employeeDetails.proof_of_address;
                let ine_doc = basePath + response.data.employeeDetails.ine;
                $('#download_proof_of_address_document a').attr('href', proof_of_address_doc);
                $('#download_ine_document a').attr('href', ine_doc);
                $('.show-edit-document').removeClass('d-none');
                $('#addModal').modal('show');
            }else{
                showToastMessage('error', response.message);
            }
        })
    });


    // Delete employee
    $('body').on('click', '.deleteEmployee', function () {
        if (confirm("Are you sure you want to delete?")) {
            var postData = {
                employeeId : $(this).data('id'),
            };
            $.post(deleteEmployeeUrl, postData, function (response) {
                if(response.status == true){
                    employeesTable.draw();
                    showToastMessage('success', response.message);
                }else{
                    showToastMessage('error', response.message);
                }
            });
        }else{
            employeesTable.draw();
        }
    });

    //  open increase company limit request modal
    $('body').on('click', '#increase_company_limit', function(){
        $('#change_company_authorized_limit_form #authorized_credit_limit').val('');
        $('.error').html('');
        $('#change_company_authorized_limit').modal('show');
    });

    // submit company increase credit limit request
    $('body').on('submit', '#change_company_authorized_limit_form', function(e){
        e.preventDefault();
        $('.error').html('');
        var formData = $('#change_company_authorized_limit_form').serialize();
        $.ajax({
            type: "POST",
            url: $('#change_company_authorized_limit_form').attr('action'),
            data: formData,
            beforeSend:function(){
                $('#saveRequestBtn').prop('disabled', true);
                showLoader();
            },
            success: function (response) {
                $('#saveRequestBtn').prop('disabled', false);
                hideLoader();
                if(response.status == true){
                    $('#change_company_authorized_limit').modal('hide');
                    showToastMessage('success', response.message);
                }else if(response.status == 'error'){
                    var firstInput = "";
                    $.each(response.message, function(key, value){
                        if(firstInput == "") firstInput = key;
                        $('#change_company_authorized_limit_form #error_'+key).html(value);
                    });
                    $('#'+firstInput).focus();
                }else{
                    $('#change_company_authorized_limit').modal('hide');
                    showToastMessage('error', response.message);
                }
            }
        });
    });

    //  open change employee credit limit modal
    $('body').on('click', '#change_user_credit_limit', function(){
        $('#change_employee_authorized_limit_form #employee').val('');
        $('#change_employee_authorized_limit_form #authorized_credit_limit').val('');
        $('.error').html('');
        $('#change_employee_authorized_limit').modal('show');
    });

    // submit employee increase credit limit request
    $('body').on('submit', '#change_employee_authorized_limit_form', function(e){
        e.preventDefault();
        $('.error').html('');
        var formData = $('#change_employee_authorized_limit_form').serialize();
        $.ajax({
            type: "POST",
            url: $('#change_employee_authorized_limit_form').attr('action'),
            data: formData,
            beforeSend:function(){
                $('#saveEmpRequestBtn').prop('disabled', true);
                showLoader();
            },
            success: function (response) {
                $('#saveEmpRequestBtn').prop('disabled', false);
                hideLoader();
                if(response.status == true){
                    companyEmployeesTable.draw();
                    $('#change_employee_authorized_limit').modal('hide');
                    showToastMessage('success', response.message);
                }else if(response.status == 'error'){
                    var firstInput = "";
                    $.each(response.message, function(key, value){
                        if(firstInput == "") firstInput = key;
                        $('#change_employee_authorized_limit_form #error_'+key).html(value);
                    });
                    $('#'+firstInput).focus();
                }else{
                    companyEmployeesTable.draw();
                    $('#change_employee_authorized_limit').modal('hide');
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
