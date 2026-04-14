{{-- Widget Catalog Modal --}}
<div class="modal fade" id="obsidianCatalogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content obsidian-modal">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>
                    {{ trans('theme::messages.editor.catalog.title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3" id="obsidian-catalog-grid">
                    @php
                        $widgetTypes = [
                            'hero' => ['icon' => 'bi-image-fill', 'color' => '#6366f1'],
                            'features' => ['icon' => 'bi-stars', 'color' => '#8b5cf6'],
                            'servers' => ['icon' => 'bi-hdd-stack-fill', 'color' => '#06b6d4'],
                            'news' => ['icon' => 'bi-newspaper', 'color' => '#10b981'],
                            'discord' => ['icon' => 'bi-discord', 'color' => '#5865F2'],
                            'cta' => ['icon' => 'bi-megaphone-fill', 'color' => '#f43f5e'],
                            'join' => ['icon' => 'bi-joystick', 'color' => '#22c55e'],
                            'youtube' => ['icon' => 'bi-youtube', 'color' => '#ff0000'],
                            'custom' => ['icon' => 'bi-code-slash', 'color' => '#64748b'],
                        ];
                    @endphp

                    @foreach($widgetTypes as $type => $meta)
                        <div class="col-4">
                            <button type="button" class="obsidian-catalog-item" data-widget-type="{{ $type }}">
                                <div class="obsidian-catalog-icon" style="background: {{ $meta['color'] }}20; color: {{ $meta['color'] }}">
                                    <i class="bi {{ $meta['icon'] }}"></i>
                                </div>
                                <span class="obsidian-catalog-label">
                                    {{ trans("theme::messages.editor.catalog.{$type}") }}
                                </span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
