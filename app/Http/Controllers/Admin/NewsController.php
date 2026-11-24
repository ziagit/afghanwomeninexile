<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $news = News::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('body', 'like', '%' . $search . '%')
                        ->orWhere('title_fa', 'like', '%' . $search . '%')
                        ->orWhere('body_fa', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.news.index', [
            'pageTitle' => 'Manage News',
            'news' => $news,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.news.create', [
            'pageTitle' => 'Create News',
            'newsItem' => new News(['published_at' => now()]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['image'] = $this->storeImage($request);

        News::create($data);

        return redirect()->route('admin.news.index')->with('status', 'News created successfully.');
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', [
            'pageTitle' => 'Edit News',
            'newsItem' => $news,
        ]);
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $data = $this->validateRequest($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($news->image);
            $data['image'] = $this->storeImage($request);
        } else {
            unset($data['image']);
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('status', 'News updated successfully.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $this->deleteImage($news->image);
        $news->delete();

        return redirect()->route('admin.news.index')->with('status', 'News deleted successfully.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_fa' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'body_fa' => ['nullable', 'string'],
            'published_at' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:1024'],
        ], [
            'image.max' => 'This file size is too large. Please optimize and upload.',
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('admin/news', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
