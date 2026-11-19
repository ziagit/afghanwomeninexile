<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    // Lookup by typed member ID: confirms the name and status only, so IDs can't be tried one by one to collect photos.
    public function index(Request $request): View
    {
        $memberId = $request->string('member_id')->trim()->toString();

        return view('pages.verify-member', [
            'pageTitle' => __('Verify Member'),
            'memberId' => $memberId,
            'verification' => $memberId !== '' ? Verification::where('member_id', $memberId)->first() : null,
            'showPhoto' => false,
            'replacedCard' => false,
        ]);
    }

    // Lookup from a card's QR code: the secret token proves the card was issued, so the photo is shown.
    public function card(string $token): View
    {
        $verification = Verification::where('card_token', $token)->first();

        return view('pages.verify-member', [
            'pageTitle' => __('Verify Member'),
            'memberId' => $verification?->member_id ?? '',
            'verification' => $verification,
            'showPhoto' => true,
            'replacedCard' => ! $verification && Verification::withReplacedCardToken($token)->exists(),
            'scanned' => true,
        ]);
    }
}
