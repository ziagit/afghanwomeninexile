@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>Members</h2>
        <a href="{{ route('admin.verifications.create') }}">Add new member</a>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Member ID</th>
                    <th>Location</th>
                    <th>Joined</th>
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
                        <td>{{ $verification->joined_at?->format('Y-m-d') }}</td>
                        <td>{{ $verification->expirs_at?->format('Y-m-d') ?? '-' }}</td>
                        <td class="actions-cell">
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
                        <td colspan="6">No members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
