@php
  $labelMain = __("translation.Employee Details");
  $label = __("translation.Employee");
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
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row m-3">
                <div class="col">
                    @if(!empty($creditData))
                        <h4>{{ !empty($creditData['companyName'])?$creditData['companyName']:$creditData['companyName'] }}</h4>
                        <h5>{{ __("translation.Total Credit") }} : {{ currencyFormatter($creditData['credit']) }}</h5>
                        <h5>{{ __("translation.Available Credit") }} : {{ currencyFormatter($creditData['availableCredit']) }}</h5>
                        <h5>{{ __("translation.Used Credit") }} : {{ currencyFormatter($creditData['usedCredit']) }}</h5>
                    @endif
                </div>
                <div class="col">
                    <div class="float-end">
                        <button class="btn btn-primary mb-2" id="increase_company_limit">{{ __("translation.Increase Company Credit Limit") }}</button><br>
                        <button class="btn btn-primary" id="change_user_credit_limit">{{ __("translation.Change Employee Credit Limit") }}</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="company_employees_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("translation.Name") }}</th>
                                    <th>{{ __("translation.E-mail Address") }}</th>
                                    <th>{{ __("translation.Phone Number") }}</th>
                                    <th>{{ __("translation.Authorized Credit Limit") }}</th>
                                    <th>{{ __("translation.Used Credit") }}</th>
                                    <th>{{ __("translation.Available Credit") }}</th>
                                    <th>{{ __("translation.INE") }}</th>
                                    <th>{{ __("translation.Proof Of Address") }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div
</div>

{{-- change company credit limit request modal --}}
<div class="modal fade" id="change_company_authorized_limit" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <form action="{{ route('employee.sendLimitForChangeRequest') }}" method="POST" id="change_company_authorized_limit_form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __("translation.Increase company credit limit request") }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <input type="hidden" name="company_name" value="{{ $companyName }}">
                    <input type="hidden" name="current_limit" value="{{ $companyTotalCredit }}">
                    <div class="form-group mb-3">
                        <label for="authorized_credit_limit" class="form-label">{{ __("translation.Authorized credit limit") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="authorized_credit_limit" name="authorized_credit_limit" class="form-control" value="">
                        <span id="error_authorized_credit_limit" class="error text-danger"></span>
                    </div>
                    <p><span class="text-danger text-bolder">{{ __("translation.Note") }} : </span>{{ __("translation.Please note that your current credit limit will be increased by the entered amount.") }}</p>
                </div>
                <div class="d-block modal-footer">
                    <button type="button" class="btn btn-secondary float-start" id="model-cancle-btn" data-bs-dismiss="modal" aria-label="Close">{{ __("translation.Cancel") }}</button>
                        <button type="submit" class="btn btn-success float-end" id="saveRequestBtn">{{ __("translation.Save") }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- change employee credit limit modal --}}
<div class="modal fade" id="change_employee_authorized_limit" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <form action="{{ route('employee.changeEmployeeCreditLimit') }}" method="POST" id="change_employee_authorized_limit_form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __("translation.Change employee credit limit") }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <div class="form-group mb-3">
                        <label for="employee" class="form-label">{{ __("translation.Select Employee")}}<span class="text-danger"> *</span></label>
                        <select id="employee" name="employee" class="form-control form-select">
                            <option value="">{{ __("translation.Select Employee") }}</option>
                            @foreach($employeeData as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->email }}</option>
                            @endforeach
                        </select>
                        <span id="error_employee" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="authorized_credit_limit" class="form-label">{{ __("translation.Authorized credit limit") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="authorized_credit_limit" name="authorized_credit_limit" class="form-control" value="">
                        <span id="error_authorized_credit_limit" class="error text-danger"></span>
                    </div>
                </div>
                <div class="d-block modal-footer">
                    <button type="button" class="btn btn-secondary float-start" id="model-cancle-btn" data-bs-dismiss="modal" aria-label="Close">{{ __("translation.Cancel") }}</button>
                    <button type="submit" class="btn btn-success float-end" id="saveEmpRequestBtn">{{ __("translation.Save") }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- enlarge image in modal --}}
<div class="modal fade" id="enlarged_image_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __("translation.Preview Image") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="enlarged_image" src="" class="img-fluid" alt="Image Preview">
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        var companyId = "{{ isset($_REQUEST['cid'])?$_REQUEST['cid']:'' }}";
        var getEmployeesUrl = "{{ route('employee.get') }}";
        var getCompanyEmployeesUrl = "{{ route('employee.getCompanyEmployees') }}";
        var getEmployeeDetailsUrl = "{{ route('employee.edit', ['id' => '__ID__']) }}";
        var deleteEmployeeUrl = "{{ route('employee.delete') }}";
        var deleteEmployeeConfirmMsg = '{{ __("translation.All details related to this will be permanently deleted. Are you sure you want to proceed with the deletion?") }}';
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/employee.js"></script>
@endpush
