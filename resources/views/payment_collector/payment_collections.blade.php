@php
  $labelMain = __("translation.Payment Collections");
  $label = __("translation.payment collection");
@endphp
@extends('layouts.main')
@section('title', $labelMain)
@push('css')
    <link href="{{asset('/')}}backend/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/')}}backend/css/vendor/jquery-ui.css" rel="stylesheet" type="text/css" />
    <style>
        #total_amount{
            padding-right: 20rem;
        }
    </style>
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
                <div class="row mb-3">
                    <div class="col">
                        <label for="collection_date">{{ __("translation.Collections") }}</label><br>
                        <input type="text" id="collection_date" name="collection_date">
                    </div>
                    <div class="col" id="upload_bank_receipt_btn_section">
                        <div class="float-end">
                            <button class="btn btn-primary d-none" id="upload_bank_receipt_btn">{{ __("translation.Upload Bank Receipt") }}</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="payment_collections_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">{{ __("translation.Employee Name") }}</th>
                                    <th class="text-center">{{ __("translation.Installment Date") }}</th>
                                    <th class="text-center">{{ __("translation.Collect") }}</th>
                                    <th class="text-end">{{ __("translation.Status") }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <span id="total_amount" class="text-end fw-bolder">{{ __("translation.TOTAL") }}: {{ currencyFormatter(0) }}</span>
                </div>
            </div>
        </div>
    </div
</div>

{{-- upload bank reciept --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('paymentcollector.storeBankReceipt') }}" method="POST" id="add-form" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>{{ __("translation.Upload") }}</span> {{ __("translation.bank receipt") }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <input type="hidden" id="installment_date" name="installment_date" value="">
                    <div class="form-group mb-3">
                        <label for="bank_receipt" class="form-label">{{ __("translation.Bank Receipt") }}</label>
                        <input type="file" id="bank_receipt" name="bank_receipt" class="form-control" value="">
                        <span id="error_bank_receipt" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount" class="form-label">{{ __("translation.Amount") }}</label>
                        <input type="text" id="amount" name="amount" class="form-control" value="">
                        <span id="error_amount" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="note" class="form-label">{{ __("translation.Note") }}</label>
                        <textarea id="note" name="note" class="form-control"></textarea>
                        <span id="error_note" class="error text-danger"></span>
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
        var getPaymentCollectionsUrl = "{{ route('paymentcollector.getPaymentCollections') }}";
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/jquery-ui.min.js"></script>
    <script src="{{asset('/')}}backend/page-js/payment_collection.js"></script>
@endpush
