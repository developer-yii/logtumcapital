@php
  $labelMain = "Loan Requests";
  $label = "request for loan";
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
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addModal" id="add-new-btn"><i class="uil-plus"></i> Add {{ $label }}</button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        @php
                            $loanAmount = !empty($loanData->amount)?$loanData->amount:0;
                        @endphp
                        <h4>Name : {{ auth()->user()->first_name." ".auth()->user()->last_name }}</h4>
                        <h5>Debit As of Today : {{ currencyFormatter($loanAmount) }}</h5>
                        <h5>Credit Available : {{ currencyFormatter(auth()->user()->authorized_credit_limit - $loanAmount) }}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="requested_fund_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Amount</th>
                                    <th>Duration (in weeks)</th>
                                    <th>Date</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div
</div>

{{-- add new fund request modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form id="add-form" method="POST" action="{{ route('employee.storeFundRequest') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>Add</span> {{$label}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <div class="form-group mb-3">
                        <label for="bank_name" class="form-label">Bank Name <span class="text-danger"> *</span></label>
                        <input type="text" id="bank_name" name="bank_name" class="form-control" value="">
                        <span id="error_bank_name" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="account_number" class="form-label">Account Number <span class="text-danger"> *</span></label>
                        <input type="text" id="account_number" name="account_number" class="form-control" value="">
                        <span id="error_account_number" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger"> *</span></label>
                        <input type="number" id="amount" name="amount" min="1" class="form-control" value="">
                        <span id="error_amount" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="duration" class="form-label">Duration <span class="text-danger"> *</span></label>
                        <div class="input-group input-group-merge">
                            <input type="number" id="duration" name="duration" min="1" class="form-control" value="">
                            <div class="input-group-text" data-password="false">
                                weeks
                            </div>
                        </div>
                        <span id="error_duration" class="error text-danger"></span>
                    </div>
                </div>
                <div class="d-block modal-footer">
                    <button type="button" class="btn btn-secondary float-start" id="model-cancle-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-success float-end" id="addorUpdateBtn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
    <script>
        var getFundRequestsUrl = "{{ route('employee.requestFund') }}";
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/request_fund.js"></script>
@endpush
