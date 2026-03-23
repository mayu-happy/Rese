@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('title', 'ログイン')
@section('hide_guest_header', true)

@section('content')
<div class="card">
    <div class="card__head">Login</div>

    <div class="card__body">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="row">
                <div class="auth-row">
                    <span class="auth-icon" aria-hidden="true">
                        <!-- mail icon -->
                        <svg viewBox="0 0 24 24" class="auth-svg" aria-hidden="true">
                            <path fill="currentColor" d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z" />
                        </svg>
                    </span>
                    <input class="auth-input" type="email" name="email" placeholder="Email">
                </div>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            {{-- Password --}}
            <div class="row">
                <div class="auth-row">
                    <span class="auth-icon" aria-hidden="true">
                        <!-- lock icon -->
                        <svg viewBox="0 0 24 24" class="auth-svg" aria-hidden="true">
                            <!-- 鍵の本体 -->
                            <path fill="currentColor"
                                d="M12 1a5 5 0 0 0-5 5v4H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12
       a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zm-3
       9V6a3 3 0 0 1 6 0v4H9z" />

                            <!-- 鍵穴 -->
                            <circle cx="12" cy="16" r="1.5" fill="#fff" />
                        </svg>
                    </span>
                    <input class="auth-input" type="password" name="password" placeholder="Password">
                </div>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="auth-actions">
                <button class="auth-btn" type="submit">ログイン</button>
            </div>

        </form>
    </div>
</div>
@endsection