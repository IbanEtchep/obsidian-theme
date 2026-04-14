{{-- Player HUD Sidebar --}}
<div class="sv-sidebar">
    {{-- User card --}}
    @if($shopUser !== null)
        <div class="sv-user">
            <img src="{{ $shopUser->getAvatar(48) }}" alt="{{ $shopUser->name }}" class="sv-user-avatar">
            <div class="sv-user-info">
                <span class="sv-user-name">{{ $shopUser->name }}</span>
                @if(use_site_money())
                    <span class="sv-user-balance">{{ format_money($shopUser->money) }}</span>
                @endif
            </div>
        </div>

        <div class="sv-actions">
            @if(use_site_money())
                <a href="{{ route('shop.offers.select') }}" class="sv-action-btn sv-action-credit">
                    <i class="bi bi-plus-circle"></i> {{ trans('shop::messages.cart.credit') }}
                </a>
            @endif
            <a href="{{ route('shop.cart.index') }}" class="sv-action-btn">
                <i class="bi bi-cart3"></i> {{ trans('shop::messages.cart.title') }}
            </a>
            @if($userHasPayments)
                <a href="{{ route('shop.profile') }}" class="sv-action-btn">
                    <i class="bi bi-receipt"></i> {{ trans('shop::messages.profile.payments') }}
                </a>
            @endif
            @guest
                <form action="{{ route('shop.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sv-action-btn sv-action-logout w-100">
                        <i class="bi bi-box-arrow-right"></i> {{ trans('auth.logout') }}
                    </button>
                </form>
            @endguest
        </div>
    @else
        <a href="{{ route('shop.login') }}" class="sv-action-btn sv-action-credit w-100 mb-3">
            <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
        </a>
    @endif

    {{-- Categories --}}
    <div class="sv-panel">
        <div class="sv-panel-head">
            <i class="bi bi-grid-fill me-2"></i> Catégories
        </div>
        <nav class="sv-nav">
            @if($displayHome)
                <a href="{{ route('shop.home') }}" class="sv-nav-item @if($category === null) --active @endif">
                    <i class="bi bi-house-fill"></i>
                    <span>{{ trans('messages.home') }}</span>
                </a>
            @endif
            @foreach($categories as $subCategory)
                <a href="{{ route('shop.categories.show', $subCategory) }}" class="sv-nav-item @if($subCategory->is($category)) --active @endif">
                    @if($subCategory->icon)<i class="{{ $subCategory->icon }}"></i>@else<i class="bi bi-tag-fill"></i>@endif
                    <span>{{ $subCategory->name }}</span>
                </a>
                @foreach($subCategory->categories as $cat)
                    <a href="{{ route('shop.categories.show', $cat) }}" class="sv-nav-item sv-nav-sub @if($cat->is($category)) --active @endif">
                        @if($cat->icon)<i class="{{ $cat->icon }}"></i>@else<i class="bi bi-chevron-right"></i>@endif
                        <span>{{ $cat->name }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>
    </div>

    {{-- Goal --}}
    @if($goal >= 0)
        <div class="sv-panel">
            <div class="sv-panel-head">
                <i class="bi bi-graph-up me-2"></i> {{ trans('shop::messages.goal.title') }}
            </div>
            <div class="sv-panel-body">
                <div class="sv-goal">
                    <div class="sv-goal-bar">
                        <div class="sv-goal-fill" style="width:{{ min($goal, 100) }}%"></div>
                    </div>
                    <span class="sv-goal-text">{{ trans_choice('shop::messages.goal.progress', $goal) }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- Top customer --}}
    @if($topCustomer !== null)
        <div class="sv-panel">
            <div class="sv-panel-head">
                <i class="bi bi-star-fill me-2"></i> {{ trans('shop::messages.top.title') }}
            </div>
            <div class="sv-panel-body">
                <div class="sv-top">
                    <img src="{{ $topCustomer->user->getAvatar(40) }}" alt="{{ $topCustomer->user->name }}" class="sv-top-avatar">
                    <div>
                        <span class="sv-top-name">{{ $topCustomer->user->name }}</span>
                        @if($displaySidebarAmount)
                            <span class="sv-top-amount">{{ $topCustomer->formatPrice() }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Recent --}}
    @if($recentPayments !== null)
        <div class="sv-panel">
            <div class="sv-panel-head">
                <i class="bi bi-clock-history me-2"></i> {{ trans('shop::messages.recent.title') }}
            </div>
            <div class="sv-recent-list">
                @forelse($recentPayments as $payment)
                    <div class="sv-recent">
                        <img src="{{ $payment->user->getAvatar(32) }}" alt="{{ $payment->user->name }}" class="sv-recent-avatar">
                        <div class="sv-recent-info">
                            <span class="sv-recent-name">{{ $payment->user->name }}</span>
                            <span class="sv-recent-meta">
                                @if($displaySidebarAmount) {{ $payment->formatPrice() }} · @endif
                                {{ format_date($payment->created_at) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="sv-recent-empty">{{ trans('shop::messages.recent.empty') }}</div>
                @endforelse
            </div>
        </div>
    @endif
</div>
