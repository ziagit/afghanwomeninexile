@extends('layouts.app')

@section('content')
<x-page-hero :eyebrow="__('Privacy Policy')" :title="__('How we handle your information.')" :description="__('Last updated: 25 November 2026')" />

<section class="tight">
    <div class="wrap policy">
        <p>{{ __('The Movement of Afghan Women in Exile ("we", "us") runs afghanwomeninexile.org and the Facebook Page and Instagram account linked to it. We collect as little personal information as we can, use it only for the purposes below, and never sell it.') }}</p>

        <h2>{{ __('Information we collect') }}</h2>
        <ul>
            <li><strong>{{ __('Contact form:') }}</strong> {{ __('your name, email address and message. These are sent to our email inbox so we can reply to you.') }}</li>
            <li><strong>{{ __('Members:') }}</strong> {{ __('for members of the Movement we keep the details on their membership card (name, date of birth, location, membership dates, photo and member ID), so that cards can be verified on this website.') }}</li>
            <li><strong>{{ __('Facebook and Instagram:') }}</strong> {{ __('when you message, comment on or interact with our Facebook Page or Instagram account, we receive your public name, account ID and the content you send. We use it only to read and reply to you, including with automated replies.') }}</li>
            <li><strong>{{ __('Cookies:') }}</strong> {{ __('we only use cookies needed for the website to work, such as remembering your language and keeping our administrators signed in. We do not use advertising or tracking cookies.') }}</li>
        </ul>

        <h2>{{ __('How we use Meta (Facebook and Instagram)') }}</h2>
        <p>{{ __('Our website uses Meta\'s official tools to publish our own news and activities to our Facebook Page and Instagram account, and to answer messages and comments sent to them. We do not access your private Facebook or Instagram data, and we do not use your information for advertising.') }}</p>

        <h2>{{ __('Who we share information with') }}</h2>
        <p>{{ __('We do not sell or rent personal information. We share it only with services that help us run this website (our web host and email provider) and with Meta when we reply on Facebook or Instagram, or when the law requires it.') }}</p>

        <h2>{{ __('How long we keep it') }}</h2>
        <p>{{ __('We keep messages only as long as needed to answer them, and member records while the membership is active. You can ask us to delete your information at any time.') }}</p>

        <h2 id="data-deletion">{{ __('Deleting your data') }}</h2>
        <p>{{ __('To ask us to delete the information we hold about you, including anything received through Facebook or Instagram, email us with the subject "Data deletion" and tell us your name and, if relevant, your Facebook or Instagram name. We will delete it within 30 days and confirm by email.') }}</p>
        <p><a href="mailto:info@afghanwomeninexile.org?subject=Data%20deletion"><bdi dir="ltr">info@afghanwomeninexile.org</bdi></a></p>
        <p>{{ __('You can also remove our app\'s access at any time in your Facebook settings under "Apps and websites".') }}</p>

        <h2>{{ __('Contact') }}</h2>
        <p>{{ __('For any question about this policy, email us at') }} <a href="mailto:info@afghanwomeninexile.org"><bdi dir="ltr">info@afghanwomeninexile.org</bdi></a> {{ __('or use our') }} <a href="{{ localized_route('contact') }}">{{ __('contact form') }}</a>.</p>
    </div>
</section>
@endsection
