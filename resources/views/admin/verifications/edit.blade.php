@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Edit Member</h2>
        <a href="{{ route('admin.verifications.index') }}">Back to list</a>
    </div>

    @include('admin.verifications._form', ['verification' => $verification])
</section>

<section class="admin-card membership-status">
    <div class="section-head">
        <h2>Membership status</h2>
        <span class="status-pill status-pill--{{ $verification->status() }}">{{ ucfirst($verification->status()) }}</span>
    </div>

    @if ($verification->revoked_at)
        <p class="form-note">
            Revoked on {{ $verification->revoked_at->format('j M Y') }}@if ($verification->revoked_reason): {{ $verification->revoked_reason }}@endif.
            Anyone checking this member's card sees that the membership is no longer valid.
        </p>
        <form method="post" action="{{ route('admin.verifications.restore', $verification) }}" class="membership-status__form">
            @csrf
            <button type="submit" class="btn btn-outline-dark">Restore membership</button>
        </form>
    @else
        <p class="form-note">Revoke the membership if this person is no longer a member. Their card will show as revoked when anyone checks it. You can restore it later.</p>
        <form method="post" action="{{ route('admin.verifications.revoke', $verification) }}" class="membership-status__form" onsubmit="return confirm('Revoke this membership?');">
            @csrf
            <div class="field">
                <label for="revoked_reason">Reason (only visible to admins)</label>
                <input id="revoked_reason" name="revoked_reason" type="text" maxlength="255" placeholder="Optional">
            </div>
            <button type="submit" class="btn btn-brick">Revoke membership</button>
        </form>
    @endif
</section>
@endsection
