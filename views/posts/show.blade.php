@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->description)
@section('type', 'article')

@push('meta')
<meta property="og:article:author:username" content="{{ $post->author->name }}">
<meta property="og:article:published_time" content="{{ $post->published_at->toIso8601String() }}">
<meta property="og:article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
@endpush

@section('content')
    @if(!$post->isPublished())
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> {{ trans('messages.posts.unpublished') }}
        </div>
    @endif

    <article class="obsidian-article">
        {{-- Hero image --}}
        @if($post->hasImage())
            <div class="obsidian-article-hero">
                <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}">
                <div class="obsidian-article-hero-fade"></div>
            </div>
        @endif

        {{-- Header --}}
        <div class="obsidian-article-header">
            <h1 class="obsidian-article-title">{{ $post->title }}</h1>

            <div class="obsidian-article-meta">
                <img src="{{ $post->author->getAvatar() }}" alt="{{ $post->author->name }}" class="obsidian-article-avatar">
                <div>
                    <span class="obsidian-article-author">{{ $post->author->name }}</span>
                    <span class="obsidian-article-date">{{ format_date($post->published_at) }}</span>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="obsidian-article-body">
            {!! $post->content !!}
        </div>

        {{-- Footer: likes --}}
        <div class="obsidian-article-footer">
            <button type="button" class="obsidian-like-btn @if($post->isLiked()) active @endif" @guest disabled @endguest data-like-url="{{ route('posts.like', $post) }}">
                <i class="bi bi-heart @if($post->isLiked()) d-none @endif" data-liked="true"></i>
                <i class="bi bi-heart-fill @if(! $post->isLiked()) d-none @endif" data-liked="false"></i>
                <span>@lang('messages.likes', ['count' => '<span class="likes-count">'.$post->likes->count().'</span>'])</span>
                <span class="d-none spinner-border spinner-border-sm load-spinner" role="status"></span>
            </button>
        </div>
    </article>

    {{-- Comments --}}
    <section class="obsidian-comments" id="comments">
        <h3 class="obsidian-comments-title">
            <i class="bi bi-chat-dots me-2"></i>
            {{ trans('messages.comments.create') }}
            <span class="obsidian-comments-count">{{ $post->comments->count() }}</span>
        </h3>

        @can('create', \Azuriom\Models\Comment::class)
            <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="obsidian-comment-form">
                @csrf
                <div class="obsidian-comment-input-wrap">
                    @auth
                        <img src="{{ auth()->user()->getAvatar() }}" alt="{{ auth()->user()->name }}" class="obsidian-comment-self-avatar">
                    @endauth
                    <div class="flex-grow-1">
                        <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="3" placeholder="{{ trans('messages.comments.content') }}" required></textarea>
                        @error('content')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="text-end mt-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send me-1"></i> {{ trans('messages.actions.comment') }}
                    </button>
                </div>
            </form>
        @endcan

        @guest
            <div class="obsidian-comment-guest">
                <i class="bi bi-info-circle me-1"></i> {{ trans('messages.comments.guest') }}
            </div>
        @endguest

        <div class="obsidian-comment-list">
            @foreach($post->comments as $comment)
                <div class="obsidian-comment">
                    <img src="{{ $comment->author->getAvatar() }}" alt="{{ $comment->author->name }}" class="obsidian-comment-avatar">
                    <div class="obsidian-comment-body">
                        <div class="obsidian-comment-head">
                            <span class="obsidian-comment-author">{{ $comment->author->name }}</span>
                            <span class="obsidian-comment-time">{{ format_date($comment->created_at, true) }}</span>
                            @can('delete', $comment)
                                <a class="obsidian-comment-delete" href="{{ route('posts.comments.destroy', [$post, $comment]) }}" data-confirm="delete" title="{{ trans('messages.actions.delete') }}">
                                    <i class="bi bi-trash"></i>
                                </a>
                            @endcan
                        </div>
                        <div class="obsidian-comment-text">{{ $comment->parseContent() }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Delete confirm modal --}}
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content obsidian-modal">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">{{ trans('messages.comments.delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">{{ trans('messages.comments.delete_confirm') }}</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" type="button" data-bs-dismiss="modal">{{ trans('messages.actions.cancel') }}</button>
                    <form id="confirmDeleteForm" method="POST">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit">{{ trans('messages.actions.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════
   ARTICLE PAGE
   ═══════════════════════════════════════ */
.obsidian-article {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    overflow: hidden;
    margin-bottom: 2rem;
}

/* Hero image */
.obsidian-article-hero {
    position: relative;
    height: 420px;
    overflow: hidden;
}
.obsidian-article-hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.obsidian-article-hero-fade {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 50%;
    background: linear-gradient(transparent, var(--obsidian-surface));
    pointer-events: none;
}

/* Header */
.obsidian-article-header {
    padding: 1.5rem 2rem 0;
}
.obsidian-article-hero + .obsidian-article-header {
    margin-top: -3rem;
    position: relative;
    z-index: 1;
}
.obsidian-article-title {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    line-height: 1.2;
    margin-bottom: 1rem;
    color: #fff;
}
.obsidian-article-meta {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--obsidian-border);
}
.obsidian-article-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--obsidian-border);
}
.obsidian-article-author {
    display: block;
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    color: #fff;
}
.obsidian-article-date {
    display: block;
    font-size: .78rem;
    color: var(--obsidian-text-dim);
}

/* Body */
.obsidian-article-body {
    padding: 1.5rem 2rem;
    color: var(--obsidian-text);
    line-height: 1.75;
    font-size: .95rem;
}
.obsidian-article-body h1,
.obsidian-article-body h2,
.obsidian-article-body h3,
.obsidian-article-body h4 {
    color: #fff;
    margin-top: 1.5em;
    margin-bottom: .5em;
}
.obsidian-article-body p { margin-bottom: 1em; }
.obsidian-article-body img {
    border-radius: .5rem;
    margin: 1rem 0;
}
.obsidian-article-body a { color: var(--obsidian-primary); text-decoration: underline; }
.obsidian-article-body blockquote {
    border-left: 3px solid var(--obsidian-primary);
    padding: .75rem 1.25rem;
    margin: 1rem 0;
    background: rgba(var(--obsidian-primary-rgb),.04);
    border-radius: 0 .5rem .5rem 0;
    color: var(--obsidian-text);
}
.obsidian-article-body code {
    background: var(--obsidian-surface-2);
    padding: .15em .4em;
    border-radius: .25rem;
    font-size: .85em;
    color: var(--obsidian-accent);
}
.obsidian-article-body pre {
    background: var(--obsidian-dark);
    border: 1px solid var(--obsidian-border);
    border-radius: .5rem;
    padding: 1rem;
    overflow-x: auto;
}
.obsidian-article-body pre code { background: none; padding: 0; }
.obsidian-article-body ul, .obsidian-article-body ol {
    padding-left: 1.5rem;
    margin-bottom: 1em;
}
.obsidian-article-body li { margin-bottom: .3em; }
.obsidian-article-body hr {
    border-color: var(--obsidian-border);
    margin: 2rem 0;
}
.obsidian-article-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}
.obsidian-article-body th, .obsidian-article-body td {
    padding: .5rem .75rem;
    border: 1px solid var(--obsidian-border);
}
.obsidian-article-body th {
    background: var(--obsidian-surface-2);
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    font-size: .8rem;
    color: #fff;
}

/* Footer (likes) */
.obsidian-article-footer {
    padding: 1rem 2rem 1.5rem;
    border-top: 1px solid var(--obsidian-border);
}
.obsidian-like-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .5rem 1rem;
    border: 1px solid var(--obsidian-border);
    border-radius: 2rem;
    background: transparent;
    color: var(--obsidian-text);
    font-family: 'Rajdhani', sans-serif;
    font-weight: 600;
    font-size: .85rem;
    cursor: pointer;
    transition: all .2s;
}
.obsidian-like-btn:hover {
    border-color: rgba(239,68,68,.4);
    color: #f87171;
    background: rgba(239,68,68,.06);
}
.obsidian-like-btn.active {
    border-color: rgba(239,68,68,.4);
    color: #f87171;
}
.obsidian-like-btn:disabled { opacity: .5; cursor: default; }

/* ═══════════════════════════════════════
   COMMENTS
   ═══════════════════════════════════════ */
.obsidian-comments { margin-bottom: 2rem; }

.obsidian-comments-title {
    font-size: 1.1rem;
    color: #fff;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.obsidian-comments-count {
    font-size: .75rem;
    background: rgba(var(--obsidian-primary-rgb),.15);
    color: var(--obsidian-primary);
    padding: .15rem .5rem;
    border-radius: 1rem;
    font-weight: 700;
}

/* Comment form */
.obsidian-comment-form {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    padding: 1rem;
    margin-bottom: 1.25rem;
}
.obsidian-comment-input-wrap {
    display: flex;
    gap: .75rem;
    align-items: flex-start;
}
.obsidian-comment-self-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    margin-top: .25rem;
}
.obsidian-comment-guest {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    padding: 1rem;
    color: var(--obsidian-text-dim);
    font-size: .9rem;
    margin-bottom: 1.25rem;
}

/* Comment list */
.obsidian-comment-list {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}
.obsidian-comment {
    display: flex;
    gap: .75rem;
    padding: 1rem;
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    transition: border-color .2s;
}
.obsidian-comment:hover {
    border-color: rgba(var(--obsidian-primary-rgb),.15);
}
.obsidian-comment-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.obsidian-comment-body { flex: 1; min-width: 0; }
.obsidian-comment-head {
    display: flex;
    align-items: center;
    gap: .5rem;
    margin-bottom: .3rem;
    flex-wrap: wrap;
}
.obsidian-comment-author {
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    font-size: .85rem;
    color: #fff;
}
.obsidian-comment-time {
    font-size: .75rem;
    color: var(--obsidian-text-dim);
}
.obsidian-comment-delete {
    margin-left: auto;
    color: var(--obsidian-text-dim);
    font-size: .8rem;
    transition: color .2s;
}
.obsidian-comment-delete:hover { color: #f87171; }
.obsidian-comment-text {
    color: var(--obsidian-text);
    font-size: .9rem;
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 767.98px) {
    .obsidian-article-hero { height: 260px; }
    .obsidian-article-header { padding: 1rem 1.25rem 0; }
    .obsidian-article-body { padding: 1rem 1.25rem; }
    .obsidian-article-footer { padding: 1rem 1.25rem; }
    .obsidian-article-title { font-size: 1.3rem; }
}
</style>
@endpush
