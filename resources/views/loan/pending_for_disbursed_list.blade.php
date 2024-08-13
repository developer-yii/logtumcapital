@php
  $labelMain = "Disbursed Loans";
  $label = "Disbursed loans";
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
                <div class="row">
                    <div class="col-md-12">
                        <table id="disbursed_pending_loans" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Company Name</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Duration (in weeks)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div
</div>

{{-- View more details of loan modal --}}
<div class="modal fade" id="view_loan_details_modal" tabindex="-1" aria-labelledby="viewLoanDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLoanDetailsModalLabel">Loan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col">
                        <label for="first_installment_date" class="form-label">First Installment Date</label>
                        <input type="text" class="form-control" id="first_installment_date" readonly>
                    </div>
                    <div class="col">
                        <label for="last_installment_date" class="form-label">Last Installment Date</label>
                        <input type="text" class="form-control" id="last_installment_date" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="yearly_interest_rate" class="form-label">Annual Interest Rate</label>
                        <input type="text" class="form-control" id="yearly_interest_rate" readonly>
                    </div>
                    <div class="col">
                        <label for="loan_interest_rate" class="form-label">Loan Interest Rate</label>
                        <input type="text" class="form-control" id="loan_interest_rate" readonly>
                    </div>
                    <div class="col">
                        <label for="weekly_interest_rate" class="form-label">Weekly Interest Rate</label>
                        <input type="text" class="form-control" id="weekly_interest_rate" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        var getDisbursedPendingLoansUrl = "{{ route('loan.pendingForDisbursed') }}";
        // var changeLoanStatusUrl = "loan.changeLoanStatus";
        var viewLoanDetailsUrl = "{{ route('loan.getLoanDetails') }}";
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/pending_for_disbursed.js"></script>
@endpush
