@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="Contact" title="Get in Touch" description="Feel free to contact us regarding our activities and to get involved." />

<section class="tight">
    <div class="wrap contact-grid">
        <div>
            <div class="info-block">
                <span class="eyebrow">Address</span>
                <svg class="info-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-6-4.2-6-10a6 6 0 1112 0c0 5.8-6 10-6 10z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="11" r="2.2" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>
                <div class="val">Islamabad, Pakistan</div>
            </div>
            <div class="info-block">
                <span class="eyebrow">Email Us</span>
                <svg class="info-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4z" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M4 7l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <div class="val">info@afghanwomeninexile.org</div>
            </div>
            <div class="info-block">
                <span class="eyebrow">Call Us</span>
                <svg class="info-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 4.5l2.8 5.4-2 2c1.6 3 3.7 5.1 6.7 6.7l2-2 5.4 2.8c.2 1.9-1.4 3.6-3.3 3.6C11 23 1 13 1 4.7 1 2.8 2.6 1.2 4.5 1.4l3 3.1z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                <div class="val">+92 336 5063509</div>
            </div>
            <div class="info-block">
                <span class="eyebrow">Follow Us</span>
                <div class="social-row">
                    <a href="https://www.facebook.com/profile.php?id=100063624367243" target="_blank" rel="noreferrer" aria-label="Facebook"><x-social-icon type="facebook" /></a>
                    <a href="https://x.com/rezaeifarzana" target="_blank" rel="noreferrer" aria-label="X / Twitter"><x-social-icon type="x" /></a>
                    <a href="https://www.youtube.com/@TheMovementofAfgWomeninExile" target="_blank" rel="noreferrer" aria-label="YouTube"><x-social-icon type="youtube" /></a>
                    <a href="https://www.instagram.com/movement_afg_women_in_exile_" target="_blank" rel="noreferrer" aria-label="Instagram"><x-social-icon type="instagram" /></a>
                </div>
            </div>
        </div>

        <div>
            <form class="contact-form" action="{{ route('contact.send') }}" method="post">
                @csrf
                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required>{{ old('message') }}</textarea>
                    @error('message') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <p class="form-note">Messages are sent directly to our inbox.</p>
                @if(session('status'))
                    <p class="form-note">{{ session('status') }}</p>
                @endif
                <div>
                    <button type="submit" class="btn btn-brick">Submit</button>
                    <div id="formStatus"></div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
