@extends('layouts.app')

@section('content')
<section class="auth-screen">
    <div class="auth-panel">
        <div class="auth-mark"><img src="{{ asset('logo.png') }}" alt="Afghan Women in Exile"></div>
        <div class="auth-copy">
            <span class="eyebrow">Admin access</span>
            <h1>Reset password</h1>
        </div>

        <form class="auth-form" method="post" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus>
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="password">New password</label>
                <input id="password" name="password" type="password" required>
                @error('password')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            <button type="submit" class="btn btn-gold auth-button">Reset password</button>
        </form>
    </div>
</section>
@endsection
