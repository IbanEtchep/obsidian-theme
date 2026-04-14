<!DOCTYPE html>
@include('elements.base')
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', setting('description', ''))">
    <meta name="theme-color" content="{{ theme_config('colors.primary') ?? '#6366f1' }}">
    <meta name="author" content="{{ site_name() }}">

    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="@yield('type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ favicon() }}">
    <meta property="og:description" content="@yield('description', setting('description', ''))">
    <meta property="og:site_name" content="{{ site_name() }}">
    @stack('meta')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ site_name() }}</title>

    <link rel="shortcut icon" href="{{ favicon() }}">

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('vendor/axios/axios.min.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ theme_asset('js/theme.js') }}" defer></script>
    @stack('scripts')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    @php
        $obsidianColors = theme_config('colors') ?? [];
        $primaryColor = $obsidianColors['primary'] ?? '#6366f1';
        $secondaryColor = $obsidianColors['secondary'] ?? '#8b5cf6';
        $accentColor = $obsidianColors['accent'] ?? '#06b6d4';
        $navbarBg = $obsidianColors['navbar'] ?? '#0f172a';
        $footerBg = $obsidianColors['footer'] ?? '#0f172a';
        $heroStart = $obsidianColors['hero_start'] ?? '#1e1b4b';
        $heroEnd = $obsidianColors['hero_end'] ?? '#312e81';
    @endphp

    @include('elements.theme-color', ['color' => $primaryColor])

    <style>
        :root {
            --obsidian-primary: {{ $primaryColor }};
            --obsidian-primary-rgb: {{ color_rgb($primaryColor) }};
            --obsidian-secondary: {{ $secondaryColor }};
            --obsidian-secondary-rgb: {{ color_rgb($secondaryColor) }};
            --obsidian-accent: {{ $accentColor }};
            --obsidian-accent-rgb: {{ color_rgb($accentColor) }};
            --obsidian-navbar-bg: {{ $navbarBg }};
            --obsidian-footer-bg: {{ $footerBg }};
            --obsidian-hero-start: {{ $heroStart }};
            --obsidian-hero-end: {{ $heroEnd }};
            --obsidian-primary-contrast: {{ color_contrast($primaryColor) }};
            --obsidian-secondary-contrast: {{ color_contrast($secondaryColor) }};
        }
    </style>

    <link href="{{ theme_asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body class="obsidian-body" data-bs-theme="dark">
<div id="app">
    <header>
        @include('elements.navbar')
    </header>

    @yield('app')
</div>

@include('elements.footer')

@auth
    @if(Auth::user()->isAdmin())
        @include('editor.footer-modal')
    @endif
@endauth

@stack('footer-scripts')
</body>
</html>
