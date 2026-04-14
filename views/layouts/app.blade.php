@extends('layouts.base')

@section('app')
    <div class="obsidian-page-spacer"></div>

    <main class="container content my-5">
        @include('elements.session-alerts')

        @yield('content')
    </main>
@endsection
