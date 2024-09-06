@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
<div class="row mt-3 mb-3">
    <div class="col-md-2">
        <h4 class="page-title">{{ __("translation.Dashboard") }}</h4>
    </div>
    <div class="col-md-10"></div>
</div>

<div class="row">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="col-xl-12 col-lg-12">
        <div class="row">
            <div class="col-sm-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-warehouse widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of Companies">{{ __("translation.Companies") }}</h5>
                        <h3 class="mt-3 mb-3">{{ currencyFormatter($totalCompanies, false) }}</h3>
                        {{-- <p class="mb-0 text-muted">
                            <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> +15%</span>
                            <span class="text-nowrap">Total last 30 days</span>
                        </p> --}}
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="uil-users-alt widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of employees">{{ __("translation.Employees") }}</h5>
                        <h3 class="mt-3 mb-3">{{ currencyFormatter($totalEmployees, false) }}</h3>
                        {{-- <p class="mb-0 text-muted">
                            <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> +0.5%</span>
                            <span class="text-nowrap">Total delegated</span>
                        </p> --}}
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="uil-users-alt widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0" title="Number of investors">{{ __("translation.Investors") }}</h5>
                        <h3 class="mt-3 mb-3">{{ currencyFormatter($totalInvestors, false) }}</h3>
                        {{-- <p class="mb-0 text-muted">
                            <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> +0.5%</span>
                            <span class="text-nowrap">Total delegated</span>
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
