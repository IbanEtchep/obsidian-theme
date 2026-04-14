@php
    $d = $widget['data'] ?? [];
    $s = $widget['settings'] ?? [];
    $heroTitle = $d['title'] ?: trans('theme::messages.hero.title', ['name' => site_name()]);
    $heroSubtitle = $d['subtitle'] ?: trans('theme::messages.hero.subtitle');
    $heroBtnText = $d['button_text'] ?: trans('theme::messages.hero.button');
    $heroBtnUrl = $d['button_url'] ?: '';
    $heroIp = $d['server_ip'] ?? ($server ? $server->fullAddress() : '');
    $heroLogo = $d['logo_url'] ?? '';
    $heroLogoSize = $d['logo_size'] ?? '120';
    $showLogo = $heroLogo ?: site_logo();
@endphp
<section class="obsidian-hero d-flex align-items-center justify-content-center text-center text-white position-relative overflow-hidden">
    @if(setting('background'))
        <div class="obsidian-hero-bg" style="background-image: url('{{ image_url(setting('background')) }}');"></div>
    @endif
    <div class="obsidian-hero-overlay"></div>

    <div class="container position-relative" style="z-index:2">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                @if($showLogo)
                    <div class="obsidian-hero-logo obsidian-fade-up" style="animation-delay:.05s; --obsidian-hero-logo-size: {{ intval($heroLogoSize) }}px">
                        <img src="{{ $heroLogo ?: site_logo() }}" alt="{{ site_name() }}">
                    </div>
                @endif

                <h1 class="mb-3 obsidian-fade-up" data-field="title" style="animation-delay:.15s">
                    {{ $heroTitle }}
                </h1>

                <p class="lead mb-4 obsidian-fade-up" style="animation-delay:.25s" data-field="subtitle">
                    {{ $heroSubtitle }}
                </p>

                {{-- Buttons row: CTA + IP copy --}}
                <div class="obsidian-hero-actions obsidian-fade-up" style="animation-delay:.35s">
                    @if($s['show_button'] ?? true)
                        @if($heroBtnUrl)
                            <a href="{{ $heroBtnUrl }}" class="btn btn-primary btn-lg obsidian-btn-glow" data-field="button_text">
                                {{ $heroBtnText }}
                            </a>
                        @elseif($server && $server->join_url)
                            <a href="{{ $server->join_url }}" class="btn btn-primary btn-lg obsidian-btn-glow">
                                {{ trans('messages.server.join') }}
                            </a>
                        @elseif(Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg obsidian-btn-glow" data-field="button_text">
                                {{ $heroBtnText }}
                            </a>
                        @endif
                    @endif

                    @if($heroIp)
                        <div class="obsidian-hero-ip" onclick="obsidianCopyHeroIp(this)">
                            <code id="obsidian-hero-ip-value">{{ $heroIp }}</code>
                            <span class="obsidian-hero-ip-copy" title="{{ trans('theme::messages.common.copy') }}">
                                <i class="bi bi-clipboard"></i>
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Player count --}}
                @if(($s['show_player_count'] ?? true) && $server && $server->isOnline())
                    <div class="obsidian-fade-up mt-4" style="animation-delay:.4s">
                        <span class="badge px-3 py-2">
                            <span class="obsidian-pulse-dot"></span>
                            {{ trans_choice('messages.server.online', $server->getOnlinePlayers()) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div style="position:absolute;bottom:0;left:0;right:0;height:120px;background:linear-gradient(transparent, var(--obsidian-dark));z-index:1;pointer-events:none"></div>
</section>

<script>
function obsidianCopyHeroIp(el) {
    var ip = document.getElementById('obsidian-hero-ip-value').textContent.trim();
    navigator.clipboard.writeText(ip).then(function() {
        var icon = el.querySelector('.obsidian-hero-ip-copy i');
        icon.className = 'bi bi-check-lg';
        el.classList.add('copied');
        setTimeout(function() {
            icon.className = 'bi bi-clipboard';
            el.classList.remove('copied');
        }, 2000);
    });
}
</script>
