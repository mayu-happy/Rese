@extends('layouts.app')

@section('title', 'Mypage')

@section('content')
<div class="mypage">
    <div class="mypage__inner">

        <div class="mypage__grid">
            {{-- 左：予約状況 --}}
            <section>
                <div class="mypage__spacer"></div>
                <h3 class="mypage__title">予約状況</h3>

                @if(session('success'))
                <div class="flash-message">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
                @endif

                @foreach($reservations as $i => $reservation)
                <div class="reserve-card">
                    <div class="reserve-card__head">
                        <div class="reserve-card__head-left">
                            <span class="reserve-card__icon">🕒</span>
                            <span class="reserve-card__label">予約{{ $i + 1 }}</span>
                        </div>

                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                class="reserve-card__close"
                                type="submit"
                                onclick="return confirm('この予約をキャンセルしますか？')">
                                ×
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <dl class="reserve-card__dl">
                            <div class="reserve-card__row">
                                <dt>Shop</dt>
                                <dd>{{ $reservation->shop->name }}</dd>
                            </div>

                            <div class="reserve-card__row">
                                <dt>Date</dt>
                                <dd>
                                    <input
                                        type="date"
                                        name="date"
                                        value="{{ \Carbon\Carbon::parse($reservation->reserved_at)->format('Y-m-d') }}"
                                        class="reserve-card__input">
                                </dd>
                            </div>

                            <div class="reserve-card__row">
                                <dt>Time</dt>
                                <dd>
                                    <input
                                        type="time"
                                        name="time"
                                        value="{{ \Carbon\Carbon::parse($reservation->reserved_at)->format('H:i') }}"
                                        class="reserve-card__input">
                                </dd>
                            </div>

                            <div class="reserve-card__row">
                                <dt>Number</dt>
                                <dd>
                                    <select name="number" class="reserve-card__select">
                                        @for($p = 1; $p <= 20; $p++)
                                            <option value="{{ $p }}" {{ $reservation->people == $p ? 'selected' : '' }}>
                                            {{ $p }}人
                                            </option>
                                            @endfor
                                    </select>
                                </dd>
                            </div>
                        </dl>

                        <div class="reserve-card__actions">
                            <button type="submit" class="reserve-card__update-btn">変更する</button>
                        </div>
                    </form>
                </div>
                @endforeach
            </section>

            {{-- 右：お気に入り店舗 --}}
            <section>
                <h2 class="mypage__username">{{ $user->name }}さん</h2>
                <h3 class="mypage__title">お気に入り店舗</h3>

                <div class="shop-grid">
                    @foreach($favorites as $favorite)
                    @php $shop = $favorite->shop; @endphp
                    @continue(!$shop)

                    <div class="shop-card">
                        <img class="shop-card__img" src="{{ asset('storage/' . $shop->image_url) }}" alt="{{ $shop->name }}">
                        <div class="shop-card__body">
                            <p class="shop-card__name">{{ $shop->name }}</p>
                            <p class="shop-card__meta">#{{ $shop->area->name ?? '' }} #{{ $shop->genre->name ?? '' }}</p>

                            <div class="shop-card__actions">
                                <a class="btn" href="{{ route('shops.show', $shop->id) }}">詳しくみる</a>

                                <form method="POST" action="{{ route('favorites.toggle', $shop->id) }}" class="fav-form">
                                    @csrf
                                    <button type="submit" class="heart is-on" aria-label="favorite">
                                        <svg class="heart-svg" viewBox="0 0 28 26" aria-hidden="true">
                                            <path d="M14 24.7c-.4 0-.8-.2-1.1-.4C9.9 22 2 15.5 2 9.6 2 5.7 4.9 3 8.5 3c2.2 0 4.1 1.1 5.5 2.9C15.5 4.1 17.4 3 19.6 3c3.6 0 6.4 2.7 6.4 6.6 0 5.9-7.9 12.4-10.9 14.7-.3.2-.7.4-1.1.4z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </section>
        </div>

    </div>
</div>
@endsection