@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Create News</h2>
        <a href="{{ route('admin.news.index') }}">Back to list</a>
    </div>

    @include('admin.news._form', ['newsItem' => $newsItem])
</section>
@endsection
