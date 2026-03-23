@extends('layouts.app')

@section('title', '店舗一覧')

@section('header-search')
<div class="header__search">
    <form method="GET" action="{{ route('shops.index') }}" class="search" id="jsSearchForm">

        <div class="search__selectWrap">
            <select class="search__select" name="area_id">
                <option value="">All area</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}"
                    {{ (string)request('area_id') === (string)$area->id ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
                @endforeach
            </select>

            <svg class="search__arrow" viewBox="0 0 512 512" aria-hidden="true">
                <path d="M456.931 65
             L256.120 160
             C247.033 165, 230.058 165, 220.971 160
             L55.073 65
             C30.058 40, 0.483 58, 0.006 85
             C-0.125 92, 1.578 99, 5.473 106
             L215.482 373.229
             C223 383, 236 389, 250.5 389
             C265 389, 278 383, 285.560 373.229
             L506.521 106
             C510.4 99, 512 92, 511.994 85
             C511.517 58, 481.946 40, 456.931 65
             Z" />
            </svg>
        </div>

        <div class="search__selectWrap">
            <select class="search__select" name="genre_id">
                <option value="">All genre</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}"
                    {{ (string)request('genre_id') === (string)$genre->id ? 'selected' : '' }}>
                    {{ $genre->name }}
                </option>
                @endforeach
            </select>

            <svg class="search__arrow" viewBox="0 0 512 512" aria-hidden="true">
                <path d="M456.931 65
             L256.120 160
             C247.033 165, 230.058 165, 220.971 160
             L55.073 65
             C30.058 40, 0.483 58, 0.006 85
             C-0.125 92, 1.578 99, 5.473 106
             L215.482 373.229
             C223 383, 236 389, 250.5 389
             C265 389, 278 383, 285.560 373.229
             L506.521 106
             C510.4 99, 512 92, 511.994 85
             C511.517 58, 481.946 40, 456.931 65
             Z" />
            </svg>
        </div>

        <div class="search__inputWrap">
            <svg class="search__icon" viewBox="0 0 64 64" aria-hidden="true">
                <circle cx="26" cy="26" r="16" class="search__icon-circle"></circle>
                <line x1="40" y1="40" x2="54" y2="54" class="search__icon-handle"></line>
                <path d="M20 17 Q24 13 29 15" class="search__icon-highlight"></path>
            </svg>
            <input class="search__input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search ..." />
        </div>

    </form>
</div>
@endsection

@section('content')
<div class="wrap">

    <div class="grid">
        @foreach($shops as $shop)
        <div class="card">
            <img class="img" src="{{ asset('storage/' . $shop->image_url) }}" alt="{{ $shop->name }}">
            <div class="body">
                <p class="name">{{ $shop->name }}</p>
                <div class="meta">#{{ $shop->area->name }} #{{ $shop->genre->name }}</div>

                <div class="card__footer">
                    <a class="btn" href="{{ route('shops.show', $shop->id) }}">詳しくみる</a>

                    @auth
                    @php $liked = in_array($shop->id, $favoriteShopIds); @endphp
                    <form method="POST" action="{{ route('favorites.toggle', $shop->id) }}" class="fav-form">
                        @csrf
                        <button type="submit" class="heart {{ $liked ? 'is-on' : '' }}" aria-label="favorite">
                            <svg class="heart-svg" viewBox="0 0 28 26" aria-hidden="true">
                                <path d="M14 24.7c-.4 0-.8-.2-1.1-.4C9.9 22 2 15.5 2 9.6 2 5.7 4.9 3 8.5 3c2.2 0 4.1 1.1 5.5 2.9C15.5 4.1 17.4 3 19.6 3c3.6 0 6.4 2.7 6.4 6.6 0 5.9-7.9 12.4-10.9 14.7-.3.2-.7.4-1.1.4z" />
                            </svg>
                        </button>
                    </form>
                    @else
                    <span class="heart is-off" title="ログインするとお気に入りできます">
                        <svg class="heart-svg" viewBox="0 0 28 26" aria-hidden="true">
                            <path d="M14 24.7c-.4 0-.8-.2-1.1-.4C9.9 22 2 15.5 2 9.6 2 5.7 4.9 3 8.5 3c2.2 0 4.1 1.1 5.5 2.9C15.5 4.1 17.4 3 19.6 3c3.6 0 6.4 2.7 6.4 6.6 0 5.9-7.9 12.4-10.9 14.7-.3.2-.7.4-1.1.4z" />
                        </svg>
                    </span>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="pager">
        {{ $shops->onEachSide(1)->links('pagination::simple-default') }}
    </div>

</div>
@endsection