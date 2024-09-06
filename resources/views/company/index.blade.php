@php
  $labelMain = __("translation.Company Details");
  $label = __("translation.Company");
@endphp
@extends('layouts.main')
@section('title', $labelMain)
@push('css')
    <link href="{{asset('/')}}backend/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="row mt-3 mb-3">
    <div class="col-md-6">
        <h4 class="page-title">{{ $labelMain }}</h4>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addModal" id="add-new-btn"><i class="uil-plus"></i> {{ __("translation.Add") }} {{ $label }}</button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="companies_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("translation.Name") }}</th>
                                    <th>{{ __("translation.Phone Number") }}</th>
                                    <th>{{ __("translation.Authorized Credit Limit") }}</th>
                                    <th>{{ __("translation.IOweYou Expiry Date") }}</th>
                                    <th>{{ __("translation.Status") }}</th>
                                    <th>{{ __("translation.Actions") }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div
</div>

{{-- add company modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('company.store') }}" method="POST" id="add-form" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>{{ __("translation.Add") }}</span> {{$label}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <input type="hidden" name="company_id" id="company_id">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">{{ __("translation.Name") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="">
                        <span id="error_name" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone_number" class="form-label">{{ __("translation.Phone number") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="">
                        <span class="text-secondary">{{ __("translation.For example :") }} +521234567890</span><br>
                        <span id="error_phone_number" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="address" class="form-label">{{ __("translation.Address") }}<span class="text-danger"> *</span></label>
                        <textarea cols="5" rows="5" name="address" id="address" class="form-control"></textarea>
                        <span id="error_address" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="authorized_credit_limit" class="form-label">{{ __("translation.Authorized credit limit") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="authorized_credit_limit" name="authorized_credit_limit" class="form-control" value="">
                        <span id="error_authorized_credit_limit" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="consetitutive_act_document" class="form-label">{{ __("translation.Constitutive act document") }}<span class="text-danger"> *</span></label>
                        <input type="file" id="consetitutive_act_document" name="consetitutive_act_document" class="form-control" value="">
                        <div class="show-edit-document d-none" id="download_consetitutive_act_document"><a href="" download>{{ __("translation.Current Consetitutive Act Document") }}</a></div>
                        <span id="error_consetitutive_act_document" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ine_document" class="form-label">{{ __("translation.INE document") }}<span class="text-danger"> *</span></label>
                        <input type="file" id="ine_document" name="ine_document" class="form-control" value="">
                        <div class="show-edit-document d-none" id="download_ine_document"><a href="" download>{{ __("translation.Current INE Document") }}</a></div>
                        <span id="error_ine_document" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ioweyou_document" class="form-label">{{ __("translation.IOweYou document") }}<span class="text-danger"> *</span></label>
                        <input type="file" id="ioweyou_document" name="ioweyou_document" class="form-control" value="">
                        <div class="show-edit-document d-none" id="download_ioweyou_document"><a href="" download>{{ __("translation.Current IOweYou Document") }}</a></div>
                        <span id="error_ioweyou_document" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ioweyou_expiry_date" class="form-label">{{ __("translation.IOweYou Expiry Date") }}<span class="text-danger"> *</span></label>
                        <input type="date" id="ioweyou_expiry_date" name="ioweyou_expiry_date" class="form-control" value="">
                        <span id="error_ioweyou_expiry_date" class="error text-danger"></span>
                    </div>
                </div>
                <div class="d-block modal-footer">
                    <button type="button" class="btn btn-secondary float-start" id="model-cancle-btn" data-bs-dismiss="modal" aria-label="Close">{{ __("translation.Cancel") }}</button>
                    <button type="submit" class="btn btn-success float-end" id="addorUpdateBtn">{{ __("translation.Save") }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script>
        var getCompaniesUrl = "{{ route('company.get') }}";
        var getCompanyDetailsUrl = "{{ route('company.edit', ['id' => '__ID__']) }}";
        var deleteCompanyUrl = "{{ route('company.delete') }}";
        var changeCompanyStatusUrl = "{{ route('company.changeStatus') }}";
        var basePath = "{{ asset('/storage') }}/";
        var deleteCompanyConfirmMsg = '{{ __("translation.All details related to this will be permanently deleted. Are you sure you want to proceed with the deletion?") }}';
        var changeStatusMsg = '{{ __("translation.Are you sure you want to change status of company?") }}';
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/company.js"></script>
@endpush
