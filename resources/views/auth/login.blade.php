@extends('layouts.app')

@section('content')
<section class="auth-screen">
    <div class="auth-panel">
        <div class="auth-mark">A</div>
        <div class="auth-copy">
            <span class="eyebrow">Admin access</span>
            <h1>Sign in</h1>
        </div>

        <form class="auth-form" method="post" action="{{ route('login.store') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
                @error('password')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <label class="remember-row">
                <input type="checkbox" name="remember" value="1">
                <span>Remember me</span>
            </label>

            <button type="submit" class="btn btn-gold auth-button">Login</button>
        </form>
    </div>
</section>
@endsection
