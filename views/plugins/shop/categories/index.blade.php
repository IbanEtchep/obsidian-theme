@extends('layouts.app')

@section('title', trans('shop::messages.title'))

@section('content')
    <div class="sv-layout" id="shop">
        <aside class="sv-aside">
            @include('shop::categories._sidebar')
        </aside>

        <main class="sv-main">
            <div class="sv-main-head">
                <h1 class="sv-title">{{ trans('shop::messages.title') }}</h1>
            </div>

            <div class="sv-welcome">
                <div>{{ $welcome }}</div>
            </div>
        </main>
    </div>
@endsection

@push('styles')
    @include('plugins.shop.categories._shop-styles')
@endpush
