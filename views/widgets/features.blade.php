@php
    $d = $widget['data'] ?? [];
    $sectionTitle = $d['title'] ?: trans('theme::messages.features.title');
@endphp
<section class="obsidian-section py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <div class="obsidian-section-divider mx-auto mb-3"></div>
            <h2 class="obsidian-reveal" data-field="title">{{ $sectionTitle }}</h2>
        </div>

        <div class="row g-4 justify-content-center">
            @for($i = 1; $i <= 3; $i++)
                <div class="col-md-4">
                    <div class="obsidian-feature-card p-4 h-100 obsidian-reveal">
                        <div class="obsidian-feature-icon mb-3">
                            <i class="{{ $d["feature_{$i}_icon"] ?? 'bi bi-star-fill' }}" data-field="feature_{{ $i }}_icon"></i>
                        </div>
                        <h5 class="fw-semibold mb-2" data-field="feature_{{ $i }}_title">
                            {{ $d["feature_{$i}_title"] ?: trans("theme::messages.features.f{$i}_title") }}
                        </h5>
                        <p class="mb-0" style="color: var(--obsidian-text-dim)" data-field="feature_{{ $i }}_desc">
                            {{ $d["feature_{$i}_desc"] ?: trans("theme::messages.features.f{$i}_desc") }}
                        </p>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
