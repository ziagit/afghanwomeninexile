<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(Request $request): View
    {
        $memberId = $request->string('member_id')->trim()->toString();

        return view('pages.verify-member', [
            'pageTitle' => 'Verify Member',
            'memberId' => $memberId,
            'verification' => $memberId !== '' ? Verification::where('member_id', $memberId)->first() : null,
        ]);
    }
}
