@extends('layouts.app')

@section('title', '予約完了')

@section('content')
<main class="center">
    <div class="panel">
        <p class="panel__message">予約完了しました</p>
        <a class="btn" href="{{ route('shops.index') }}">戻る</a>
    </div>
</main>
@endsection