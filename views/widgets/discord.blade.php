@php
    $d = $widget['data'] ?? [];
    $discordId = $d['discord_id'] ?? '';
    $sectionTitle = $d['title'] ?: trans('theme::messages.discord.title');
    $sectionSubtitle = $d['subtitle'] ?: trans('theme::messages.discord.subtitle');
@endphp
@if($discordId)
<section class="obsidian-section obsidian-section-alt py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <div class="obsidian-reveal">
                    <h2 class="fw-bold mb-3" data-field="title">{{ $sectionTitle }}</h2>
                    <p class="text-body-secondary lead mb-4" data-field="subtitle">{{ $sectionSubtitle }}</p>
                    <a href="https://discord.gg/{{ $discordId }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg">
                        <i class="bi bi-discord"></i> Discord
                    </a>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="obsidian-reveal">
                    <iframe
                        src="https://discord.com/widget?id={{ $discordId }}&theme={{ dark_theme() ? 'dark' : 'light' }}"
                        width="100%" height="400" allowtransparency="true" frameborder="0"
                        sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"
                        class="rounded-4 shadow-sm" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@else
<section class="obsidian-section obsidian-section-alt py-5">
    <div class="container text-center">
        <div class="obsidian-reveal">
            <h2 class="fw-bold mb-3" data-field="title">{{ $sectionTitle }}</h2>
            <p class="text-body-secondary" data-field="subtitle">{{ $sectionSubtitle }}</p>
            <p class="text-muted"><i class="bi bi-info-circle"></i> Configurez l'ID du serveur Discord dans l'éditeur.</p>
        </div>
    </div>
</section>
@endif
