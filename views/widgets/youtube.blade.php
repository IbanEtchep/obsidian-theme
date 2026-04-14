@php
    $d = $widget['data'] ?? [];
    $title = $d['title'] ?: trans('theme::messages.youtube.title');
    $videoId = $d['video_id'] ?? '';
    $subtitle = $d['subtitle'] ?? '';
@endphp
<section class="obsidian-section py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <div class="obsidian-section-divider mx-auto mb-3"></div>
            <h2 class="obsidian-reveal" data-field="title">{{ $title }}</h2>
            @if($subtitle)
                <p class="mt-2 obsidian-reveal" style="color:var(--obsidian-text-dim)" data-field="subtitle">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="obsidian-yt obsidian-reveal">
                    @if($videoId)
                        <iframe
                            src="https://www.youtube-nocookie.com/embed/{{ $videoId }}?rel=0&modestbranding=1&color=white"
                            title="{{ $title }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy"
                        ></iframe>
                    @else
                        <div class="obsidian-yt-empty">
                            <i class="bi bi-play-circle"></i>
                            <p>Configurez l'ID de la vidéo YouTube dans l'éditeur.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.obsidian-yt {
    position: relative;
    aspect-ratio: 16 / 9;
    border-radius: var(--obsidian-radius);
    overflow: hidden;
    border: 1px solid var(--obsidian-border);
    background: var(--obsidian-surface);
    box-shadow: 0 8px 40px rgba(0,0,0,.3), 0 0 0 1px rgba(var(--obsidian-primary-rgb),.05);
}
.obsidian-yt iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
}
.obsidian-yt-empty {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--obsidian-text-dim);
    gap: .5rem;
}
.obsidian-yt-empty i { font-size: 3rem; }
.obsidian-yt-empty p { font-size: .85rem; margin: 0; }
</style>
