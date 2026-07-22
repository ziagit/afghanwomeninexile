<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GaleryController extends Controller
{
    public function index(): View
    {
        return view('admin.galeries.index', [
            'pageTitle' => 'Manage Galeries',
            'galeries' => Galery::latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.galeries.create', [
            'pageTitle' => 'Create Galery',
            'galery' => new Galery(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['picture'] = $this->storePicture($request);

        Galery::create($data);

        return redirect()->route('admin.galeries.index')->with('status', 'Galery created successfully.');
    }

    public function edit(Galery $galery): View
    {
        return view('admin.galeries.edit', [
            'pageTitle' => 'Edit Galery',
            'galery' => $galery,
        ]);
    }

    public function update(Request $request, Galery $galery): RedirectResponse
    {
        $data = $this->validateRequest($request);

        if ($request->hasFile('picture')) {
            $this->deletePicture($galery->picture);
            $data['picture'] = $this->storePicture($request);
        } else {
            unset($data['picture']);
        }

        $galery->update($data);

        return redirect()->route('admin.galeries.index')->with('status', 'Galery updated successfully.');
    }

    public function destroy(Galery $galery): RedirectResponse
    {
        $this->deletePicture($galery->picture);
        $galery->delete();

        return redirect()->route('admin.galeries.index')->with('status', 'Galery deleted successfully.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:25'],
            'details' => ['required', 'string'],
            'picture' => ['nullable', 'image', 'max:1024'],
        ], [
            'picture.max' => 'This file size is too large. Please optimize and upload.',
        ]);
    }

    private function storePicture(Request $request): ?string
    {
        if (! $request->hasFile('picture')) {
            return null;
        }

        return $request->file('picture')->store('admin/galeries', 'public');
    }

    private function deletePicture(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
