@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="Verification" title="Verify a Member" description="Enter a member ID to check whether a verification record exists in our system." />

<section class="tight verify-page">
    <div class="wrap">
        <div class="verify-layout">
            <div class="verify-search-card">
                <span class="eyebrow">Member records</span>
                <h2>Check membership</h2>
                <p>Use the member ID provided by MAWIE to confirm an active verification record.</p>

                <form class="verify-form" method="get" action="{{ route('verify-member') }}">
                    <div class="field">
                        <label for="member_id">Member ID</label>
                        <input id="member_id" name="member_id" type="text" value="{{ $memberId }}" placeholder="Enter member ID" autocomplete="off" required>
                    </div>
                    <button type="submit" class="btn btn-brick">Verify Member</button>
                </form>
            </div>

            @if ($memberId !== '')
                @if ($verification)
                    <div class="verify-result verify-result--ok">
                        <div class="verify-result__header">
                            <span class="verify-badge">Verified member</span>
                            <span class="verify-check" aria-hidden="true">✓</span>
                        </div>
                        <div class="verify-member-card">
                            <div class="verify-member-photo">
                                @if ($verification->picture)
                                    <img src="{{ asset('storage/'.$verification->picture) }}" alt="{{ $verification->first_name }} {{ $verification->last_name }}" loading="lazy">
                                @else
                                    <x-media-placeholder label="No member picture" />
                                @endif
                            </div>
                            <div class="verify-member-info">
                                <h2>{{ $verification->first_name }} {{ $verification->last_name }}</h2>
                                <p class="verify-member-id">{{ $verification->member_id }}</p>
                                <dl class="verify-details">
                                    <div><dt>Location</dt><dd>{{ $verification->location }}</dd></div>
                                    <div><dt>Joined</dt><dd>{{ $verification->joined_at?->format('F j, Y') }}</dd></div>
                                    <div><dt>Expires</dt><dd>{{ $verification->expirs_at?->format('F j, Y') ?? 'No expiry set' }}</dd></div>
                                </dl>
                                @if ($verification->note)
                                    <div class="verify-note"><span>Note</span><p>{{ $verification->note }}</p></div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="verify-result verify-result--bad">
                        <span class="verify-badge verify-badge--bad">No match</span>
                        <h2>No record found</h2>
                        <p>We could not find a verification entry for member ID <strong>{{ $memberId }}</strong>.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</section>
@endsection
