<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioItemController extends Controller
{
    public function index(): View
    {
        return view('admin.portfolio.index', ['items' => PortfolioItem::ordered()->paginate(12)]);
    }

    public function create(): View
    {
        return view('admin.portfolio.form', ['item' => new PortfolioItem]);
    }

    public function store(Request $request): RedirectResponse
    {
        PortfolioItem::create($this->validated($request));

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item created.');
    }

    public function edit(PortfolioItem $portfolioItem): View
    {
        return view('admin.portfolio.form', ['item' => $portfolioItem]);
    }

    public function update(Request $request, PortfolioItem $portfolioItem): RedirectResponse
    {
        $portfolioItem->update($this->validated($request));

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item updated.');
    }

    public function destroy(PortfolioItem $portfolioItem): RedirectResponse
    {
        $portfolioItem->delete();

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:500'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('uploads/portfolio', 'public');
        }

        unset($data['image_file']);

        $data['featured'] = $request->boolean('featured');

        return $data;
    }
}
