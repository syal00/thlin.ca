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

        return redirect()->route('admin.board.index')->with('status', 'Board member added.');
    }

    public function edit(BoardMember $boardMember): View
    {
        return view('admin.board.form', ['member' => $boardMember]);
    }

    public function update(Request $request, BoardMember $boardMember): RedirectResponse
    {
        $boardMember->update($this->validated($request));

        return redirect()->route('admin.board.index')->with('status', 'Board member updated.');
    }

    public function destroy(BoardMember $boardMember): RedirectResponse
    {
        $boardMember->delete();

        return redirect()->route('admin.board.index')->with('status', 'Board member removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
