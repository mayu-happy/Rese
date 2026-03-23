<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <header class="header header--menu">
        <div class="header__inner">
            <a class="menu-btn menu-btn--close" href="{{ route('shops.index') }}" aria-label="close">
                <span class="menu-btn__x" aria-hidden="true">×</span>
            </a>
        </div>
    </header>

    <main class="menu">
        <a class="menu__link" href="{{ route('shops.index') }}">Home</a>

        @guest
        <a class="menu__link" href="{{ route('register') }}">Registration</a>
        <a class="menu__link" href="{{ route('login') }}">Login</a>
        @endguest

        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu__link menu__button">Logout</button>
        </form>

        <a class="menu__link" href="{{ route('mypage') }}">Mypage</a>
        @endauth
    </main>
</body>

</html>