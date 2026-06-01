<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactSettingsFormRequest;
use App\Models\Setting;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactSettingController extends Controller
{
    /**
     * @return array<int, array{code: string, name: string}>
     */
    private function localeMeta(): array
    {
        $locales = config('app.locales', []);

        return collect($locales)->map(fn (array $meta, string $code): array => [
            'code' => $code,
            'name' => (string) ($meta['name'] ?? $code),
        ])->values()->all();
    }

    public function edit(): Response
    {
        $address = [];
        foreach (array_keys(config('app.locales', [])) as $locale) {
            $address[$locale] = SiteSettings::get('contact.address_'.$locale) ?? '';
        }

        return Inertia::render('Admin/ContactSettings/Edit', [
            'contact' => [
                'phone' => SiteSettings::get('contact.phone') ?? '',
                'email' => SiteSettings::get('contact.email') ?? '',
                'maps_embed_url' => SiteSettings::get('contact.maps_embed_url') ?? '',
                'address' => $address,
            ],
            'localeMeta' => $this->localeMeta(),
        ]);
    }

    public function update(ContactSettingsFormRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Setting::query()->updateOrCreate(
            ['key' => 'contact.phone'],
            ['value' => $data['phone'] ?? null, 'group' => 'contact']
        );
        Setting::query()->updateOrCreate(
            ['key' => 'contact.email'],
            ['value' => $data['email'] ?? null, 'group' => 'contact']
        );
        Setting::query()->updateOrCreate(
            ['key' => 'contact.maps_embed_url'],
            ['value' => $data['maps_embed_url'] ?? null, 'group' => 'contact']
        );

        foreach ($data['address'] ?? [] as $locale => $value) {
            Setting::query()->updateOrCreate(
                ['key' => 'contact.address_'.$locale],
                ['value' => $value ?: null, 'group' => 'contact']
            );
        }

        SiteSettings::forgetCache();

        return redirect()->back()->with('success', __('Saved.'));
    }
}
