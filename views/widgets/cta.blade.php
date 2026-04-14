@php
    $d = $widget['data'] ?? [];
    $ctaTitle = $d['title'] ?: trans('theme::messages.cta.title');
    $ctaSubtitle = $d['subtitle'] ?: trans('theme::messages.cta.subtitle');
    $ctaBtnText = $d['button_text'] ?: trans('theme::messages.cta.button');
    $ctaBtnUrl = $d['button_url'] ?: (Route::has('register') ? route('register') : '#');
@endphp
<section class="obsidian-cta text-center text-white py-5 position-relative overflow-hidden">
    <div class="container position-relative py-4" style="z-index:2">
        <div class="row justify-content-center">
            <div class="col-lg-7 obsidian-reveal">
                <div class="obsidian-section-divider mx-auto mb-3"></div>
                <h2 class="display-5 mb-3" data-field="title">{{ $ctaTitle }}</h2>
                <p class="lead mb-4" style="color:var(--obsidian-text-dim)" data-field="subtitle">{{ $ctaSubtitle }}</p>
                <a href="{{ $ctaBtnUrl }}" class="btn btn-light btn-lg px-5 obsidian-btn-glow" data-field="button_text">
                    {{ $ctaBtnText }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>
