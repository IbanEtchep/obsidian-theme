<li>
    <a class="dropdown-item" href="{{ route('profile.index') }}">
        <i class="bi bi-person"></i> {{ trans('messages.nav.profile') }}
    </a>
</li>
@foreach(plugins()->getUserNavItems() ?? [] as $navId => $navItem)
    <li>
        <a class="dropdown-item" href="{{ route($navItem['route']) }}">
            <i class="{{ $navItem['icon'] ?? 'bi bi-three-dots' }}"></i> {{ $navItem['name'] }}
        </a>
    </li>
@endforeach
@if(Auth::user()->hasAdminAccess())
    <li><hr class="dropdown-divider"></li>
    <li>
        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> {{ trans('messages.nav.admin') }}
        </a>
    </li>
@endif
<li><hr class="dropdown-divider"></li>
<li>
    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right"></i> {{ trans('auth.logout') }}
    </a>
</li>
