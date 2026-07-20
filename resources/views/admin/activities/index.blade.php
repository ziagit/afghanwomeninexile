@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Activities</h2>
        <a href="{{ route('admin.activities.create') }}">Create activity</a>
    </div>

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

</section>
@endsection
