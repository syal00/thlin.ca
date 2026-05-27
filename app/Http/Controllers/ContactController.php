<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        $page = Page::published()->where('slug', 'contact')->firstOrFail();

        return view('contact.show', compact('page'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $to = config('thlin.contact_email');

        try {
            Mail::raw(
                "Name: {$data['name']}\nEmail: {$data['email']}\nOrganization: ".($data['organization'] ?? '—')."\n\n{$data['message']}",
                fn ($message) => $message->to($to)->replyTo($data['email'])->subject('THLIN website contact: '.$data['name'])
            );
        } catch (\Throwable) {
            // Log-only fallback when mail is not configured locally
            logger()->info('Contact form submission', $data);
        }

        return redirect()->route('contact')->with('status', 'Thank you for your message. We will be in touch soon.');
    }
}
