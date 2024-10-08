@php
  $labelMain = __("translation.Loan Requests");
  $label = __("translation.Loan Requests");
@endphp
@extends('layouts.main')
@section('title', $labelMain)
@push('css')
    <link href="{{asset('/')}}backend/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/')}}backend/css/vendor/jquery-ui.css" rel="stylesheet" type="text/css" />
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
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="requested_fund_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("translation.Company Name") }}</th>
                                    <th>{{ __("translation.Employee Name") }}</th>
                                    <th>{{ __("translation.Bank Name") }}</th>
                                    <th>{{ __("translation.Account Number") }}</th>
                                    <th>{{ __("translation.Amount") }}</th>
                                    <th>{{ __("translation.Duration (in weeks)") }}</th>
                                    <th>{{ __("translation.IOweYou") }}</th>
                                    <th>{{ __("translation.Status") }}</th>
                                    <th class="text-end">{{ __("translation.Date") }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div
</div>

{{-- upload iowe you modal --}}
<div class="modal fade" id="upload_ioweyou_modal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form id="upload_ioweyou_form" method="POST" action="{{ route('loan.uploadIoweyou') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>{{ __("translation.Upload") }}</span> {{ __("translation.IOweYou") }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <input type="hidden" id="fund_request_id" name="fund_request_id" value="">
                    <input type="hidden" id="status" name="status" value="">
                    <div class="form-group mb-3">
                        <label for="disbursement_date" class="form-label">{{ __("translation.Disbursement date") }}</label>
                        <input type="text" id="disbursement_date" name="disbursement_date" class="form-control">
                        <span id="error_disbursement_date" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ioweyou_document" class="form-label">{{ __("translation.IOweYou") }}</label>
                        <input type="file" id="ioweyou_document" name="ioweyou_document" class="form-control" value="">
                        <span id="error_ioweyou_document" class="error text-danger"></span>
                    </div>
                </div>
                <div class="d-block modal-footer">
                    <button type="button" class="btn btn-secondary float-start" id="model-cancle-btn" data-bs-dismiss="modal" aria-label="Close">{{ __("translation.Cancel") }}</button>
                    <button type="submit" class="btn btn-success float-end" id="save_btn">{{ __("translation.Save") }}</button>
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
        var getFundRequestsUrl = "{{ route('loan.requests') }}";
        var rejectRequestStatusUrl = "{{ route('loan.rejectFundRequestStatus') }}";
        var changeFundRequestConfirmMsg = '{{ __("translation.Are you sure you want to change status?") }}';
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/jquery-ui.min.js"></script>
    <script src="{{asset('/')}}backend/page-js/loan_request.js"></script>
@endpush
