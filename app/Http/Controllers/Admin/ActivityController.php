<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
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
                        ->orWhere('body', 'like', '%' . $search . '%');
                });
            })
            ->latest()
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
            'activity' => new Activity(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['image'] = $this->storeImage($request);

        Activity::create($data);

        return redirect()->route('admin.activities.index')->with('status', 'Activity created successfully.');
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

        if ($request->hasFile('image')) {
            $this->deleteImage($activity->image);
            $data['image'] = $this->storeImage($request);
        } else {
            unset($data['image']);
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')->with('status', 'Activity updated successfully.');
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
            'type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
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

        return $request->file('image')->store('admin/activities', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
