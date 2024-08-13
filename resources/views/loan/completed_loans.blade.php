@php
  $labelMain = "Loan Requests";
  $label = "Loan requests";
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
                        <table id="completed_loans_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th class="text-start">#</th>
                                    <th class="text-center">Company Name</th>
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Duration (in weeks)</th>
                                    <th class="text-center">IOweYou</th>
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
@endsection
@push('js')
    <script>
        var getCompletedLoansUrl = "{{ route('loan.completedLoans') }}";
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/completed_loans.js"></script>
@endpush
