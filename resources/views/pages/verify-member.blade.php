@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="Verification" title="Verify a Member" description="Enter a member ID to check whether a verification record exists in our system." />

<section class="tight">
    <div class="wrap">
        <div class="verify-panel">
            <form class="verify-form" method="get" action="{{ route('verify-member') }}">
                <div class="field">
                    <label for="member_id">Member ID</label>
                    <input id="member_id" name="member_id" type="text" value="{{ $memberId }}" placeholder="Enter member ID">
                </div>
                <button type="submit" class="btn btn-brick">Verify Member</button>
            </form>

            @if ($memberId !== '')
                @if ($verification)
                    <div class="verify-result verify-result--ok">
                        <h2>{{ $verification->first_name }} {{ $verification->last_name }}</h2>
                        <p><strong>Status:</strong> Verified</p>
                        <p><strong>Member ID:</strong> {{ $verification->member_id }}</p>
                        <p><strong>Location:</strong> {{ $verification->location }}</p>
                        <p><strong>Joined:</strong> {{ $verification->joined_at?->format('F j, Y') }}</p>
                        <p><strong>Expires:</strong> {{ $verification->expirs_at?->format('F j, Y') ?? 'No expiry set' }}</p>
                        @if ($verification->note)
                            <p><strong>Note:</strong> {{ $verification->note }}</p>
                        @endif
                    </div>
                @else
                    <div class="verify-result verify-result--bad">
                        <h2>No record found</h2>
                        <p>We could not find a verification entry for member ID <strong>{{ $memberId }}</strong>.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</section>
@endsection
