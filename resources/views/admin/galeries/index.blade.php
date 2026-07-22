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

    @if ($galeries->hasPages())
        <div class="archive-pagination">
            <div class="pagination-strip" aria-label="Galeries pagination">
                @if ($galeries->onFirstPage())
                    <span class="pagination-btn is-disabled" aria-disabled="true">Prev</span>
                @else
                    <a class="pagination-btn" href="{{ $galeries->previousPageUrl() }}">Prev</a>
                @endif

                @for ($page = 1; $page <= $galeries->lastPage(); $page++)
                    @if ($page === $galeries->currentPage())
                        <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $galeries->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($galeries->hasMorePages())
                    <a class="pagination-btn" href="{{ $galeries->nextPageUrl() }}">Next</a>
                @else
                    <span class="pagination-btn is-disabled" aria-disabled="true">Next</span>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
