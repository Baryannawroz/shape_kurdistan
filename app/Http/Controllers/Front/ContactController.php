<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiteContactRequest;
use App\Models\ContactMessage;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        $locale = app()->getLocale();

        return Inertia::render('Front/Contact', [
            'mapsEmbedUrl' => SiteSettings::get('contact.maps_embed_url'),
            'contact' => [
                'phone' => SiteSettings::get('contact.phone'),
                'email' => SiteSettings::get('contact.email'),
                'address' => SiteSettings::get('contact.address_'.$locale),
            ],
        ]);
    }

    public function store(StoreSiteContactRequest $request): RedirectResponse
    {
        ContactMessage::query()->create($request->validated());

        return redirect()->back()->with('success', __('Your message has been sent successfully.'));
    }
}
