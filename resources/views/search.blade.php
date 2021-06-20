@if (Auth::check())
    @extends('layouts.app')

    @section('content')
        @include('commons.navbar')
        <div class="container">
            <div class="row">
                <div class="col-md-3 appear">
                    @include('commons.menubar')
                </div>
                <div class="col-md-9 mt-2">
                    <p class="lead">{{ $keyword }}の検索結果</p>
                    {{-- 投稿一覧 --}}
                    @include('cordinates.search')
                </div>
                <div class="col-md-3 none">
                    {{-- 投稿フォーム --}}
                    <div class="mb-2">
                        @include('commons.menubar')
                    </div>
                    {{-- ユーザ一覧 --}}
                    @include('users.hot')
                </div>
            </div>
        </div>
    @endsection
@else
    @include('auth.login')
@endif