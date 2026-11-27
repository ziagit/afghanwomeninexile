<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ShareToFacebook;
use App\Models\Activity;
use App\Services\FacebookPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $activities = Activity::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('type', 'like', '%' . $search . '%')
                        ->orWhere('title', 'like', '%' . $search . '%')
                        ->orWhere('body', 'like', '%' . $search . '%')
                        ->orWhere('name_fa', 'like', '%' . $search . '%')
                        ->orWhere('title_fa', 'like', '%' . $search . '%')
                        ->orWhere('body_fa', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('published_at')
            ->latest()
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.activities.index', [
            'pageTitle' => 'Manage Activities',
            'activities' => $activities,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.activities.create', [
            'pageTitle' => 'Create Activity',
            'activity' => new Activity(['type' => 'general', 'published_at' => now()]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['type'] = $data['type'] ?: 'general';
        $data['image'] = $this->storeImage($request);

        $activity = Activity::create($data);

        return redirect()->route('admin.activities.index')->with('status', 'Activity created successfully.' . $this->shareToFacebook($request, $activity));
    }

    public function edit(Activity $activity): View
    {
        return view('admin.activities.edit', [
            'pageTitle' => 'Edit Activity',
            'activity' => $activity,
        ]);
    }

    public function update(Request $request, Activity $activity): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['type'] = $data['type'] ?: 'general';

        if ($request->hasFile('image')) {
            $this->deleteImage($activity->image);
            $data['image'] = $this->storeImage($request);
        } else {
            unset($data['image']);
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')->with('status', 'Activity updated successfully.' . $this->shareToFacebook($request, $activity));
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $this->deleteImage($activity->image);
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('status', 'Activity deleted successfully.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_fa' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'published_at' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'title_fa' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'body_fa' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
        ], [
            'image.max' => 'This file size is too large. Please optimize and upload.',
        ]);
    }

    // Queues the Facebook post when "Share to Facebook" was ticked; returns a note for the status message.
    private function shareToFacebook(Request $request, Activity $activity): string
    {
        if (! $request->boolean('share_facebook') || $activity->facebook_post_id) {
            return '';
        }

        if (! FacebookPage::isConfigured()) {
            return ' Facebook is not configured, so it was not shared.';
        }

        ShareToFacebook::dispatch($activity);

        return ' It will be shared to Facebook shortly.';
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('admin/activities', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
