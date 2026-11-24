@extends('layouts.app')

@section('content')
<x-page-hero :eyebrow="__('Support Us')" :title="__('Stand with Afghan women in exile.')" :description="__('Your support helps provide shelter, legal support, and education so Afghan women in exile can keep moving forward.')" />

<section class="tight">
    <div class="wrap">
        <div class="support-impact">
            @foreach ([
                ['title' => __('Shelter'), 'text' => __('Safe housing and emergency help for women and families who were forced to leave Afghanistan.')],
                ['title' => __('Legal support'), 'text' => __('Guidance with asylum, residency, and documents, so women can stay safe and plan their future.')],
                ['title' => __('Education'), 'text' => __('Classes and learning opportunities for women and girls who were denied education under Taliban rule.')],
            ] as $item)
                <div class="support-impact__item">
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="support-donate">
    <div class="wrap">
        <span class="eyebrow">{{ __('Donate') }}</span>
        <h2>{{ __('Your donation is a lifeline.') }}</h2>

        <div class="support-donate__options">
            @if ($donationUrl)
                <div class="support-card">
                    <h3>{{ __('Donate online') }}</h3>
                    <p>{{ __('Make a secure donation by card or online payment.') }}</p>
                    <a class="btn btn-brick" href="{{ $donationUrl }}" target="_blank" rel="noopener noreferrer">{{ __('Support Us') }}</a>
                </div>
            @endif

            @if ($bankDetails)
                <div class="support-card">
                    <h3>{{ __('Bank transfer') }}</h3>
                    <dl class="support-bank">
                        @foreach ($bankDetails as $label => $value)
                            <div><dt>{{ __($label) }}</dt><dd>{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                </div>
            @endif

            @if (! $donationUrl && ! $bankDetails)
                <div class="support-card">
                    <h3>{{ __('How to donate') }}</h3>
                    <p>{{ __('Please contact us and we will send you the details for making a donation.') }}</p>
                    <a class="btn btn-brick" href="{{ localized_route('contact') }}">{{ __('Contact us') }}</a>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="tight">
    <div class="wrap">
        <span class="eyebrow support-eyebrow">{{ __('Other ways to help') }}</span>
        <div class="support-impact">
            @foreach ([
                ['title' => __('Volunteer'), 'text' => __('Share your time and skills: teaching, translation, legal knowledge, design, or event support.')],
                ['title' => __('Share our statements'), 'text' => __('Follow us and share our statements so the voices of Afghan women reach more people.')],
                ['title' => __('Partner with us'), 'text' => __('Organizations and institutions can work with us on advocacy, education, and humanitarian projects.')],
            ] as $item)
                <div class="support-impact__item">
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['text'] }}</p>
                    <a class="more" href="{{ localized_route('contact') }}">{{ __('Get in touch →') }}</a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
