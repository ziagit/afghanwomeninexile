@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Edit Video</h2>
        <a href="{{ route('admin.videos.index') }}">Back to list</a>
    </div>

    @include('admin.videos._form', ['video' => $video])
</section>
@endsection
