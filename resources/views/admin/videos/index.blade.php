@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Videos</h2>
        <a href="{{ route('admin.videos.create') }}">Create video</a>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Video link</th>
                    <th>Thumbnail</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($videos as $video)
                    <tr>
                        <td>{{ $video->name }}</td>
                        <td><a href="{{ $video->video_link }}" target="_blank" rel="noreferrer">{{ $video->video_link }}</a></td>
                        <td>
                            @if ($video->thumbnail)
                                <a href="{{ asset('storage/'.$video->thumbnail) }}" target="_blank" rel="noreferrer">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="actions-cell">
                            <a href="{{ route('admin.videos.edit', $video) }}">Edit</a>
                            <form action="{{ route('admin.videos.destroy', $video) }}" method="post" onsubmit="return confirm('Delete this video?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No videos found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($videos->hasPages())
        <div class="archive-pagination">
            <div class="pagination-strip" aria-label="Videos pagination">
                @if ($videos->onFirstPage())
                    <span class="pagination-btn is-disabled" aria-disabled="true">Prev</span>
                @else
                    <a class="pagination-btn" href="{{ $videos->previousPageUrl() }}">Prev</a>
                @endif

                @for ($page = 1; $page <= $videos->lastPage(); $page++)
                    @if ($page === $videos->currentPage())
                        <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $videos->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($videos->hasMorePages())
                    <a class="pagination-btn" href="{{ $videos->nextPageUrl() }}">Next</a>
                @else
                    <span class="pagination-btn is-disabled" aria-disabled="true">Next</span>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
