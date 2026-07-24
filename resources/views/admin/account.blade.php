@extends('layouts.admin')

@section('content')
<section class="admin-card account-card">
    <div class="section-head">
        <div>
            <h2>Account settings</h2>
            <p>Update your administrator profile and password.</p>
        </div>
    </div>

    <form class="admin-form" method="post" action="{{ route('admin.account.update') }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="account-divider">
            <h3>Change password</h3>
            <p>Leave the password fields blank to keep your current password.</p>
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="current_password">Current password</label>
                <input id="current_password" name="current_password" type="password">
                @error('current_password')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div></div>

            <div class="field">
                <label for="password">New password</label>
                <input id="password" name="password" type="password">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password">
            </div>
        </div>

        <button type="submit" class="btn btn-gold">Save changes</button>
    </form>
</section>
@endsection
