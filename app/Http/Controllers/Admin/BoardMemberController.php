<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardMemberController extends Controller
{
    public function index(): View
    {
        return view('admin.board.index', ['members' => BoardMember::ordered()->get()]);
    }

    public function create(): View
    {
        return view('admin.board.form', ['member' => new BoardMember]);
    }

    public function store(Request $request): RedirectResponse
    {
        BoardMember::create($this->validated($request));

        return redirect()->route('admin.board.index')->with('success', 'Board member added.');
    }

    public function edit(BoardMember $boardMember): View
    {
        return view('admin.board.form', ['member' => $boardMember]);
    }

    public function update(Request $request, BoardMember $boardMember): RedirectResponse
    {
        $boardMember->update($this->validated($request));

        return redirect()->route('admin.board.index')->with('success', 'Board member updated.');
    }

    public function destroy(BoardMember $boardMember): RedirectResponse
    {
        $boardMember->delete();

        return redirect()->route('admin.board.index')->with('success', 'Board member removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('photo_file')) {
            $data['photo'] = $request->file('photo_file')->store('uploads/board', 'public');
        }

        unset($data['photo_file']);

        return $data;
    }
}
