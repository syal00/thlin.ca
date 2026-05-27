<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        return view('admin.careers.index', ['careers' => Career::orderByDesc('posted_at')->get()]);
    }

    public function create(): View
    {
        return view('admin.careers.form', ['career' => new Career]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);

        Career::create($data);

        return redirect()->route('admin.careers.index')->with('status', 'Job posting created.');
    }

    public function edit(Career $career): View
    {
        return view('admin.careers.form', compact('career'));
    }

    public function update(Request $request, Career $career): RedirectResponse
    {
        $career->update($this->validated($request));

        return redirect()->route('admin.careers.index')->with('status', 'Job posting updated.');
    }

    public function destroy(Career $career): RedirectResponse
    {
        $career->delete();

        return redirect()->route('admin.careers.index')->with('status', 'Job posting deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'max:255'],
            'posted_at' => ['nullable', 'date'],
            'closes_at' => ['nullable', 'date'],
            'body' => ['nullable', 'string'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
