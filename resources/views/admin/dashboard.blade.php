@extends('layouts.admin')

@section('content')
<div class="admin-grid">
    <div class="admin-card stat-card">
        <span class="eyebrow">Activities</span>
        <strong>{{ $activityCount }}</strong>
        <a href="{{ route('admin.activities.index') }}">Manage activities</a>
    </div>
    <div class="admin-card stat-card">
        <span class="eyebrow">News</span>
        <strong>{{ $newsCount }}</strong>
        <a href="{{ route('admin.news.index') }}">Manage news</a>
    </div>
    <div class="admin-card stat-card">
        <span class="eyebrow">Galeries</span>
        <strong>{{ $galeryCount }}</strong>
        <a href="{{ route('admin.galeries.index') }}">Manage galeries</a>
    </div>
</div>
</div>

<div class="admin-grid admin-grid--two">
    <section class="admin-card">
        <div class="section-head">
            <h2>Recent activities</h2>
            <a href="{{ route('admin.activities.create') }}">New activity</a>
        </div>
        <div class="stack-list">
            @forelse ($recentActivities as $activity)
                <article class="stack-item">
                    <strong>{{ $activity->title }}</strong>
                    <span>{{ $activity->type }} · {{ $activity->name }}</span>
                </article>
            @empty
                <p>No activities yet.</p>
            @endforelse
        </div>
    </section>

    <section class="admin-card">
        <div class="section-head">
            <h2>Recent galeries</h2>
            <a href="{{ route('admin.galeries.create') }}">New galery</a>
        </div>
        <div class="stack-list">
            @forelse ($recentGaleries as $galery)
                <article class="stack-item">
                    <strong>{{ $galery->name }}</strong>
                    <span>{{ \Illuminate\Support\Str::limit($galery->details, 90) }}</span>
                </article>
            @empty
                <p>No galeries yet.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
