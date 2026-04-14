@php
    $footerLinks = theme_config('footer.links') ?? [];
    $legalLinks = theme_config('footer.legal_links') ?? [];
@endphp
<div class="modal fade" id="obsidianFooterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content obsidian-modal">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-layout-text-window-reverse me-2"></i> Modifier le footer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="obsidian-footer-description" rows="2" placeholder="{{ trans('theme::messages.footer.description') }}">{{ theme_config('footer.description') ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Titre colonne Liens</label>
                        <input type="text" class="form-control" id="obsidian-footer-links-title" value="{{ theme_config('footer.links_title') ?? '' }}" placeholder="{{ trans('theme::messages.footer.links') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Titre colonne Réseaux</label>
                        <input type="text" class="form-control" id="obsidian-footer-social-title" value="{{ theme_config('footer.social_title') ?? '' }}" placeholder="{{ trans('theme::messages.footer.social') }}">
                    </div>

                    {{-- Custom links --}}
                    <div class="col-12">
                        <hr class="my-2">
                        <label class="form-label fw-semibold">Liens rapides</label>
                        <div id="obsidian-footer-links-list">
                            @foreach($footerLinks as $link)
                                <div class="obsidian-footer-link-row d-flex gap-2 mb-2">
                                    <input type="text" class="form-control form-control-sm" data-link-name value="{{ $link['name'] ?? '' }}" placeholder="Nom">
                                    <input type="text" class="form-control form-control-sm" data-link-url value="{{ $link['url'] ?? '' }}" placeholder="URL (ex: /boutique)">
                                    <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest('.obsidian-footer-link-row').remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-light" id="obsidian-footer-add-link">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter un lien
                        </button>
                    </div>

                    {{-- Legal links --}}
                    <div class="col-12">
                        <hr class="my-2">
                        <div class="d-flex gap-3 align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">Liens légaux</label>
                            <input type="text" class="form-control form-control-sm" style="max-width:200px" id="obsidian-footer-legal-title" value="{{ theme_config('footer.legal_title') ?? '' }}" placeholder="Titre (ex: Légal)">
                        </div>
                        <div id="obsidian-footer-legal-list">
                            @foreach($legalLinks as $link)
                                <div class="obsidian-footer-legal-row d-flex gap-2 mb-2">
                                    <input type="text" class="form-control form-control-sm" data-legal-name value="{{ $link['name'] ?? '' }}" placeholder="Nom">
                                    <input type="text" class="form-control form-control-sm" data-legal-url value="{{ $link['url'] ?? '' }}" placeholder="URL">
                                    <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest('.obsidian-footer-legal-row').remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-light" id="obsidian-footer-add-legal">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter un lien légal
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-sm btn-primary" id="obsidian-footer-apply">
                    <i class="bi bi-check-lg me-1"></i> Appliquer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('obsidian-footer-add-link').addEventListener('click', function () {
    var row = document.createElement('div');
    row.className = 'obsidian-footer-link-row d-flex gap-2 mb-2';
    row.innerHTML =
        '<input type="text" class="form-control form-control-sm" data-link-name placeholder="Nom">' +
        '<input type="text" class="form-control form-control-sm" data-link-url placeholder="URL (ex: /boutique)">' +
        '<button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest(\'.obsidian-footer-link-row\').remove()"><i class="bi bi-trash"></i></button>';
    document.getElementById('obsidian-footer-links-list').appendChild(row);
});

document.getElementById('obsidian-footer-add-legal').addEventListener('click', function () {
    var row = document.createElement('div');
    row.className = 'obsidian-footer-legal-row d-flex gap-2 mb-2';
    row.innerHTML =
        '<input type="text" class="form-control form-control-sm" data-legal-name placeholder="Nom">' +
        '<input type="text" class="form-control form-control-sm" data-legal-url placeholder="URL">' +
        '<button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="this.closest(\'.obsidian-footer-legal-row\').remove()"><i class="bi bi-trash"></i></button>';
    document.getElementById('obsidian-footer-legal-list').appendChild(row);
});
</script>
