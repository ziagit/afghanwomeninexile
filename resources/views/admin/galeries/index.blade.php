@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Galeries</h2>
        <a href="{{ route('admin.galeries.create') }}">Create galery</a>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($galeries as $galery)
                    <tr>
                        <td>{{ $galery->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($galery->details, 80) }}</td>
                        <td>
                            @if ($galery->picture)
                                <a href="{{ asset('storage/'.$galery->picture) }}" target="_blank" rel="noreferrer">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="actions-cell">
                            <a href="{{ route('admin.galeries.edit', $galery) }}">Edit</a>
                            <form action="{{ route('admin.galeries.destroy', $galery) }}" method="post" onsubmit="return confirm('Delete this galery?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No galeries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>
@endsection
