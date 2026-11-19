@extends('layouts.admin')

@section('content')
@php
    $filtered = $filters['q'] !== '' || $filters['status'] !== '' || $filters['location'] !== '' || $filters['expiring'];
@endphp
<section class="admin-card">
    <div class="section-head">
        <h2>Members</h2>
        <a href="{{ route('admin.verifications.create') }}">Add new member</a>
    </div>

    <form class="member-filters" method="get" action="{{ route('admin.verifications.index') }}">
        <div class="field member-filters__search">
            <label for="q">Search</label>
            <input id="q" name="q" type="search" value="{{ $filters['q'] }}" placeholder="Name, member ID or location">
        </div>
        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All</option>
                @foreach (['active' => 'Active', 'expired' => 'Expired', 'revoked' => 'Revoked'] as $value => $label)
                    <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="location">Location</label>
            <select id="location" name="location">
                <option value="">All</option>
                @foreach ($locations as $location)
                    <option value="{{ $location }}" @selected($filters['location'] === $location)>{{ $location }}</option>
                @endforeach
            </select>
        </div>
        <label class="member-filters__check">
            <input type="checkbox" name="expiring" value="1" @checked($filters['expiring'])>
            Expiring in 30 days
        </label>
        <div class="member-filters__actions">
            <button type="submit" class="btn btn-brick">Filter</button>
            @if ($filtered)
                <a href="{{ route('admin.verifications.index') }}">Clear</a>
            @endif
        </div>
    </form>

    <p class="member-filters__count">
        @if ($filtered)
            {{ $verifications->total() }} of {{ $total }} {{ Str::plural('member', $total) }} match
        @else
            {{ $total }} {{ Str::plural('member', $total) }}
        @endif
    </p>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Member ID</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Expires</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($verifications as $verification)
                    <tr>
                        <td>{{ $verification->first_name }} {{ $verification->last_name }}</td>
                        <td>{{ $verification->member_id }}</td>
                        <td>{{ $verification->location }}</td>
                        <td><span class="status-pill status-pill--{{ $verification->status() }}">{{ ucfirst($verification->status()) }}</span></td>
                        <td>{{ $verification->expirs_at?->format('Y-m-d') ?? '-' }}</td>
                        <td class="actions-cell">
                            <a href="{{ route('admin.verifications.card', $verification) }}">Card</a>
                            <a href="{{ route('admin.verifications.edit', $verification) }}">Edit</a>
                            <form action="{{ route('admin.verifications.destroy', $verification) }}" method="post" onsubmit="return confirm('Delete this member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">{{ $filtered ? 'No members match these filters.' : 'No members found.' }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($verifications->hasPages())
        <div class="archive-pagination">
            <div class="pagination-strip" aria-label="Members pagination">
                @if ($verifications->onFirstPage())
                    <span class="pagination-btn is-disabled" aria-disabled="true">Prev</span>
                @else
                    <a class="pagination-btn" href="{{ $verifications->previousPageUrl() }}">Prev</a>
                @endif

                @php
                    $startPage = max(1, min($verifications->currentPage() - 1, $verifications->lastPage() - 2));
                    $endPage = min($verifications->lastPage(), $startPage + 2);
                @endphp
                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page === $verifications->currentPage())
                        <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $verifications->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($verifications->hasMorePages())
                    <a class="pagination-btn" href="{{ $verifications->nextPageUrl() }}">Next</a>
                @else
                    <span class="pagination-btn is-disabled" aria-disabled="true">Next</span>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
