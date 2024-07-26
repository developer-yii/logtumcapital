@php
  $labelMain = "Payment Collections";
  $label = "payment collection";
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
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col">
                        <label for="collected_date">Collections</label>
                        <select class="form-select w-auto" id="collected_date">
                            @if(count($collectedData) > 0)
                                @foreach($collectedData as $collection)
                                    @php
                                        $dateValue = date('Y-m-d', strtotime($collection->created_at));
                                        $dateDisplay = date('d-m-Y', strtotime($collection->created_at));
                                    @endphp
                                    <option value="{{ $dateValue }}" {{ ($dateValue == $fridayDate)?'selected':'' }}>{{ $dateDisplay }}</option>
                                @endforeach
                            @else
                                @php
                                    $dateValue = date('Y-m-d', strtotime($fridayDate));
                                    $dateDisplay = date('d-m-Y', strtotime($fridayDate));
                                @endphp
                                <option value="{{ $dateValue }}" {{ ($dateValue == $fridayDate)?'selected':'' }}>{{ $dateDisplay }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col" id="upload_bank_receipt_btn_section">
                        @php
                            $todayDate = date('Y-m-d');
                            $hideShowBtn = (date('N', strtotime($todayDate)) == 5) ? true : false;
                        @endphp
                        <div class="float-end">
                            @if($hideShowBtn)
                                <button class="btn btn-primary" id="upload_bank_receipt_btn">Upload Bank Receipt</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="payment_collections_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    <th>Installment Date</th>
                                    <th class="text-end">Collect</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
                    <h4 class="modal-title"><span>Upload</span> bank receipt</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <div class="form-group mb-3">
                        <label for="bank_receipt" class="form-label">Bank Receipt</label>
                        <input type="file" id="bank_receipt" name="bank_receipt" class="form-control" value="">
                        <span id="error_bank_receipt" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" id="amount" name="amount" class="form-control" value="">
                        <span id="error_amount" class="error text-danger"></span>
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
        var getPaymentCollectionsUrl = "{{ route('paymentcollector.getPaymentCollections') }}";
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/payment_collection.js"></script>
@endpush
