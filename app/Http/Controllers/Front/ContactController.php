<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiteContactRequest;
use App\Models\ContactMessage;
use App\Support\AdminEditLinks;
use App\Support\PageSeo;
use App\Support\RichContent;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        $locale = app()->getLocale();

        $user = request()->user();
        $canManage = AdminEditLinks::canManage($user);

        return Inertia::render('Front/Contact', [
            'adminEdits' => AdminEditLinks::build($user, AdminEditLinks::forContact()),
            'seo' => PageSeo::resolve('contact', __('Contact'), __('Get in touch with our team.')),
            'seoSettings' => $canManage ? PageSeo::settingsPayload('contact') : null,
            'mapsEmbedUrl' => SiteSettings::get('contact.maps_embed_url'),
            'contact' => [
                'phone' => SiteSettings::get('contact.phone'),
                'email' => SiteSettings::get('contact.email'),
                'address' => RichContent::expand(SiteSettings::get('contact.address_'.$locale) ?? ''),
            ],
            'contactSettings' => $canManage ? AdminEditLinks::contactSettingsPayload() : null,
            'localeMeta' => $canManage ? AdminEditLinks::localeMeta() : [],
        ]);
    }

    public function store(StoreSiteContactRequest $request): RedirectResponse
    {
        ContactMessage::query()->create($request->validated());

        return redirect()->back()->with('success', __('Your message has been sent successfully.'));
    }
}
