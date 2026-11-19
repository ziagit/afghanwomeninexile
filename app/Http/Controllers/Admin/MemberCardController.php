<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use App\Support\MemberCard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MemberCardController extends Controller
{
    public function show(Verification $verification): View
    {
        return view('admin.verifications.card', [
            'pageTitle' => 'Member Card',
            'verification' => $verification,
        ]);
    }

    public function image(Request $request, Verification $verification, string $side): Response
    {
        $card = new MemberCard($verification, route('verify-member', ['member_id' => $verification->member_id]));
        $png = $side === 'front' ? $card->front() : $card->back();
        $filename = MemberCard::filename($verification, $side);

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => ($request->boolean('download') ? 'attachment' : 'inline').'; filename="'.$filename.'"',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
