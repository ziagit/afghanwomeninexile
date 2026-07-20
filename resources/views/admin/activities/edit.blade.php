@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Edit Activity</h2>
        <a href="{{ route('admin.activities.index') }}">Back to list</a>
    </div>

    @include('admin.activities._form', ['activity' => $activity])
</section>
@endsection
