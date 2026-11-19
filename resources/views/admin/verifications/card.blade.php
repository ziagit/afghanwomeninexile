@extends('layouts.admin')

@section('content')
@php
    $sides = ['front' => 'Front', 'back' => 'Back'];
    $downloads = collect(array_keys($sides))
        ->map(fn ($side) => [
            'url' => route('admin.verifications.card.image', [$verification, $side, 'download' => 1]),
            'name' => \App\Support\MemberCard::filename($verification, $side),
        ])
        ->all();
@endphp
<section class="admin-card member-card-page">
    <div class="section-head">
        <h2>{{ $verification->first_name }} {{ $verification->last_name }}</h2>
        <a href="{{ route('admin.verifications.index') }}">Back to members</a>
    </div>

    <div class="member-card-sides">
        @foreach ($sides as $side => $label)
            <figure>
                <img src="{{ route('admin.verifications.card.image', [$verification, $side]) }}" alt="{{ $label }} of member card" width="1276" height="2022">
                <figcaption>{{ $label }}</figcaption>
            </figure>
        @endforeach
    </div>

    <div class="member-card-download">
        <button type="button" class="btn btn-brick" id="member-card-download"
            data-files='@json($downloads)'>
            Download card
        </button>
        <p>2 PNG files (front and back) · 54 × 85.6 mm at 600 DPI · print at 100%</p>
    </div>

    <div class="member-card-replace">
        <div>
            <strong>Card lost or stolen?</strong>
            <p>Replacing the card gives it a new QR code. The old card will show as "no longer valid" when scanned.@if (count($verification->replaced_card_tokens ?? [])) This member's card has been replaced {{ count($verification->replaced_card_tokens) }} {{ Str::plural('time', count($verification->replaced_card_tokens)) }}.@endif</p>
        </div>
        <form method="post" action="{{ route('admin.verifications.card.replace', $verification) }}" onsubmit="return confirm('Replace this card? The current printed card will stop verifying.');">
            @csrf
            <button type="submit" class="btn btn-outline-dark">Replace card</button>
        </form>
    </div>
</section>

<script>
    document.getElementById('member-card-download').addEventListener('click', function () {
        JSON.parse(this.dataset.files).forEach(function (file, index) {
            setTimeout(function () {
                var link = document.createElement('a');
                link.href = file.url;
                link.download = file.name;
                document.body.appendChild(link);
                link.click();
                link.remove();
            }, index * 600);
        });
    });
</script>
@endsection
