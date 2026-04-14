@extends('layouts.base')

@php
    $isAdmin = Auth::check() && auth()->user()->isAdmin();
    $themeConfig = theme_config('sections') ? ['colors' => theme_config('colors') ?? [], 'sections' => theme_config('sections') ?? []] : json_decode(file_get_contents(theme_path('config.json', 'obsidian')), true);
    $sections = collect($themeConfig['sections'] ?? [])->sortBy('order')->values();
@endphp

@section('title', trans('messages.home'))

@section('app')
    <div id="obsidian-sections" @if($isAdmin) data-config='@json($themeConfig)' @endif>
        @foreach($sections as $section)
            @if($isAdmin || ($section['visible'] ?? true))
                <div class="obsidian-widget @if(!($section['visible'] ?? true)) obsidian-widget-hidden @endif"
                     @if(!empty($section['data']['anchor'])) id="{{ $section['data']['anchor'] }}" @endif
                     data-widget-id="{{ $section['id'] }}"
                     data-widget-type="{{ $section['type'] }}">
                    @include('widgets.' . $section['type'], ['widget' => $section])
                </div>

                @if($isAdmin)
                    <div class="obsidian-add-zone" data-after="{{ $section['id'] }}">
                        <button type="button" class="obsidian-add-btn" title="{{ trans('theme::messages.editor.add_widget') }}">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                @endif
            @endif
        @endforeach

        @if($isAdmin && $sections->isEmpty())
            <div class="obsidian-add-zone obsidian-add-zone-empty" data-after="">
                <button type="button" class="obsidian-add-btn" title="{{ trans('theme::messages.editor.add_widget') }}">
                    <i class="bi bi-plus-lg"></i> {{ trans('theme::messages.editor.add_widget') }}
                </button>
            </div>
        @endif
    </div>

    @if($message)
        <div class="container mt-4">
            <div class="alert alert-info">{{ $message }}</div>
        </div>
    @endif

    @include('elements.session-alerts')

    @if($isAdmin)
        @include('editor.sidebar')
        @include('editor.widget-modal')
        @include('editor.catalog-modal')

        <button type="button" id="obsidian-editor-toggle" class="obsidian-editor-toggle" title="{{ trans('theme::messages.editor.edit') }}">
            <i class="bi bi-pencil-square"></i>
        </button>

        <div id="obsidian-submit-url" class="d-none">{{ route('admin.themes.config', 'obsidian') }}</div>

        @php
            $widgetDefaults = [
                'hero' => ['anchor' => '', 'logo_url' => '', 'logo_size' => '120', 'title' => '', 'subtitle' => '', 'button_text' => '', 'button_url' => '', 'server_ip' => ''],
                'features' => ['anchor' => '', 'title' => '', 'feature_1_icon' => 'bi bi-star-fill', 'feature_1_title' => '', 'feature_1_desc' => '', 'feature_2_icon' => 'bi bi-star-fill', 'feature_2_title' => '', 'feature_2_desc' => '', 'feature_3_icon' => 'bi bi-star-fill', 'feature_3_title' => '', 'feature_3_desc' => ''],
                'servers' => ['anchor' => '', 'title' => ''],
                'news' => ['anchor' => '', 'title' => ''],
                'discord' => ['anchor' => '', 'title' => '', 'subtitle' => '', 'discord_id' => ''],
                'cta' => ['anchor' => '', 'title' => '', 'subtitle' => '', 'button_text' => '', 'button_url' => ''],
                'join' => ['anchor' => '', 'title' => '', 'step_1' => '', 'step_2' => '', 'step_3' => '', 'server_ip' => ''],
                'youtube' => ['anchor' => '', 'title' => '', 'subtitle' => '', 'video_id' => ''],
                'custom' => ['anchor' => '', 'title' => '', 'content' => '<p>Nouveau contenu</p>'],
            ];
        @endphp
        <script>window.obsidianConfig = @json($themeConfig);</script>
        <script>window.obsidianWidgetDefaults = @json($widgetDefaults);</script>
        <script src="{{ theme_asset('js/vendor/Sortable.min.js') }}" defer></script>
        <script src="{{ theme_asset('js/editor.js') }}" defer></script>
        <link href="{{ theme_asset('css/editor.css') }}" rel="stylesheet">
    @endif
@endsection
