<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(): View
    {
        return view('admin.verifications.index', [
            'pageTitle' => 'Manage Members',
            'verifications' => Verification::latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.verifications.create', [
            'pageTitle' => 'Add New Member',
            'verification' => new Verification(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['picture'] = $this->storePicture($request);

        Verification::create($data);

        return redirect()->route('admin.verifications.index')->with('status', 'Member created successfully.');
    }

    public function edit(Verification $verification): View
    {
        return view('admin.verifications.edit', [
            'pageTitle' => 'Edit Member',
            'verification' => $verification,
        ]);
    }

    public function update(Request $request, Verification $verification): RedirectResponse
    {
        $data = $this->validateRequest($request, $verification);

        if ($request->hasFile('picture')) {
            $this->deletePicture($verification->picture);
            $data['picture'] = $this->storePicture($request);
        } else {
            unset($data['picture']);
        }

        $verification->update($data);

        return redirect()->route('admin.verifications.index')->with('status', 'Member updated successfully.');
    }

    public function destroy(Verification $verification): RedirectResponse
    {
        $this->deletePicture($verification->picture);
        $verification->delete();

        return redirect()->route('admin.verifications.index')->with('status', 'Member deleted successfully.');
    }

    private function validateRequest(Request $request, ?Verification $verification = null): array
    {
        return $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'joined_at' => ['required', 'date'],
            'expirs_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
            'picture' => ['nullable', 'image', 'max:1024'],
            'member_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('verifications', 'member_id')->ignore($verification?->id),
            ],
        ], [
            'picture.max' => 'This file size is too large. Please optimize and upload.',
        ]);
    }

    private function storePicture(Request $request): ?string
    {
        if (! $request->hasFile('picture')) {
            return null;
        }

        return $request->file('picture')->store('admin/verifications', 'public');
    }

    private function deletePicture(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
