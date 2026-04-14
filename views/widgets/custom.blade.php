@php
    $d = $widget['data'] ?? [];
    $customTitle = $d['title'] ?? trans('theme::messages.custom.title');
    $customContent = $d['content'] ?? trans('theme::messages.custom.content');
@endphp
<section class="obsidian-section py-5">
    <div class="container">
        <div class="obsidian-reveal">
            <div data-field="content">
                {!! $customContent !!}
            </div>
        </div>
    </div>
</section>
