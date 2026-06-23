<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        return view('admin.messages.index', [
            'messages' => ContactMessage::query()->latest('created_at')->paginate(12),
        ]);
    }

    public function show(ContactMessage $message): View
    {
        return view('admin.messages.show', [
            'message' => $message,
        ]);
    }

    public function markRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['status' => 'read']);

        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        ContactMessage::destroy($message->getKey());

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }
}