@extends('layouts.login')
@section('title', __('translation.Register'))
@section('content')
<div class="row justify-content-center">
    <div class="col-xxl-4 col-lg-5">
        <div class="card">

            <div class="card-header py-3 text-center bg-primary">
                <a href="{{ route('register') }}">
                    <span><img src="{{ asset('frontend/images/LOGO.png') }}" alt="logo" height="95"></span>
                </a>
            </div>

            <div class="card-body p-4">

                <div class="text-center w-75 m-auto">
                    <h4 class="text-dark-50 text-center pb-2 fw-bold">{{ __("translation.Register Your Company") }}</h4>
                </div>

                <form method="POST" id="register_form" action="{{ route('store-company-details') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">{{ __("translation.Name") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="" autofocus>
                        <span id="error_name" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone_number" class="form-label">{{ __("translation.Phone number") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="">
                        <span class="text-secondary">{{ __("translation.For example") }} : +521234567890</span><br>
                        <span id="error_phone_number" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="address" class="form-label">{{ __("translation.Address") }}<span class="text-danger"> *</span></label>
                        <textarea cols="5" rows="5" name="address" id="address" class="form-control"></textarea>
                        <span id="error_address" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="consetitutive_act_document" class="form-label">
                            {{ __("translation.Constitutive act document") }}
                            <span class="text-danger"> *</span>
                        </label>
                        <br>
                        <div class="border">
                            <input type="file" id="consetitutive_act_document" name="consetitutive_act_document" class="form-control" style="display:none;" onchange="updateCustomLabel('consetitutive_act_document', 'label_consetitutive_act_document')">
                            <label for="consetitutive_act_document" id="label_consetitutive_act_document" class="btn btn-secondary">Elejir Archivo</label>
                            <span id="error_consetitutive_act_document" class="error text-danger"></span>
                            <small id="file-name-consetitutive_act_document" class="text-muted">ningún archivo seleccionado</small>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="ine_document" class="form-label">
                            {{ __("translation.INE document") }}
                            <span class="text-danger"> *</span>
                        </label>
                        <br>
                        <div class="border">
                            <input type="file" id="ine_document" name="ine_document" class="form-control" style="display:none;" onchange="updateCustomLabel('ine_document', 'label_ine_document')">
                            <label for="ine_document" id="label_ine_document" class="btn btn-secondary">Elejir Archivo</label>
                            <span id="error_ine_document" class="error text-danger"></span>
                            <small id="file-name-ine_document" class="text-muted">ningún archivo seleccionado</small>
                        </div>
                    </div>

                    <input type="hidden" id="status" name="status" value="1">
                    <div class="d-block text-center">
                        <button type="submit" class="btn btn-success" id="addorUpdateBtn">{{ __("translation.Save") }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="text-muted">{{ __("translation.Back to") }} <a href="{{ url('/') }}" class="text-muted"><b>{{ __("translation.Home") }}</b></a></p>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        // Save or update company
        $('#register_form').submit(function (e) {
            e.preventDefault();
            $('.error').html('');
            var formData = new FormData($('#register_form')[0]);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend:function(){
                    $('#addorUpdateBtn').prop('disabled', true);
                },
                success: function (response) {
                    $('#addorUpdateBtn').prop('disabled', false);
                    if(response.status == true){
                        $('#register_form').trigger("reset");
                        showToastMessage('success', response.message);
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
        function updateCustomLabel(inputId, labelId) {
            const input = document.getElementById(inputId);
            const label = document.getElementById(labelId);
            const fileNameDisplay = document.getElementById(`file-name-${inputId}`);

            if (input.files.length > 0) {
                label.textContent = "Elejir Archivo";
                fileNameDisplay.textContent = input.files[0].name;
            } else {
                label.textContent = "Elejir Archivo";
                fileNameDisplay.textContent = "ningún archivo seleccionado";
            }
        }

    </script>
@endpush
