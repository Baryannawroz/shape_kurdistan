<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CmsSettingsFormRequest;
use App\Models\Setting;
use App\Services\ImageUploadService;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingController extends Controller
{
    public function __construct(
        private ImageUploadService $images
    ) {}

    public function index(): Response
    {
        $grouped = Setting::query()
            ->where('group', '!=', 'contact')
            ->orderBy('group')
            ->orderBy('key')
            ->get(['key', 'value', 'group'])
            ->groupBy('group')
            ->map(fn ($items) => $items->mapWithKeys(fn ($row) => [$row->key => $row->value])->all());

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $grouped,
        ]);
    }

    public function update(CmsSettingsFormRequest $request): RedirectResponse
    {
        $flat = collect($request->input('settings', []))
            ->reject(fn (mixed $value, string|int $key): bool => str_starts_with((string) $key, 'contact.'))
            ->all();

        foreach ($flat as $key => $value) {
            $group = $this->groupForKey((string) $key);
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group]
            );
        }

        if ($request->hasFile('site_logo')) {
            $path = $this->images->storeWithThumbnail($request->file('site_logo'), 'settings')['path'];
            Setting::query()->updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $path, 'group' => 'general']
            );
        }

        if ($request->hasFile('site_favicon')) {
            $path = $this->images->storeWithThumbnail($request->file('site_favicon'), 'settings')['path'];
            Setting::query()->updateOrCreate(
                ['key' => 'site_favicon'],
                ['value' => $path, 'group' => 'general']
            );
        }

        if ($request->hasFile('seo_og_image')) {
            $path = $this->images->storeWithThumbnail($request->file('seo_og_image'), 'settings')['path'];
            Setting::query()->updateOrCreate(
                ['key' => 'seo_og_image'],
                ['value' => $path, 'group' => 'seo']
            );
        }

        SiteSettings::forgetCache();

        return redirect()->back()->with('success', __('Saved.'));
    }

    private function groupForKey(string $key): string
    {
        $prefix = explode('.', $key, 2)[0] ?? 'general';

        return match ($prefix) {
            'seo' => 'seo',
            'social' => 'social',
            'contact' => 'contact',
            'appearance' => 'appearance',
            default => 'general',
        };
    }
}
