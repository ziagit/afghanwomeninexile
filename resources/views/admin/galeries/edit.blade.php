@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Edit Galery</h2>
        <a href="{{ route('admin.galeries.index') }}">Back to list</a>
    </div>

    @include('admin.galeries._form', ['galery' => $galery])
</section>
@endsection
