<nav class="navbar navbar-expand-lg obsidian-navbar fixed-top @if(!request()->routeIs('home')) obsidian-navbar-solid @endif" id="obsidian-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            @if(site_logo())
                <img src="{{ site_logo() }}" alt="{{ site_name() }}" height="36" class="me-2">
            @endif
            <span class="fw-bold">{{ site_name() }}</span>
        </a>

        <button class="navbar-toggler obsidian-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarObsidian" aria-controls="navbarObsidian" aria-expanded="false" aria-label="{{ trans('messages.nav.toggle') }}">
            <span class="obsidian-toggler-bar"></span>
            <span class="obsidian-toggler-bar"></span>
            <span class="obsidian-toggler-bar"></span>
        </button>

        {{-- Center: nav links (inside collapse for mobile) --}}
        <div class="collapse navbar-collapse" id="navbarObsidian">
            <ul class="navbar-nav">
                @foreach($navbar as $element)
                    @if(!$element->isDropdown())
                        <li class="nav-item">
                            <a class="nav-link @if($element->isCurrent()) active @endif" href="{{ $element->getLink() }}" @if($element->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $element->name }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if($element->isCurrent()) active @endif" href="#" id="navDrop{{ $element->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $element->name }}
                            </a>
                            <ul class="dropdown-menu obsidian-dropdown" aria-labelledby="navDrop{{ $element->id }}">
                                @foreach($element->elements as $child)
                                    <li>
                                        <a class="dropdown-item @if($child->isCurrent()) active @endif" href="{{ $child->getLink() }}" @if($child->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- Auth links (visible inside collapse on mobile only) --}}
            <ul class="navbar-nav d-lg-none obsidian-nav-mobile-auth">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> {{ trans('auth.login') }}
                        </a>
                    </li>
                    @if(Route::has('register'))
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm w-100 mt-1" href="{{ route('register') }}">
                                {{ trans('auth.register') }}
                            </a>
                        </li>
                    @endif
                @else
                    @include('elements.notifications')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}" width="26" height="26" class="rounded" style="object-fit:cover">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu obsidian-dropdown">
                            @include('elements._user-dropdown-items')
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>

        {{-- Right: auth links (desktop only, outside collapse) --}}
        <ul class="navbar-nav align-items-center d-none d-lg-flex obsidian-nav-right">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
                    </a>
                </li>
                @if(Route::has('register'))
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary btn-sm px-3" href="{{ route('register') }}">
                            {{ trans('auth.register') }}
                        </a>
                    </li>
                @endif
            @else
                @include('elements.notifications')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdownObsidian" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}" width="26" height="26" class="rounded" style="object-fit:cover">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end obsidian-dropdown" aria-labelledby="userDropdownObsidian">
                        @include('elements._user-dropdown-items')
                    </ul>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
        </ul>
    </div>
</nav>
