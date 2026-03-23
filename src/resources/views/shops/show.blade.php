@extends('layouts.app')

@section('title', $shop->name)

@section('content')
<div class="detail">

    {{-- 左：店舗情報 --}}
    <section class="detail__left">
        <div class="detail__head">
            <a class="detail__back" href="{{ route('shops.index') }}">‹</a>
            <h1 class="detail__name">{{ $shop->name }}</h1>
        </div>

        <img class="detail__img" src="{{ asset('storage/' . $shop->image_url) }}" alt="{{ $shop->name }}">
        <p class="detail__tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>

        <p class="detail__desc">{{ $shop->description }}</p>
    </section>

    {{-- 右：予約カード --}}
    <aside class="reserve">
        <h2 class="reserve__title">予約</h2>

        @if ($errors->any())
        <div style="margin-bottom:12px; padding:10px; border-radius:6px; background:rgba(0,0,0,.2); color:#fff;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="reserve__form" method="POST" action="{{ route('reservations.store') }}">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <input class="reserve__input reserve__input--date"
                type="date" name="date" id="input-date" value="{{ old('date') }}" required>

            <div class="reserve__select">
                <select class="reserve__input" name="time" id="input-time" required>
                    @foreach($times as $time)
                    <option value="{{ $time }}" @selected(old('time')===$time)>{{ $time }}</option>
                    @endforeach
                </select>
            </div>

            <div class="reserve__select">
                <select class="reserve__input" name="number" id="input-number" required>
                    @for($i=1; $i<=20; $i++)
                        <option value="{{ $i }}" @selected(old('number')==$i)>{{ $i }}人</option>
                        @endfor
                </select>
            </div>

            {{-- summary（そのまま） --}}
            <div class="reserve__summary">
                <div class="reserve__row"><span>Shop</span><span>{{ $shop->name }}</span></div>

                <div class="reserve__row"><span>Date</span><span id="summary-date">----</span></div>
                <div class="reserve__row"><span>Time</span><span id="summary-time">----</span></div>
                <div class="reserve__row"><span>Number</span><span id="summary-number">----</span></div>
            </div>
            <button class="reserve__submit" type="submit">予約する</button>
        </form>
    </aside>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputDate = document.getElementById('input-date');
        const inputTime = document.getElementById('input-time');
        const inputNumber = document.getElementById('input-number');

        const summaryDate = document.getElementById('summary-date');
        const summaryTime = document.getElementById('summary-time');
        const summaryNumber = document.getElementById('summary-number');

        const render = () => {
            // date は "2026-03-01" の形式で出したい（見本通り）
            summaryDate.textContent = inputDate.value ? inputDate.value : '----';

            // time はそのまま（"17:00"）
            summaryTime.textContent = inputTime.value ? inputTime.value : '----';

            // number は "1人" の表記に
            summaryNumber.textContent = inputNumber.value ? `${inputNumber.value}人` : '----';
        };

        // 変更時に反映
        inputDate.addEventListener('change', render);
        inputTime.addEventListener('change', render);
        inputNumber.addEventListener('change', render);

        // 初期表示（old() があるときに最初から反映される）
        render();
    });
</script>

<section class="review-section">
    <h2 class="review-section__title">レビュー</h2>

    <div class="review-section__summary">
        <strong>平均評価：</strong>
        {{ $shop->average_rating ? $shop->average_rating . ' / 5' : 'まだ評価はありません' }}
    </div>

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

    @auth
    <form class="review-form" method="POST" action="{{ route('reviews.store', $shop->id) }}">
        @csrf

        <div class="review-form__row">
            <label for="rating">評価</label>
            <select name="rating" id="rating" class="review-form__input">
                <option value="">選択してください</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('rating', optional($userReview)->rating) == $i ? 'selected' : '' }}>
                    {{ $i }}
                    </option>
                    @endfor
            </select>
        </div>

        <div class="review-form__row">
            <label for="comment">コメント</label>
            <textarea
                name="comment"
                id="comment"
                rows="4"
                class="review-form__textarea">{{ old('comment', optional($userReview)->comment) }}</textarea>
        </div>

        @error('rating')
        <p class="error">{{ $message }}</p>
        @enderror

        @error('comment')
        <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit" class="review-form__btn">
            {{ $userReview ? 'レビューを更新する' : 'レビューを投稿する' }}
        </button>
    </form>
    @else
    <p>レビューを投稿するにはログインが必要です。</p>
    @endauth

    <div class="review-list">
        @forelse($shop->reviews as $review)
        <div class="review-card">
            <p class="review-card__meta">
                {{ $review->user->name }} さん / 評価：{{ $review->rating }} / 5
            </p>

            @if($review->comment)
            <p class="review-card__comment">{{ $review->comment }}</p>
            @endif
        </div>
        @empty
        <p>まだレビューはありません。</p>
        @endforelse
    </div>
</section>
@endsection