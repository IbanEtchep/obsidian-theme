{{-- Widget Edit Modal --}}
<div class="modal fade" id="obsidianWidgetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content obsidian-modal">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>
                    <span id="obsidian-modal-title">{{ trans('theme::messages.editor.edit_widget') }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="obsidian-modal-body">
                {{-- Populated dynamically by editor.js --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ trans('theme::messages.common.cancel') }}</button>
                <button type="button" class="btn btn-sm btn-primary" id="obsidian-modal-apply">
                    <i class="bi bi-check-lg me-1"></i> {{ trans('theme::messages.common.apply') }}
                </button>
            </div>
        </div>
    </div>
</div>
