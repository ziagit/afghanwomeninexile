@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Member Card · {{ $verification->first_name }} {{ $verification->last_name }}</h2>
        <a href="{{ route('admin.verifications.index') }}">Back to list</a>
    </div>

    <p class="member-card-help">Standard ID card size: 54 × 85.6 mm (CR80), portrait. Page 1 is the front, page 2 is the back.</p>

    <div class="member-card-actions">
        <a class="btn btn-brick" href="{{ route('admin.verifications.card.pdf', $verification) }}">Download for card printer</a>
        <a class="btn member-card-edit" href="{{ route('admin.verifications.card.pdf', [$verification, 'version' => 'press']) }}">Download for print shop</a>
        <a class="btn member-card-edit" href="{{ route('admin.verifications.edit', $verification) }}">Edit member</a>
    </div>

    <ul class="member-card-tips">
        <li><strong>Card printer</strong> (PVC card printer): the PDF is exactly card size. Choose the CR80 card size in the printer settings and print at <strong>Actual size / 100%</strong>. Never use "Fit to page".</li>
        <li><strong>Print shop</strong>: the PDF has 3 mm bleed and crop marks. Ask them to cut along the crop marks.</li>
    </ul>

    <iframe class="member-card-preview" src="{{ route('admin.verifications.card.pdf', [$verification, 'inline' => 1]) }}" title="Member card preview"></iframe>
</section>
@endsection
