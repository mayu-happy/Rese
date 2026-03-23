<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Rese')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('jsSearchForm');
        if (!form) return;

        const go = () => {
            const url = new URL(window.location.href);

            const area = form.querySelector('select[name="area_id"]')?.value ?? '';
            const genre = form.querySelector('select[name="genre_id"]')?.value ?? '';
            const keyword = form.querySelector('input[name="keyword"]')?.value ?? '';

            if (area) url.searchParams.set('area_id', area);
            else url.searchParams.delete('area_id');

            if (genre) url.searchParams.set('genre_id', genre);
            else url.searchParams.delete('genre_id');

            if (keyword) url.searchParams.set('keyword', keyword);
            else url.searchParams.delete('keyword');

            // ページングしてたら1ページ目に戻す
            url.searchParams.delete('page');

            window.location.href = url.toString();
        };

        // selectは変更で即反映
        form.querySelectorAll('select').forEach(el => {
            el.addEventListener('change', go);
        });

        // keywordはEnterで反映（ボタン無し）
        const kw = form.querySelector('input[name="keyword"]');
        if (kw) {
            kw.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    go();
                }
            });
        }
    });
</script>

<body>
    {{-- ヘッダー（全ページ共通） --}}
    <header class="header">
        <div class="header__inner container">

            {{-- 左：ハンバーガー + ロゴ --}}
            <div class="header__left">
                <a class="header__btn" href="{{ route('menu') }}" aria-label="menu">
                    <div class="menu-btn__lines">
                        <span></span><span></span><span></span>
                    </div>
                </a>
                <a class="logo" href="{{ route('shops.index') }}">Rese</a>
            </div>

            {{-- ★トップだけここに検索が入る --}}
            <div class="header__right">
                @yield('header-search')
            </div>
        </div>
    </header>

    <main class="container main">
        @yield('content')
    </main>
</body>

</html>