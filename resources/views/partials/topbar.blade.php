@php
$loginUser = Auth::user();
@endphp
<div class="navbar-custom">
    <ul class="list-unstyled topbar-menu float-end mb-0">

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                aria-expanded="false">
                <span class="account-user-avatar">
                    <img src="{{asset('/')}}backend/images/user.png" alt="user-image" class="rounded-circle">
                </span>
                <span>
                    <span class="account-user-name">{{ Illuminate\Support\Str::limit($loginUser->first_name.' '.$loginUser->last_name, 20, '...') }}</span>
                    <span class="account-position">{{ App\Models\User::getRoleName($loginUser->role) }}</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">{{ __("translation.Welcome") }}, {{ App\Models\User::getRoleName($loginUser->role) }}!</h6>
                </div>

                <!-- item-->
                {{-- <a href="" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-circle me-1"></i>
                    <span>My Account</span>
                </a> --}}

                <!-- item-->
                <a href="{{route('dashboard')}}" class="dropdown-item notify-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout me-1"></i>
                    <span>{{ __("translation.Logout") }}</span>
                </a>
                <form id="logout-form" action="{{route('logout')}}" method="POST" class="app-display-none">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
    <button class="button-menu-mobile open-left">
        <i class="mdi mdi-menu"></i>
    </button>
</div>
