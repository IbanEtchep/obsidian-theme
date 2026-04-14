@php
    $d = $widget['data'] ?? [];
    $sectionTitle = $d['title'] ?: trans('theme::messages.news.title');
@endphp
@if(!$posts->isEmpty())
<section class="obsidian-section py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <div class="obsidian-section-divider mx-auto mb-3"></div>
            <h2 class="obsidian-reveal" data-field="title">{{ $sectionTitle }}</h2>
        </div>

        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-lg-4 col-md-6">
                    <article class="obsidian-news-card h-100 obsidian-reveal">
                        @if($post->hasImage())
                            <div class="obsidian-news-img">
                                <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" loading="lazy">
                            </div>
                        @endif
                        <div class="p-4">
                            <div class="d-flex align-items-center small mb-2" style="color:var(--obsidian-text-dim)">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ format_date($post->published_at) }}
                                <span class="mx-2">&middot;</span>
                                <i class="bi bi-person me-1"></i>
                                {{ $post->author->name }}
                            </div>
                            <h4 class="mb-2">
                                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                            </h4>
                            <p class="mb-3" style="color:var(--obsidian-text-dim)">
                                {{ Str::limit(strip_tags($post->content), 180) }}
                            </p>
                            <a href="{{ route('posts.show', $post) }}" class="obsidian-link">
                                {{ trans('theme::messages.news.read_more') }} <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
