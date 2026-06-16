<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewResponse;

class ContactController extends Controller
{
    public function index(): ViewResponse
    {
        $page = Page::published()->where('slug', 'contact')->first();

        if ($page) {
            View::share('cmsPage', $page);
        }

        return view('contact.show', compact('page'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        return back()->with('success', 'Thank you. Your message has been received.');
    }
}