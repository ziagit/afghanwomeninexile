@extends('layouts.app')

@section('content')
<section class="auth-screen">
    <div class="auth-panel">
        <div class="auth-mark"><img src="{{ asset('logo.png') }}" alt="Afghan Women in Exile"></div>
        <div class="auth-copy">
            <span class="eyebrow">Admin access</span>
            <h1>Forgot password?</h1>
            <p>Enter your email address and we’ll send you a password reset link.</p>
        </div>

        @if (session('status'))
            <div class="form-status">{{ session('status') }}</div>
        @endif

        <form class="auth-form" method="post" action="{{ route('password.email') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-gold auth-button">Email reset link</button>
            <a class="auth-link" href="{{ route('login') }}">Back to login</a>
        </form>
    </div>
</section>
@endsection
