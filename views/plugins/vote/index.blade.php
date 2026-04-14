@extends('layouts.app')

@php
    $isAdmin = Auth::check() && auth()->user()->isAdmin();

    $defaultVoteSections = [
        ['id' => 'vpanel-1', 'type' => 'vote/panel', 'zone' => 'sidebar', 'order' => 0, 'visible' => true, 'data' => ['title' => ''], 'settings' => []],
        ['id' => 'vgoal-1', 'type' => 'vote/goal', 'zone' => 'sidebar', 'order' => 1, 'visible' => true, 'data' => ['title' => ''], 'settings' => []],
        ['id' => 'vrewards-1', 'type' => 'vote/rewards', 'zone' => 'sidebar', 'order' => 2, 'visible' => true, 'data' => ['title' => ''], 'settings' => []],
        ['id' => 'vsteps-h-1', 'type' => 'vote/steps-h', 'zone' => 'main', 'order' => 0, 'visible' => true, 'data' => ['title' => ''], 'settings' => []],
        ['id' => 'vleaderboard-1', 'type' => 'vote/leaderboard', 'zone' => 'main', 'order' => 1, 'visible' => true, 'data' => ['title' => ''], 'settings' => []],
    ];

    $voteLayout = theme_config('vote_layout') ?? 'sidebar-left';
    $voteSections = theme_config('vote_sections') ?? $defaultVoteSections;
    $sidebarWidgets = collect($voteSections)->where('zone', 'sidebar')->sortBy('order')->values();
    $mainWidgets = collect($voteSections)->where('zone', 'main')->sortBy('order')->values();

    $voteConfig = [
        'colors' => theme_config('colors') ?? [],
        'sections' => collect($voteSections)->values()->toArray(),
        'layout' => $voteLayout,
    ];
@endphp

@section('title', trans('vote::messages.title'))

@section('content')
    @php
        $layoutClasses = [
            'full' => '',
            'sidebar-left' => 'obsidian-layout-sl',
            'sidebar-right' => 'obsidian-layout-sr',
        ];
    @endphp

    <div id="obsidian-sections" class="obsidian-zone-layout {{ $layoutClasses[$voteLayout] ?? '' }}" data-layout="{{ $voteLayout }}" @if($isAdmin) data-config='@json($voteConfig)' @endif>
        @if($voteLayout === 'full')
            <div class="obsidian-zone" data-zone="main" id="obsidian-zone-main">
                @foreach(collect($voteSections)->sortBy('order')->values() as $section)
                    @if($isAdmin || ($section['visible'] ?? true))
                        <div class="obsidian-widget obsidian-vote-widget mb-3 @if(!($section['visible'] ?? true)) obsidian-widget-hidden @endif"
                             data-widget-id="{{ $section['id'] }}" data-widget-type="{{ $section['type'] }}" data-zone="{{ $section['zone'] ?? 'main' }}">
                            @include('widgets.' . $section['type'], ['widget' => $section])
                        </div>
                    @endif
                @endforeach

                @if($isAdmin)
                    <div class="obsidian-add-zone" data-after="" data-zone="main">
                        <button type="button" class="obsidian-add-btn"><i class="bi bi-plus-lg"></i></button>
                    </div>
                @endif
            </div>
        @else
            @php
                $sidebarZone = $voteLayout === 'sidebar-left' ? 'first' : 'last';
            @endphp
            <div class="obsidian-zone obsidian-zone-sidebar" data-zone="sidebar" id="obsidian-zone-sidebar" style="order:{{ $sidebarZone === 'first' ? 0 : 2 }}">
                @foreach($sidebarWidgets as $section)
                    @if($isAdmin || ($section['visible'] ?? true))
                        <div class="obsidian-widget obsidian-vote-widget mb-3 @if(!($section['visible'] ?? true)) obsidian-widget-hidden @endif"
                             data-widget-id="{{ $section['id'] }}" data-widget-type="{{ $section['type'] }}" data-zone="sidebar">
                            @include('widgets.' . $section['type'], ['widget' => $section])
                        </div>
                    @endif
                @endforeach

                @if($isAdmin)
                    <div class="obsidian-add-zone" data-after="" data-zone="sidebar">
                        <button type="button" class="obsidian-add-btn"><i class="bi bi-plus-lg"></i></button>
                    </div>
                @endif
            </div>

            <div class="obsidian-zone obsidian-zone-main" data-zone="main" id="obsidian-zone-main" style="order:1">
                @foreach($mainWidgets as $section)
                    @if($isAdmin || ($section['visible'] ?? true))
                        <div class="obsidian-widget obsidian-vote-widget mb-3 @if(!($section['visible'] ?? true)) obsidian-widget-hidden @endif"
                             data-widget-id="{{ $section['id'] }}" data-widget-type="{{ $section['type'] }}" data-zone="main">
                            @include('widgets.' . $section['type'], ['widget' => $section])
                        </div>
                    @endif
                @endforeach

                @if($isAdmin)
                    <div class="obsidian-add-zone" data-after="" data-zone="main">
                        <button type="button" class="obsidian-add-btn"><i class="bi bi-plus-lg"></i></button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if($isAdmin)
        @include('editor.sidebar')
        @include('editor.widget-modal')

        <div class="modal fade" id="obsidianCatalogModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content obsidian-modal">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Ajouter un widget</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3" id="obsidian-catalog-grid">
                            @php
                                $voteWidgetTypes = [
                                    'vote/panel' => ['icon' => 'bi-star-fill', 'color' => '#6366f1', 'label' => 'Voter'],
                                    'vote/steps-h' => ['icon' => 'bi-calendar-check-fill', 'color' => '#fbbf24', 'label' => 'Paliers (horizontal)'],
                                    'vote/steps-v' => ['icon' => 'bi-calendar-check-fill', 'color' => '#f59e0b', 'label' => 'Paliers (vertical)'],
                                    'vote/leaderboard' => ['icon' => 'bi-trophy-fill', 'color' => '#22c55e', 'label' => 'Classement'],
                                    'vote/goal' => ['icon' => 'bi-bullseye', 'color' => '#06b6d4', 'label' => 'Objectif'],
                                    'vote/rewards' => ['icon' => 'bi-gift-fill', 'color' => '#ec4899', 'label' => 'Récompenses'],
                                ];
                            @endphp
                            @foreach($voteWidgetTypes as $type => $meta)
                                <div class="col-4">
                                    <button type="button" class="obsidian-catalog-item" data-widget-type="{{ $type }}">
                                        <div class="obsidian-catalog-icon" style="background:{{ $meta['color'] }}20;color:{{ $meta['color'] }}"><i class="bi {{ $meta['icon'] }}"></i></div>
                                        <span class="obsidian-catalog-label">{{ $meta['label'] }}</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" id="obsidian-editor-toggle" class="obsidian-editor-toggle"><i class="bi bi-pencil-square"></i></button>
        <div id="obsidian-submit-url" class="d-none">{{ route('admin.themes.config', 'obsidian') }}</div>

        @php
            $voteWidgetDefaults = [
                'vote/panel' => ['title' => ''],
                'vote/steps-h' => ['title' => ''],
                'vote/steps-v' => ['title' => ''],
                'vote/leaderboard' => ['title' => ''],
                'vote/goal' => ['title' => ''],
                'vote/rewards' => ['title' => ''],
            ];
        @endphp
        <script>
            window.obsidianConfig = @json($voteConfig);
            window.obsidianWidgetDefaults = @json($voteWidgetDefaults);
            window.obsidianConfigKey = 'vote_sections';
            @php
                $voteI18n = [
                    'fields' => trans('theme::messages.fields'),
                    'widgets' => trans('theme::messages.widgets'),
                    'delete_confirm' => trans('theme::messages.editor.delete_confirm'),
                ];
            @endphp
            window.obsidianI18n = @json($voteI18n);
            window.obsidianHasZones = true;
        </script>
        <script src="{{ theme_asset('js/vendor/Sortable.min.js') }}" defer></script>
        <script src="{{ theme_asset('js/editor.js') }}" defer></script>
        <link href="{{ theme_asset('css/editor.css') }}" rel="stylesheet">
    @endif
@endsection

@push('scripts')
    @if($ipv6compatibility)
        <script src="https://ipv6-adapter.com/api/v1/api.js" async defer></script>
    @endif
    <script src="{{ plugin_asset('vote', 'js/vote.js?v3') }}" defer></script>
    @auth <script>window.username = '{{ $user->name }}';</script> @endauth
@endpush

@push('styles')
<style>
    /* Zone layout */
    .obsidian-zone-layout { display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .obsidian-zone-layout.obsidian-layout-sl .obsidian-zone-sidebar,
    .obsidian-zone-layout.obsidian-layout-sr .obsidian-zone-sidebar { flex: 0 0 340px; max-width: 340px; }
    .obsidian-zone-layout .obsidian-zone-main { flex: 1; min-width: 0; }
    .obsidian-zone-layout:not(.obsidian-layout-sl):not(.obsidian-layout-sr) .obsidian-zone { flex: 1 1 100%; }

    /* Sticky sidebar — desktop only */
    @media (min-width: 992px) {
        .obsidian-zone-sidebar { position: sticky; top: 90px; align-self: flex-start; }
    }

    /* Zone drop area in edit mode */
    .obsidian-editing-mode .obsidian-zone {
        min-height: 100px;
        border: 2px dashed transparent;
        border-radius: var(--obsidian-radius);
        padding: .25rem;
        transition: border-color .2s;
    }
    .obsidian-editing-mode .obsidian-zone:empty,
    .obsidian-editing-mode .obsidian-zone:has(.obsidian-add-zone:only-child) {
        border-color: rgba(var(--obsidian-primary-rgb),.15);
    }

    @media (max-width: 991.98px) {
        .obsidian-zone-layout { flex-direction: column; }
        .obsidian-zone-layout.obsidian-layout-sl .obsidian-zone-sidebar,
        .obsidian-zone-layout.obsidian-layout-sr .obsidian-zone-sidebar {
            flex: 1 1 100%;
            max-width: 100%;
            position: static;
        }
        .obsidian-zone-sidebar { position: static !important; }
    }

    /* Vote card styles */
    .obsidian-vote-card { background: var(--obsidian-surface); border: 1px solid var(--obsidian-border); border-radius: var(--obsidian-radius); overflow: hidden; }
    .obsidian-vote-header { padding: .85rem 1.25rem; font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .95rem; text-transform: uppercase; letter-spacing: .04em; color: #fff; background: rgba(var(--obsidian-primary-rgb),.06); border-bottom: 1px solid var(--obsidian-border); display: flex; align-items: center; }
    .obsidian-vote-body { padding: 1.25rem; }
    .obsidian-vote-count { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 1.8rem; color: var(--obsidian-primary); line-height: 1; }
    .obsidian-vote-site-btn { display: flex; align-items: center; justify-content: space-between; padding: .7rem 1rem; background: var(--obsidian-surface-2); border: 1px solid var(--obsidian-border); border-radius: .5rem; color: #fff; font-family: 'Rajdhani', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; transition: all .2s; }
    .obsidian-vote-site-btn:hover { color: #fff; background: rgba(var(--obsidian-primary-rgb),.1); border-color: rgba(var(--obsidian-primary-rgb),.3); transform: translateX(4px); }
    .obsidian-vote-site-btn .vote-timer:not(:empty) { background: rgba(var(--obsidian-primary-rgb),.15); color: var(--obsidian-primary); font-size: .75rem; padding: .25rem .6rem; border-radius: .25rem; }
    .obsidian-vote-progress { position: relative; height: 28px; background: var(--obsidian-surface-2); border-radius: .5rem; overflow: hidden; }
    .obsidian-vote-progress-bar { height: 100%; background: linear-gradient(90deg, var(--obsidian-primary), var(--obsidian-accent)); border-radius: .5rem; transition: width .6s cubic-bezier(.4,0,.2,1); }
    .obsidian-vote-progress-text { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: .8rem; color: #fff; }
    .obsidian-reward-row { display: flex; align-items: center; justify-content: space-between; padding: .5rem .75rem; background: var(--obsidian-surface-2); border-radius: .5rem; font-size: .9rem; }
    .obsidian-reward-img { width: 32px; height: 32px; object-fit: contain; border-radius: .25rem; }
    .obsidian-reward-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: rgba(var(--obsidian-primary-rgb),.1); border-radius: .25rem; color: var(--obsidian-primary); }
    .obsidian-reward-chance { font-family: 'Rajdhani', sans-serif; font-weight: 700; color: var(--obsidian-primary); }
    .obsidian-vote-rank { display: flex; align-items: center; gap: .75rem; padding: .75rem 1.25rem; border-bottom: 1px solid var(--obsidian-border); transition: background .15s; }
    .obsidian-vote-rank:last-child { border-bottom: none; }
    .obsidian-vote-rank:hover { background: rgba(var(--obsidian-primary-rgb),.03); }
    .obsidian-vote-rank-top { background: rgba(var(--obsidian-primary-rgb),.03); }
    .obsidian-vote-rank-pos { width: 36px; text-align: center; font-family: 'Rajdhani', sans-serif; font-weight: 700; color: var(--obsidian-text-dim); flex-shrink: 0; }
    .obsidian-vote-rank-pos i { font-size: 1.1rem; }
    .obsidian-vote-rank-avatar { width: 36px; height: 36px; border-radius: .5rem; object-fit: cover; flex-shrink: 0; }
    .obsidian-vote-rank-name { flex: 1; font-weight: 600; color: #fff; }
    .obsidian-vote-rank-votes { font-family: 'Rajdhani', sans-serif; font-weight: 700; font-size: 1.1rem; color: var(--obsidian-primary); flex-shrink: 0; }
    .obsidian-vote-rank-votes::after { content: ' votes'; font-size: .7rem; font-weight: 600; color: var(--obsidian-text-dim); text-transform: uppercase; }
    #vote-card .spinner-parent { display: none; }
    #vote-card.voting .spinner-parent { position: absolute; display: flex; align-items: center; justify-content: center; inset: 0; background: rgba(14,18,33,.7); backdrop-filter: blur(6px); z-index: 10; border-radius: var(--obsidian-radius); }
</style>
@endpush
