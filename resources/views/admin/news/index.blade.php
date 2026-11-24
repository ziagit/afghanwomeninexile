@extends('layouts.admin')

@section('content')
<section class="admin-card">
    <div class="section-head">
        <h2>News</h2>
        <a href="{{ route('admin.news.create') }}">Create news</a>
    </div>

    <form class="admin-search" method="get" action="{{ route('admin.news.index') }}">
        <label class="sr-only" for="news-search">Search news</label>
        <input id="news-search" name="search" type="search" value="{{ $search }}" placeholder="Search by title or content">
        <button class="btn btn-brick" type="submit">Search</button>
        @if ($search !== '')
            <a class="btn btn-outline" href="{{ route('admin.news.index') }}">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Dari</th>
                    <th>Image</th>
                    <th>Facebook</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($news as $newsItem)
                    <tr>
                        <td>{{ $newsItem->published_at?->format('j M Y') }}</td>
                        <td>{{ $newsItem->title }}</td>
                        <td>{{ filled($newsItem->title_fa) ? 'Yes' : '-' }}</td>
                        <td>
                            @if ($newsItem->image)
                                <a href="{{ asset('storage/'.$newsItem->image) }}" target="_blank" rel="noreferrer">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>@include('admin.partials.facebook-status', ['item' => $newsItem])</td>
                        <td class="actions-cell">
                            <a href="{{ route('admin.news.edit', $newsItem) }}">Edit</a>
                            <form action="{{ route('admin.news.destroy', $newsItem) }}" method="post" onsubmit="return confirm('Delete this news?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No news found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($news->hasPages())
        <div class="archive-pagination">
            <div class="pagination-strip" aria-label="News pagination">
                @if ($news->onFirstPage())
                    <span class="pagination-btn is-disabled" aria-disabled="true">Prev</span>
                @else
                    <a class="pagination-btn" href="{{ $news->previousPageUrl() }}">Prev</a>
                @endif

                @php
                    $startPage = max(1, min($news->currentPage() - 1, $news->lastPage() - 2));
                    $endPage = min($news->lastPage(), $startPage + 2);
                @endphp
                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page === $news->currentPage())
                        <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $news->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($news->hasMorePages())
                    <a class="pagination-btn" href="{{ $news->nextPageUrl() }}">Next</a>
                @else
                    <span class="pagination-btn is-disabled" aria-disabled="true">Next</span>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
