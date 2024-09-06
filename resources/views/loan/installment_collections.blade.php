@php
  $labelMain = __("translation.Collected Installments");
  $label = __("translation.Collected Installments");
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
                        <table id="collected_installments_table" class="table site_table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th class="text-start">#</th>
                                    <th class="text-center">{{ __("translation.Company Name") }}</th>
                                    <th class="text-center">{{ __("translation.Collected By") }}</th>
                                    <th class="text-center">{{ __("translation.Amount") }}</th>
                                    <th class="text-center">{{ __("translation.Bank Receipt") }}</th>
                                    <th class="text-center">{{ __("translation.Note") }}</th>
                                    <th class="text-end">{{ __("translation.Collected On") }}</th>
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
        var getCollectedInstallmentsUrl = "{{ route('loan.collectedInstallments') }}";
    </script>
    <script src="{{asset('/')}}backend/js/vendor/jquery.dataTables.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor/dataTables.bootstrap5.js"></script>
    <script src="{{asset('/')}}backend/page-js/collected_installments.js"></script>
@endpush
