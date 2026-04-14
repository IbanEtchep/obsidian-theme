@extends('layouts.app')

@section('title', $page->title)
@section('description', $page->description)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <article class="obsidian-page">
                <div class="obsidian-page-header">
                    <h1 class="obsidian-page-title">{{ $page->title }}</h1>
                </div>

                <div class="obsidian-page-body">
                    {!! $page->content !!}
                </div>
            </article>
        </div>
    </div>
@endsection

@push('styles')
<style>
.obsidian-page {
    background: var(--obsidian-surface);
    border: 1px solid var(--obsidian-border);
    border-radius: var(--obsidian-radius);
    overflow: hidden;
}

.obsidian-page-header {
    padding: 2rem 2.5rem 1.25rem;
    border-bottom: 1px solid var(--obsidian-border);
}
.obsidian-page-title {
    font-size: clamp(1.4rem, 3vw, 2rem);
    color: #fff;
    line-height: 1.2;
    margin: 0;
}

/* === Reading body === */
.obsidian-page-body {
    padding: 2rem 2.5rem 2.5rem;
    color: var(--obsidian-text);
    font-size: .95rem;
    line-height: 1.85;
}

.obsidian-page-body h1,
.obsidian-page-body h2 {
    color: #fff;
    margin-top: 2em;
    margin-bottom: .6em;
    padding-bottom: .4em;
    border-bottom: 1px solid var(--obsidian-border);
}
.obsidian-page-body h3,
.obsidian-page-body h4,
.obsidian-page-body h5 {
    color: #fff;
    margin-top: 1.5em;
    margin-bottom: .5em;
}
.obsidian-page-body h1:first-child,
.obsidian-page-body h2:first-child,
.obsidian-page-body h3:first-child { margin-top: 0; }

.obsidian-page-body p { margin-bottom: 1.1em; }

.obsidian-page-body a {
    color: var(--obsidian-primary);
    text-decoration: underline;
    text-decoration-color: rgba(var(--obsidian-primary-rgb),.3);
    text-underline-offset: 2px;
    transition: text-decoration-color .2s;
}
.obsidian-page-body a:hover {
    text-decoration-color: var(--obsidian-primary);
}

.obsidian-page-body img {
    border-radius: .5rem;
    margin: 1.25rem 0;
    border: 1px solid var(--obsidian-border);
}

.obsidian-page-body blockquote {
    border-left: 3px solid var(--obsidian-primary);
    padding: .75rem 1.25rem;
    margin: 1.25rem 0;
    background: rgba(var(--obsidian-primary-rgb),.04);
    border-radius: 0 .5rem .5rem 0;
    color: var(--obsidian-text);
}
.obsidian-page-body blockquote p:last-child { margin-bottom: 0; }

.obsidian-page-body code {
    background: var(--obsidian-surface-2);
    padding: .15em .4em;
    border-radius: .25rem;
    font-size: .85em;
    color: var(--obsidian-accent);
}
.obsidian-page-body pre {
    background: var(--obsidian-dark);
    border: 1px solid var(--obsidian-border);
    border-radius: .5rem;
    padding: 1.25rem;
    overflow-x: auto;
    margin: 1.25rem 0;
}
.obsidian-page-body pre code {
    background: none;
    padding: 0;
    color: var(--obsidian-text);
}

.obsidian-page-body ul, .obsidian-page-body ol {
    padding-left: 1.5rem;
    margin-bottom: 1.1em;
}
.obsidian-page-body li { margin-bottom: .4em; }
.obsidian-page-body li::marker { color: var(--obsidian-primary); }

.obsidian-page-body hr {
    border: none;
    border-top: 1px solid var(--obsidian-border);
    margin: 2rem 0;
}

.obsidian-page-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.25rem 0;
}
.obsidian-page-body th,
.obsidian-page-body td {
    padding: .6rem .85rem;
    border: 1px solid var(--obsidian-border);
    text-align: left;
}
.obsidian-page-body th {
    background: var(--obsidian-surface-2);
    font-family: 'Rajdhani', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    font-size: .8rem;
    letter-spacing: .03em;
    color: #fff;
}
.obsidian-page-body tr:hover td {
    background: rgba(var(--obsidian-primary-rgb),.02);
}

/* Embedded videos */
.obsidian-page-body iframe {
    border-radius: .5rem;
    margin: 1.25rem 0;
    max-width: 100%;
}

/* Bootstrap cards inside pages */
.obsidian-page-body .card {
    background: var(--obsidian-surface-2);
    border-color: var(--obsidian-border);
}

/* Bootstrap rows inside pages */
.obsidian-page-body .row { row-gap: 1rem; }

@media (max-width: 767.98px) {
    .obsidian-page-header { padding: 1.25rem 1.25rem 1rem; }
    .obsidian-page-body { padding: 1.25rem; font-size: .9rem; line-height: 1.75; }
}
</style>
@endpush
