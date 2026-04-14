@php
    $footerLinks = theme_config('footer.links') ?? [];
    $legalLinks = theme_config('footer.legal_links') ?? [];
    $t = [
        'name' => trans('theme::messages.footer_modal.name'),
        'url' => trans('theme::messages.footer_modal.url'),
        'url_example' => trans('theme::messages.footer_modal.url_example'),
    ];
@endphp
<div class="modal fade" id="obsidianFooterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content obsidian-modal">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-layout-text-window-reverse me-2"></i> {{ trans('theme::messages.editor.edit_widget') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ trans('theme::messages.footer_modal.description') }}</label>
                        <textarea class="form-control" id="obsidian-footer-description" rows="2" placeholder="{{ trans('theme::messages.footer.description') }}">{{ theme_config('footer.description') ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ trans('theme::messages.footer_modal.links_col_title') }}</label>
                        <input type="text" class="form-control" id="obsidian-footer-links-title" value="{{ theme_config('footer.links_title') ?? '' }}" placeholder="{{ trans('theme::messages.footer.links') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ trans('theme::messages.footer_modal.social_col_title') }}</label>
                        <input type="text" class="form-control" id="obsidian-footer-social-title" value="{{ theme_config('footer.social_title') ?? '' }}" placeholder="{{ trans('theme::messages.footer.social') }}">
                    </div>

                    {{-- Custom links --}}
                    <div class="col-12">
                        <hr class="my-2">
                        <label class="form-label fw-semibold">{{ trans('theme::messages.footer_modal.quick_links') }}</label>
                        <div id="obsidian-footer-links-list">
                            @foreach($footerLinks as $link)
                                <div class="obsidian-footer-link-row d-flex gap-2 mb-2">
                                    <input type="text" class="form-control form-control-sm" data-link-name value="{{ $link['name'] ?? '' }}" placeholder="{{ $t['name'] }}">
                                    <input type="text" class="form-control form-control-sm" data-link-url value="{{ $link['url'] ?? '' }}" placeholder="{{ $t['url_example'] }}">
                                    <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest('.obsidian-footer-link-row').remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-light" id="obsidian-footer-add-link">
                            <i class="bi bi-plus-lg me-1"></i> {{ trans('theme::messages.common.add_link') }}
                        </button>
                    </div>

                    {{-- Legal links --}}
                    <div class="col-12">
                        <hr class="my-2">
                        <div class="d-flex gap-3 align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">{{ trans('theme::messages.footer_modal.legal_links') }}</label>
                            <input type="text" class="form-control form-control-sm" style="max-width:200px" id="obsidian-footer-legal-title" value="{{ theme_config('footer.legal_title') ?? '' }}" placeholder="{{ trans('theme::messages.footer_modal.title_example_legal') }}">
                        </div>
                        <div id="obsidian-footer-legal-list">
                            @foreach($legalLinks as $link)
                                <div class="obsidian-footer-legal-row d-flex gap-2 mb-2">
                                    <input type="text" class="form-control form-control-sm" data-legal-name value="{{ $link['name'] ?? '' }}" placeholder="{{ $t['name'] }}">
                                    <input type="text" class="form-control form-control-sm" data-legal-url value="{{ $link['url'] ?? '' }}" placeholder="{{ $t['url'] }}">
                                    <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest('.obsidian-footer-legal-row').remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-light" id="obsidian-footer-add-legal">
                            <i class="bi bi-plus-lg me-1"></i> {{ trans('theme::messages.common.add_legal_link') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ trans('theme::messages.common.cancel') }}</button>
                <button type="button" class="btn btn-sm btn-primary" id="obsidian-footer-apply">
                    <i class="bi bi-check-lg me-1"></i> {{ trans('theme::messages.common.apply') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var tName = @json($t['name']);
    var tUrl = @json($t['url']);
    var tUrlEx = @json($t['url_example']);

    document.getElementById('obsidian-footer-add-link').addEventListener('click', function () {
        var row = document.createElement('div');
        row.className = 'obsidian-footer-link-row d-flex gap-2 mb-2';
        row.innerHTML =
            '<input type="text" class="form-control form-control-sm" data-link-name placeholder="' + tName + '">' +
            '<input type="text" class="form-control form-control-sm" data-link-url placeholder="' + tUrlEx + '">' +
            '<button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest(\'.obsidian-footer-link-row\').remove()"><i class="bi bi-trash"></i></button>';
        document.getElementById('obsidian-footer-links-list').appendChild(row);
    });

    document.getElementById('obsidian-footer-add-legal').addEventListener('click', function () {
        var row = document.createElement('div');
        row.className = 'obsidian-footer-legal-row d-flex gap-2 mb-2';
        row.innerHTML =
            '<input type="text" class="form-control form-control-sm" data-legal-name placeholder="' + tName + '">' +
            '<input type="text" class="form-control form-control-sm" data-legal-url placeholder="' + tUrl + '">' +
            '<button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest(\'.obsidian-footer-legal-row\').remove()"><i class="bi bi-trash"></i></button>';
        document.getElementById('obsidian-footer-legal-list').appendChild(row);
    });
})();
</script>
