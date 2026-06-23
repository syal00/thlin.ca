<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    /** @var array<string, string> */
    private const GROUP_LABELS = [
        'navigation' => 'Navigation labels',
        'footer' => 'Footer',
        'cta' => 'Global call-to-action',
        'home' => 'Homepage',
        'contact' => 'Contact page',
        'portfolio' => 'Portfolio page',
    ];

    public function index(): View
    {
        $groups = SiteSetting::query()
            ->orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');

        return view('admin.settings.index', [
            'groups' => $groups,
            'groupLabels' => self::GROUP_LABELS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $input = $request->input('settings', []);

        if (! is_array($input)) {
            return back()->with('error', 'Invalid settings payload.');
        }

        $known = SiteSetting::query()->get()->keyBy('key');
        $updated = 0;

        foreach ($input as $key => $value) {
            if (! is_string($key) || ! $known->has($key)) {
                continue;
            }

            /** @var SiteSetting $setting */
            $setting = $known->get($key);
            $setting->update([
                'value' => $this->sanitize($setting, is_string($value) ? $value : ''),
            ]);
            $updated++;
        }

        SiteSetting::forgetCache();

        return back()->with('success', $updated > 0 ? 'Site settings saved.' : 'No changes were made.');
    }

    private function sanitize(SiteSetting $setting, string $value): ?string
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if ($setting->type === 'text') {
            return strip_tags($value);
        }

        $value = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $value) ?? $value;

        if ($setting->type === 'richtext') {
            return strip_tags($value, '<p><br><strong><b><em><i><u><a><ul><ol><li><h2><h3><h4>');
        }

        return $value;
    }
}
