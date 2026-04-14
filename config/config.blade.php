<div class="text-center py-5">
    <i class="bi bi-pencil-square display-4 text-primary mb-3 d-block"></i>
    <h4 class="fw-bold">{{ trans('theme::messages.config_page.title') }}</h4>
    <p class="text-muted mb-4" style="max-width:500px;margin:0 auto">
        {!! trans('theme::messages.config_page.description') !!}
    </p>
    <a href="{{ url('/') }}?editor=true" class="btn btn-primary btn-lg" target="_blank">
        <i class="bi bi-pencil-square me-2"></i> {{ trans('theme::messages.config_page.open_editor') }}
    </a>
</div>
