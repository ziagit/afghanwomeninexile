@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Activities</h2>
        <a href="{{ route('admin.activities.create') }}">Create activity</a>
    </div>

    <form class="admin-search" method="get" action="{{ route('admin.activities.index') }}">
        <label class="sr-only" for="activity-search">Search activities</label>
        <input id="activity-search" name="search" type="search" value="{{ $search }}" placeholder="Search by name, type, title, or content">
        <button class="btn btn-brick" type="submit">Search</button>
        @if ($search !== '')
            <a class="btn btn-outline" href="{{ route('admin.activities.index') }}">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activities as $activity)
                    <tr>
                        <td>{{ $activity->name }}</td>
                        <td>{{ $activity->type }}</td>
                        <td>{{ $activity->title }}</td>
                        <td>
                            @if ($activity->image)
                                <a href="{{ asset('storage/'.$activity->image) }}" target="_blank" rel="noreferrer">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="actions-cell">
                            <a href="{{ route('admin.activities.edit', $activity) }}">Edit</a>
                            <form action="{{ route('admin.activities.destroy', $activity) }}" method="post" onsubmit="return confirm('Delete this activity?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No activities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($activities->hasPages())
        <div class="archive-pagination">
            <div class="pagination-strip" aria-label="Activities pagination">
                @if ($activities->onFirstPage())
                    <span class="pagination-btn is-disabled" aria-disabled="true">Prev</span>
                @else
                    <a class="pagination-btn" href="{{ $activities->previousPageUrl() }}">Prev</a>
                @endif

                @php
                    $startPage = max(1, min($activities->currentPage() - 1, $activities->lastPage() - 2));
                    $endPage = min($activities->lastPage(), $startPage + 2);
                @endphp
                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page === $activities->currentPage())
                        <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $activities->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($activities->hasMorePages())
                    <a class="pagination-btn" href="{{ $activities->nextPageUrl() }}">Next</a>
                @else
                    <span class="pagination-btn is-disabled" aria-disabled="true">Next</span>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
