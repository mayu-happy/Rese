{{-- resources/views/auth/thanks.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="center-card">
    <p class="center-card__title">会員登録ありがとうございます</p>

    <a class="center-card__btn" href="{{ route('login') }}">
        ログインする
    </a>
</div>
@endsection