<x-guest-layout>
    <div class="card">
        <div class="card__head">Registration</div>

        <div class="card__body">
            @if ($errors->any())
            <div class="error-box">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-row">
                    <span class="form-icon" aria-hidden="true">
                        <svg viewBox="0 0 512 512" class="auth-svg" aria-hidden="true">
                            <path fill="currentColor" d="M316.275 326.914c-1.545-14.858.59-29.846 3.006-40.885 9.592-10.133 17.803-25.214 26.268-49.096 12.748-4.676 25.696-15.472 31.703-42.072 2.77-12.253-2.041-23.582-10.35-31.74 6.022-20.216 29.73-113.881-41.494-132.874C301.724.641 280.995 1.824 245.466 5.377c-17.674 1.766-20.185 5.524-33.752 3.553-15.559-2.263-28.182-6.985-31.977-8.883-2.186-1.096-22.705 17.113-28.424 31.977-18.11 47.087-13.244 79.381-8.641 103.806-.154 2.501-.359 4.994-.359 7.522l4.222 17.893c.008.184-.004.361.004.545-9.18 8.23-14.715 20.133-11.791 33.073 6.01 26.612 18.967 37.403 31.718 42.077 8.459 23.864 16.662 38.938 26.248 49.072 2.418 11.04 4.554 26.036 3.01 40.904-6.469 62.273-137.504 29.471-137.504 138.814 0 16.862 56.506 46.271 197.779 46.271 141.27 0 197.777-29.409 197.777-46.271 0-109.343-131.035-76.541-137.503-138.814Z" />
                        </svg>
                    </span>
                    <input type="text" name="name" placeholder="Username" value="{{ old('name') }}" required>
                </div>

                <div class="form-row">
                    <span class="form-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" class="auth-svg">
                            <path fill="currentColor" d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z" />
                        </svg>
                    </span>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                </div>

                <div class="form-row">
                    <span class="form-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" class="auth-svg">
                            <path fill="currentColor"
                                d="M12 1a5 5 0 0 0-5 5v4H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12
                                a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zm-3
                                9V6a3 3 0 0 1 6 0v4H9z" />
                            <circle cx="12" cy="16" r="1.5" fill="#fff" />
                        </svg>
                    </span>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="auth-actions">
                    <button class="auth-submit" type="submit">登録</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>