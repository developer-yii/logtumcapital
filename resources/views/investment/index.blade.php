@php
  $labelMain = __("translation.Investment Details");
  $label = __("translation.Investment");
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
                        <table id="investments_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("translation.Name") }}</th>
                                    <th>{{ __("translation.Contributions") }}</th>
                                    <th>{{ __("translation.Interest Rate") }}</th>
                                    <th>{{ __("translation.Interest Earnings") }}</th>
                                    <th>{{ __("translation.Total Amount") }}</th>
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

{{-- add investment modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('investment.store') }}" method="POST" id="add-form">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span>{{ __("translation.Add") }}</span> {{$label}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-1">
                    <input type="hidden" name="investment_id" id="investment_id">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">{{ __("translation.Name") }} <span class="text-danger"> *</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="">
                        <span id="error_name" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="contributions" class="form-label">{{ __("translation.Contributions") }} <span class="text-danger"> *</span></label>
                        <input type="text" id="contributions" name="contributions" class="form-control" value="">
                        <span id="error_contributions" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="interest_rate" class="form-label">{{ __("translation.Interest rate") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="interest_rate" name="interest_rate" class="form-control" value="">
                        <span id="error_interest_rate" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="interest_earnings" class="form-label">{{ __("translation.Interest earnings") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="interest_earnings" name="interest_earnings" class="form-control" value="">
                        <span id="error_interest_earnings" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="total_amount" class="form-label">{{ __("translation.Total amount") }}<span class="text-danger"> *</span></label>
                        <input type="text" id="total_amount" name="total_amount" class="form-control" value="">
                        <span id="error_total_amount" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="investment_contract" class="form-label">{{ __("translation.Investment Contract") }}<span class="text-danger"> *</span></label>
                        <input type="file" id="investment_contract" name="investment_contract" class="form-control">
                        <div class="show-edit-document d-none" id="download_investment_contract"><a href="" download>{{ __("translation.Current Investment Contarct") }}</a></div>
                        <span id="error_investment_contract" class="error text-danger"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="note" class="form-label">{{ __("translation.Note") }}<span class="text-danger"> *</span></label>
                        <textarea id="note" name="note" class="note form-control"></textarea>
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
        var getInvestmentsUrl = "{{ route('investment.get') }}";
        var getInvestmentDetailsUrl = "{{ route('investment.edit', ['id' => '__ID__']) }}";
        var deleteInvestmentUrl = "{{ route('investment.delete') }}";
        var basePath = "{{ asset('/storage') }}/";
        var deleteInvestmentConfirmMsg = '{{ __("translation.All details related to this will be permanently deleted. Are you sure you want to proceed with the deletion?") }}';
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/investment.js"></script>
@endpush
