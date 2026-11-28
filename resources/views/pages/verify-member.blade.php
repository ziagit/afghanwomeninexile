@extends('layouts.app')

@section('content')
<x-page-hero :eyebrow="__('Verification')" :title="__('Verify a Member')" :description="__('Scan the QR code on a member card, or enter a member ID to check whether the membership is valid.')" />

@php
    $status = $verification?->status();
    $statusStyles = [
        'active' => ['class' => 'ok', 'badge' => __('Active member'), 'icon' => '✓'],
        'expired' => ['class' => 'warn', 'badge' => __('Membership expired'), 'icon' => '!'],
        'revoked' => ['class' => 'bad', 'badge' => __('Membership revoked'), 'icon' => '✕'],
    ];
@endphp

<section class="tight verify-page">
    <div class="wrap">
        <div class="verify-layout">
            <div class="verify-search-card">
                <span class="eyebrow">{{ __('Member records') }}</span>
                <h2>{{ __('Check membership') }}</h2>
                <p>{{ __('Enter the member ID printed on the card. Scanning the card\'s QR code also shows the member\'s photo.') }}</p>

                <form class="verify-form" method="get" action="{{ localized_route('verify-member') }}">
                    <div class="field">
                        <label for="member_id">{{ __('Member ID') }}</label>
                        <input id="member_id" name="member_id" type="text" value="{{ $memberId }}" placeholder="{{ __('Enter member ID') }}" autocomplete="off" required>
                    </div>
                    <button type="submit" class="btn btn-brick">{{ __('Verify Member') }}</button>
                </form>
            </div>

            @if ($verification)
                @php
                    $style = $statusStyles[$status];
                @endphp
                <div class="verify-result verify-result--{{ $style['class'] }}">
                    <div class="verify-result__header">
                        <span class="verify-badge verify-badge--{{ $style['class'] }}">{{ $style['badge'] }}</span>
                        <span class="verify-check verify-check--{{ $style['class'] }}" aria-hidden="true">{{ $style['icon'] }}</span>
                    </div>
                    <div class="verify-member-card {{ $showPhoto ? '' : 'verify-member-card--no-photo' }}">
                        @if ($showPhoto)
                            <div class="verify-member-photo">
                                @if ($verification->picture)
                                    <img src="{{ asset('storage/'.$verification->picture) }}" alt="{{ $verification->first_name }} {{ $verification->last_name }}">
                                @else
                                    <x-media-placeholder :label="__('No member picture')" />
                                @endif
                            </div>
                        @endif
                        <div class="verify-member-info">
                            <h2>{{ $verification->first_name }} {{ $verification->last_name }}</h2>
                            <p class="verify-member-id"><bdi dir="ltr">{{ $verification->member_id }}</bdi></p>
                            <dl class="verify-details">
                                <div><dt>{{ __('Role') }}</dt><dd>{{ $verification->role ?: \App\Models\Verification::DEFAULT_ROLE }}</dd></div>
                                <div><dt>{{ __('Status') }}</dt><dd>{{ $style['badge'] }}</dd></div>
                                <div><dt>{{ __('Valid until') }}</dt><dd>{{ $verification->expirs_at?->translatedFormat('j F Y') ?? __('No expiry date') }}</dd></div>
                            </dl>
                            @if ($status === 'revoked')
                                <p class="verify-warning">{{ __('This membership is no longer valid. Please do not accept this card.') }}</p>
                            @elseif ($status === 'expired')
                                <p class="verify-warning">{{ __('This card has passed its expiry date and needs to be renewed.') }}</p>
                            @elseif (! $showPhoto)
                                <p class="verify-hint">{{ __('To see the member\'s photo, scan the QR code on the card.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif ($replacedCard)
                <div class="verify-result verify-result--bad">
                    <span class="verify-badge verify-badge--bad">{{ __('Card replaced') }}</span>
                    <h2>{{ __('This card is no longer valid') }}</h2>
                    <p>{{ __('It was reported lost or replaced with a new card. Please do not accept it.') }}</p>
                </div>
            @elseif ($memberId !== '' || isset($scanned))
                <div class="verify-result verify-result--bad">
                    <span class="verify-badge verify-badge--bad">{{ __('No match') }}</span>
                    <h2>{{ __('No record found') }}</h2>
                    @if ($memberId !== '')
                        <p>{!! __('We could not find a member with the ID :id.', ['id' => '<strong>'.e($memberId).'</strong>']) !!}</p>
                    @else
                        <p>{{ __('This QR code does not belong to a valid member card.') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
