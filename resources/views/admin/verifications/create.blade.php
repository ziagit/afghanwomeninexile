@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Add New Member</h2>
        <a href="{{ route('admin.verifications.index') }}">Back to list</a>
    </div>

    @include('admin.verifications._form', ['verification' => $verification])
</section>
@endsection
