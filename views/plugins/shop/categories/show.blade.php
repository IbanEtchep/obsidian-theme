@extends('layouts.app')

@section('title', $category->name)

@push('footer-scripts')
    <script>
        document.querySelectorAll('[data-package-url]').forEach(function (el) {
            el.addEventListener('click', function (ev) {
                ev.preventDefault();
                axios.get(el.dataset['packageUrl']).then(function (response) {
                    const itemModal = document.getElementById('itemModal');
                    itemModal.innerHTML = response.data;
                    new bootstrap.Modal(itemModal).show();
                }).catch(function (error) {
                    createAlert('danger', error, true);
                });
            });
        });
    </script>
@endpush

@section('content')
    <div class="sv-layout" id="shop">
        <aside class="sv-aside">
            @include('shop::categories._sidebar')
        </aside>

        <main class="sv-main">
            <div class="sv-main-head">
                <h1 class="sv-title">{{ $category->name }}</h1>
            </div>

            @if($category->description)
                <div class="sv-desc">
                    {!! $category->description !!}
                </div>
            @endif

            <div class="sv-grid">
                @forelse($category->packages as $package)
                    <a href="#" class="sv-item" data-package-url="{{ route('shop.packages.show', $package) }}">
                        <div class="sv-item-img">
                            @if($package->hasImage())
                                <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}">
                            @else
                                <div class="sv-item-placeholder"><i class="bi bi-box-seam"></i></div>
                            @endif
                            @if($package->isDiscounted())
                                <span class="sv-item-badge">{{ strtoupper(trans('theme::messages.common.promo')) }}</span>
                            @endif
                        </div>
                        <div class="sv-item-body">
                            <h4 class="sv-item-name">{{ $package->name }}</h4>
                            <div class="sv-item-price">
                                @if($package->isDiscounted())
                                    <del class="sv-item-old">{{ shop_format_amount($package->getOriginalPrice()) }}</del>
                                @endif
                                <span class="sv-item-current">{{ shop_format_amount($package->getPrice()) }}</span>
                            </div>
                        </div>
                        <div class="sv-item-cta">
                            <span><i class="bi bi-cart-plus me-1"></i> {{ trans('shop::messages.buy') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="sv-empty">
                        <i class="bi bi-bag-x"></i>
                        <p>{{ trans('shop::messages.categories.empty') }}</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true"></div>
@endsection

@push('styles')
    @include('plugins.shop.categories._shop-styles')
@endpush
