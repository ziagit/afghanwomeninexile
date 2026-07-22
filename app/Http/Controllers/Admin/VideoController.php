<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        return view('admin.videos.index', [
            'pageTitle' => 'Manage Videos',
            'videos' => Video::latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.videos.create', [
            'pageTitle' => 'Create Video',
            'video' => new Video(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['thumbnail'] = $this->storeThumbnail($request);

        Video::create($data);

        return redirect()->route('admin.videos.index')->with('status', 'Video created successfully.');
    }

    public function edit(Video $video): View
    {
        return view('admin.videos.edit', [
            'pageTitle' => 'Edit Video',
            'video' => $video,
        ]);
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $data = $this->validateRequest($request);

        if ($request->hasFile('thumbnail')) {
            $this->deleteThumbnail($video->thumbnail);
            $data['thumbnail'] = $this->storeThumbnail($request);
        } else {
            unset($data['thumbnail']);
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('status', 'Video updated successfully.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $this->deleteThumbnail($video->thumbnail);
        $video->delete();

        return redirect()->route('admin.videos.index')->with('status', 'Video deleted successfully.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:25'],
            'video_link' => ['required', 'url', 'max:2048'],
            'details' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:1024'],
        ], [
            'thumbnail.max' => 'This file size is too large. Please optimize and upload.',
        ]);
    }

    private function storeThumbnail(Request $request): ?string
    {
        if (! $request->hasFile('thumbnail')) {
            return null;
        }

        return $request->file('thumbnail')->store('admin/videos', 'public');
    }

    private function deleteThumbnail(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
