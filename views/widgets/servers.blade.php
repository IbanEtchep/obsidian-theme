@php
    $d = $widget['data'] ?? [];
    $sectionTitle = $d['title'] ?: trans('theme::messages.servers.title');
@endphp
@if(!$servers->isEmpty())
<section class="obsidian-section obsidian-section-alt py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <div class="obsidian-section-divider mx-auto mb-3"></div>
            <h2 class="obsidian-reveal" data-field="title">{{ $sectionTitle }}</h2>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($servers as $srv)
                <div class="col-lg-4 col-md-6">
                    <div class="obsidian-server-card h-100 obsidian-reveal">
                        <div class="p-4 text-center">
                            <h4 class="mb-3">{{ $srv->name }}</h4>

                            @if($srv->isOnline())
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <span class="obsidian-pulse-dot me-2"></span>
                                    <span style="color:#22c55e" class="fw-medium">
                                        {{ trans_choice('messages.server.online', $srv->getOnlinePlayers()) }}
                                    </span>
                                </div>

                                <div class="obsidian-progress mb-2">
                                    <div class="obsidian-progress-bar" style="width: {{ $srv->getPlayersPercents() }}%"></div>
                                </div>

                                <p class="mb-3" style="color:var(--obsidian-text-dim)">
                                    {{ trans_choice('messages.server.total', $srv->getOnlinePlayers(), ['max' => $srv->getMaxPlayers()]) }}
                                </p>
                            @else
                                <div class="mb-3">
                                    <span class="badge" style="background:rgba(239,68,68,.1);color:#ef4444;border:1px solid rgba(239,68,68,.2)">
                                        {{ trans('messages.server.offline') }}
                                    </span>
                                </div>
                            @endif

                            @if($srv->join_url)
                                <a href="{{ $srv->join_url }}" class="btn btn-primary w-100">
                                    <i class="bi bi-play-fill me-1"></i> {{ trans('messages.server.join') }}
                                </a>
                            @else
                                <div class="obsidian-server-address">
                                    <code>{{ $srv->fullAddress() }}</code>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
