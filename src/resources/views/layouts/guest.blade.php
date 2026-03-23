<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Rese' }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    @unless(View::hasSection('hide_guest_header'))
    <header class="header">
        <div class="header__inner container">

            <div class="header__left">
                <a class="menu-btn" href="{{ route('menu') }}" aria-label="menu">
                    <div class="menu-btn__lines">
                        <span></span><span></span><span></span>
                    </div>
                </a>

                <a class="logo" href="{{ route('shops.index') }}">Rese</a>
            </div>

        </div>
    </header>
    @endunless

    <main class="container main">
        {{ $slot }}
    </main>
</body>

</html>