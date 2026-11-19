@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Member Card · {{ $verification->first_name }} {{ $verification->last_name }}</h2>
        <a href="{{ route('admin.verifications.index') }}">Back to list</a>
    </div>

    <p class="member-card-help">Card size 54 × 85.6 mm (standard ID card, portrait). Page 1 is the front, page 2 is the back.</p>

    <div class="member-card-actions">
        <a class="btn btn-brick" href="{{ route('admin.verifications.card.pdf', $verification) }}">Download card (PDF)</a>
        <a class="btn member-card-edit" href="{{ route('admin.verifications.edit', $verification) }}">Edit member</a>
    </div>

    <iframe class="member-card-preview" src="{{ route('admin.verifications.card.pdf', [$verification, 'inline' => 1]) }}" title="Member card preview"></iframe>
</section>
@endsection
