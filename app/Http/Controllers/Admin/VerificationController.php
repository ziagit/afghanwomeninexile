<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'q' => $request->string('q')->trim()->toString(),
            'status' => $request->string('status')->toString(),
            'location' => $request->string('location')->toString(),
            'expiring' => $request->boolean('expiring'),
        ];

        $verifications = Verification::query()
            ->when($filters['q'] !== '', function (Builder $query) use ($filters) {
                $term = '%'.$filters['q'].'%';
                $query->where(fn (Builder $query) => $query
                    ->where('member_id', 'like', $term)
                    ->orWhere('location', 'like', $term)
                    ->orWhere('role', 'like', $term)
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$term]));
            })
            ->when($filters['status'] !== '', fn (Builder $query) => $query->withStatus($filters['status']))
            ->when($filters['location'] !== '', fn (Builder $query) => $query->where('location', $filters['location']))
            ->when($filters['expiring'], fn (Builder $query) => $query
                ->withStatus(Verification::STATUS_ACTIVE)
                ->whereBetween('expirs_at', [now()->startOfDay(), now()->addDays(30)->endOfDay()])
                ->reorder('expirs_at'))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.verifications.index', [
            'pageTitle' => 'Manage Members',
            'verifications' => $verifications,
            'filters' => $filters,
            'locations' => Verification::query()->distinct()->orderBy('location')->pluck('location'),
            'total' => Verification::count(),
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

        $verification = Verification::create($data);

        return redirect()->route('admin.verifications.card', $verification)->with('status', 'Member created successfully. Their card is ready to download.');
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

    public function revoke(Request $request, Verification $verification): RedirectResponse
    {
        $data = $request->validate([
            'revoked_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $verification->revoked_at = now();
        $verification->revoked_reason = $data['revoked_reason'] ?? null;
        $verification->save();

        return redirect()->route('admin.verifications.edit', $verification)->with('status', 'Membership revoked. The card now shows as revoked when checked.');
    }

    public function restore(Verification $verification): RedirectResponse
    {
        $verification->revoked_at = null;
        $verification->revoked_reason = null;
        $verification->save();

        return redirect()->route('admin.verifications.edit', $verification)->with('status', 'Membership restored.');
    }

    private function validateRequest(Request $request, ?Verification $verification = null): array
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
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

        $role = trim((string) ($data['role'] ?? ''));
        $data['role'] = $role !== '' ? $role : Verification::DEFAULT_ROLE;

        return $data;
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
