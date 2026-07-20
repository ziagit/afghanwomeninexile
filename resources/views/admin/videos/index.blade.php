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

</section>
@endsection
