<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('pages.contact', [
            'pageTitle' => 'Contact',
            'metaDescription' => 'Feel free to contact us regarding our activities and to get involved.',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Mail::to(config('mail.contact.address'), config('mail.contact.name'))
            ->send(new ContactMessage($data));

        return redirect()
            ->route('contact')
            ->with('status', 'Thank you. Your message has been sent successfully.');
    }
}
