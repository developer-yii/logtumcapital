<div class="leftside-menu">
    <a href="{{ route('dashboard') }}" class="logo text-center logo-light bg-white">
        <span class="logo-lg bg-white">
            <img src="{{ asset('/') }}frontend/images/LOGO.png" alt="" height="50">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('/') }}frontend/images/LOGO.png" alt="" height="20">
        </span>
    </a>
    <a href="{{ route('dashboard') }}" class="logo text-center logo-dark">
        <span class="logo-lg bg-white">
            <img src="{{ asset('/') }}frontend/images/LOGO.png" alt="" height="16">
        </span>
        <span class="logo-sm bg-white">
            <img src="{{ asset('/') }}frontend/images/LOGO.png" alt="" height="16">
        </span>
    </a>
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <ul class="side-nav">
            <li class="side-nav-title side-nav-item">{{ __("translation.Menu") }}</li>
            @php
                $user = auth()->user();
            @endphp
            @if($user->role == 1)
                <li class="side-nav-item {{ isActiveRouteMain(['dashboard']) }}">
                    <a href="{{ route('dashboard') }}" class="side-nav-link {{ isActiveRoute(['dashboard']) }}">
                        <i class="uil-home-alt fs-3"></i>
                        <span>{{ __("translation.Dashboard") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['investment.get']) }}">
                    <a href="{{ route('investment.get') }}" class="side-nav-link {{ isActiveRoute(['investment.get']) }}">
                        <i class="uil-moneybag-alt fs-3"></i>
                        <span>{{ __("translation.Investments") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['interestrate.get']) }}">
                    <a href="{{ route('interestrate.get') }}" class="side-nav-link {{ isActiveRoute(['interestrate.get']) }}">
                        <i class="mdi mdi-brightness-percent fs-3"></i>
                        <span>{{ __("translation.Interest Rate") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['company.get']) }}">
                    <a href="{{ route('company.get') }}" class="side-nav-link {{ isActiveRoute(['company.get']) }}">
                        <i class="mdi mdi-warehouse fs-3"></i>
                        <span>{{ __("translation.Companies") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['companyadmin.get']) }}">
                    <a href="{{ route('companyadmin.get') }}" class="side-nav-link {{ isActiveRoute(['companyadmin.get']) }}">
                        <i class="uil-book-reader fs-3"></i>
                        <span>{{ __("translation.Company Admins") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['employee.get']) }}">
                    <a href="{{ route('employee.get') }}" class="side-nav-link {{ isActiveRoute(['employee.get']) }}">
                        <i class="uil-users-alt fs-3"></i>
                        <span>{{ __("translation.Employees") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['loan.requests']) }}">
                    <a href="{{ route('loan.requests') }}" class="side-nav-link {{ isActiveRoute(['loan.requests']) }}">
                        <i class="mdi mdi-cash-plus fs-3"></i>
                        <span>{{ __("translation.Loan Requests") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['loan.pendingForDisbursed']) }}">
                    <a href="{{ route('loan.pendingForDisbursed') }}" class="side-nav-link {{ isActiveRoute(['loan.pendingForDisbursed']) }}">
                        <i class="uil-money-insert fs-3"></i>
                        <span>{{ __("translation.Disbursed Loans") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['loan.completedLoans']) }}">
                    <a href="{{ route('loan.completedLoans') }}" class="side-nav-link {{ isActiveRoute(['loan.completedLoans']) }}">
                        <i class="uil-money-withdraw fs-3"></i>
                        <span>{{ __("translation.Completed Loans") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['loan.collectedInstallments']) }}">
                    <a href="{{ route('loan.collectedInstallments') }}" class="side-nav-link {{ isActiveRoute(['loan.collectedInstallments']) }}">
                        <i class="uil-money-stack fs-3"></i>
                        <span>{{ __("translation.Collected Installments") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['settings.get']) }}">
                    <a href="{{ route('settings.get') }}" class="side-nav-link {{ isActiveRoute(['settings.get']) }}">
                        <i class="uil-cog fs-3"></i>
                        <span>Configuraciones</span>
                    </a>
                </li>
            @elseif($user->role == 2)
                <li class="side-nav-item {{ isActiveRouteMain(['employee.getCompanyEmployees']) }}">
                    <a href="{{ route('employee.getCompanyEmployees') }}" class="side-nav-link {{ isActiveRoute(['employee.getCompanyEmployees']) }}">
                        <i class="uil-users-alt fs-3"></i>
                        <span>{{ __("translation.Employees") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['paymentcollector.get']) }}">
                    <a href="{{ route('paymentcollector.get') }}" class="side-nav-link {{ isActiveRoute(['paymentcollector.get']) }}">
                        <i class="uil uil-money-withdrawal fs-3"></i>
                        <span>{{ __("translation.Payment Collector") }}</span>
                    </a>
                </li>
            @elseif($user->role == 3)
                <li class="side-nav-item {{ isActiveRouteMain(['paymentcollector.getPaymentCollections']) }}">
                    <a href="{{ route('paymentcollector.getPaymentCollections') }}" class="side-nav-link {{ isActiveRoute(['paymentcollector.getPaymentCollections']) }}">
                        <i class="uil-users-alt fs-3"></i>
                        <span>{{ __("translation.Payment Collections") }}</span>
                    </a>
                </li>
            @elseif($user->role == 4)
                <li class="side-nav-item {{ isActiveRouteMain(['employee.requestFund']) }}">
                    <a href="{{ route('employee.requestFund') }}" class="side-nav-link {{ isActiveRoute(['employee.requestFund']) }}">
                        <i class="mdi mdi-cash-plus fs-3"></i>
                        <span>{{ __("translation.Loan Requests") }}</span>
                    </a>
                </li>
                <li class="side-nav-item {{ isActiveRouteMain(['employee.loanTerms']) }}">
                    <a href="{{ route('employee.loanTerms') }}" class="side-nav-link {{ isActiveRoute(['employee.loanTerms']) }}">
                        <i class="mdi mdi-cash-refund fs-3"></i>
                        <span>{{ __("translation.Loan Terms") }}</span>
                    </a>
                </li>
            @endif
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
