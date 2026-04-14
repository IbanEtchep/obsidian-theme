{{-- Editor Sidebar: colors + save/quit --}}
<div id="obsidian-sidebar" class="obsidian-sidebar d-none">
    <div class="obsidian-sidebar-header">
        <i class="bi bi-palette-fill me-2"></i> {{ trans('theme::messages.editor.colors') }}
    </div>

    <div class="obsidian-sidebar-body">
        @php
            $colorFields = [
                'primary' => trans('theme::messages.editor.primary'),
                'secondary' => trans('theme::messages.editor.secondary'),
                'accent' => trans('theme::messages.editor.accent'),
                'navbar' => trans('theme::messages.editor.navbar_color'),
                'footer' => trans('theme::messages.editor.footer_color'),
                'hero_start' => trans('theme::messages.editor.hero_start'),
                'hero_end' => trans('theme::messages.editor.hero_end'),
            ];
        @endphp

        @foreach($colorFields as $key => $label)
            <div class="obsidian-color-row">
                <label class="obsidian-color-label">{{ $label }}</label>
                <input type="color" class="obsidian-color-input" data-color-key="{{ $key }}"
                       value="{{ theme_config("colors.{$key}") ?? '#6366f1' }}">
            </div>
        @endforeach

        {{-- Layout selector (only on zone pages) --}}
        <div id="obsidian-layout-section" class="d-none">
            <hr class="my-3 border-white border-opacity-10">
            <div class="obsidian-sidebar-header mb-2">
                <i class="bi bi-layout-split me-2"></i> Layout
            </div>
            <div class="obsidian-layout-picker">
                <button type="button" class="obsidian-layout-opt" data-layout="sidebar-left" title="Sidebar gauche">
                    <div class="obsidian-layout-mini">
                        <div class="obsidian-layout-mini-sb"></div>
                        <div class="obsidian-layout-mini-main"></div>
                    </div>
                </button>
                <button type="button" class="obsidian-layout-opt" data-layout="full" title="Pleine largeur">
                    <div class="obsidian-layout-mini">
                        <div class="obsidian-layout-mini-full"></div>
                    </div>
                </button>
                <button type="button" class="obsidian-layout-opt" data-layout="sidebar-right" title="Sidebar droite">
                    <div class="obsidian-layout-mini">
                        <div class="obsidian-layout-mini-main"></div>
                        <div class="obsidian-layout-mini-sb"></div>
                    </div>
                </button>
            </div>
        </div>

        <hr class="my-3 border-white border-opacity-10">

        <div class="obsidian-sidebar-header mb-2">
            <i class="bi bi-swatchbook me-2"></i> {{ trans('theme::messages.editor.presets') }}
        </div>

        <div class="obsidian-presets">
            @php
                $presets = [
                    ['primary' => '#6366f1', 'secondary' => '#8b5cf6', 'accent' => '#06b6d4', 'navbar' => '#0f172a', 'footer' => '#0f172a', 'hero_start' => '#1e1b4b', 'hero_end' => '#312e81'],
                    ['primary' => '#f43f5e', 'secondary' => '#e11d48', 'accent' => '#fb923c', 'navbar' => '#1c1917', 'footer' => '#1c1917', 'hero_start' => '#450a0a', 'hero_end' => '#7f1d1d'],
                    ['primary' => '#10b981', 'secondary' => '#059669', 'accent' => '#34d399', 'navbar' => '#022c22', 'footer' => '#022c22', 'hero_start' => '#022c22', 'hero_end' => '#064e3b'],
                    ['primary' => '#3b82f6', 'secondary' => '#2563eb', 'accent' => '#60a5fa', 'navbar' => '#0f172a', 'footer' => '#0f172a', 'hero_start' => '#0c1629', 'hero_end' => '#1e3a5f'],
                    ['primary' => '#f59e0b', 'secondary' => '#d97706', 'accent' => '#fbbf24', 'navbar' => '#1c1917', 'footer' => '#1c1917', 'hero_start' => '#1c1917', 'hero_end' => '#451a03'],
                    ['primary' => '#ec4899', 'secondary' => '#db2777', 'accent' => '#f472b6', 'navbar' => '#1a1a2e', 'footer' => '#1a1a2e', 'hero_start' => '#1a1a2e', 'hero_end' => '#16213e'],
                ];
            @endphp

            @foreach($presets as $index => $preset)
                <button type="button" class="obsidian-preset-btn" data-preset='@json($preset)'>
                    @foreach(['primary', 'secondary', 'accent', 'hero_start'] as $c)
                        <span class="obsidian-preset-dot" style="background:{{ $preset[$c] }}"></span>
                    @endforeach
                </button>
            @endforeach
        </div>
    </div>

    <div class="obsidian-sidebar-footer">
        <button type="button" id="obsidian-save" class="btn btn-success btn-sm w-100 fw-semibold" disabled>
            <span class="obsidian-save-text"><i class="bi bi-check-lg me-1"></i> {{ trans('theme::messages.editor.save') }}</span>
            <span class="obsidian-save-loading d-none"><span class="spinner-border spinner-border-sm me-1"></span> {{ trans('theme::messages.editor.saving') }}</span>
            <span class="obsidian-save-done d-none"><i class="bi bi-check-circle-fill me-1"></i> {{ trans('theme::messages.editor.saved') }}</span>
        </button>
        <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm w-100 mt-2">
            <i class="bi bi-x-lg me-1"></i> {{ trans('theme::messages.editor.quit') }}
        </a>
    </div>
</div>
