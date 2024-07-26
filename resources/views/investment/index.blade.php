@php
  $labelMain = "Investment Details";
  $label = "Investment";
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
                <div class="row">
                    <div class="col-md-12">
                        <table id="investments_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contributions</th>
                                    <th>Interest Rate</th>
                                    <th>Interest Earnings</th>
                                    <th>Total Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div
</div>

{{-- add investment modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('investment.store') }}" method="POST" id="add-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>Add</span> {{$label}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <input type="hidden" name="investment_id" id="investment_id">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="">
                        <span id="error_name" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="contributions" class="form-label">Contributions</label>
                        <input type="text" id="contributions" name="contributions" class="form-control" value="">
                        <span id="error_contributions" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="interest_rate" class="form-label">Interest rate</label>
                        <input type="text" id="interest_rate" name="interest_rate" class="form-control" value="">
                        <span id="error_interest_rate" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="interest_earnings" class="form-label">Interest earnings</label>
                        <input type="text" id="interest_earnings" name="interest_earnings" class="form-control" value="">
                        <span id="error_interest_earnings" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="total_amount" class="form-label">Total amount</label>
                        <input type="text" id="total_amount" name="total_amount" class="form-control" value="">
                        <span id="error_total_amount" class="error text-danger"></span>
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
        var getInvestmentsUrl = "{{ route('investment.get') }}";
        var getInvestmentDetailsUrl = "{{ route('investment.edit', ['id' => '__ID__']) }}";
        var deleteInvestmentUrl = "{{ route('investment.delete') }}";
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/investment.js"></script>
@endpush
