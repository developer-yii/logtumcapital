@php
  $labelMain = __("translation.Payment Collectors");
  $label = __("translation.payment collector");
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
                        <table id="payment_collectors_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("translation.Name") }}</th>
                                    <th>{{ __("translation.E-mail Address") }}</th>
                                    <th>{{ __("translation.Phone Number") }}</th>
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

{{-- add paymentcollector modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('paymentcollector.store') }}" method="POST" id="add-form" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>{{ __("translation.Add") }}</span> {{$label}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group mb-3">
                        <label for="first_name" class="form-label">{{ __("translation.First name") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="">
                        <span id="error_first_name" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="middle_name" class="form-label">{{ __("translation.Middle name") }}</label>
                        <input type="text" id="middle_name" name="middle_name" class="form-control" value="">
                        <span id="error_middle_name" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="last_name" class="form-label">{{ __("translation.Last name") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="">
                        <span id="error_last_name" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">{{ __("translation.E-mail address") }}<span class="text-danger"> *</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="">
                        <span id="error_email" class="error text-danger"></span>
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
                        <label for="password" class="form-label">{{ __("translation.Password") }}<span class="text-danger"> *</span></label>
                        <input type="password" id="password" name="password" class="form-control" value="">
                        <span id="error_password" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirm_password" class="form-label">{{ __("translation.Confirm password") }}<span class="text-danger"> *</span></label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" value="">
                        <span id="error_confirm_password" class="error text-danger"></span>
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
        var getPaymentCollectorsUrl = "{{ route('paymentcollector.get') }}";
        var getPaymentCollectorDetailsUrl = "{{ route('paymentcollector.edit', ['id' => '__ID__']) }}";
        var deletePaymentCollector = "{{ route('paymentcollector.delete') }}";
        var deleteConfirmMsg = '{{ __("translation.Are you sure you want to delete?") }}';
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/payment_collector.js"></script>
@endpush
