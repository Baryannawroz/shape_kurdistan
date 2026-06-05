<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\Concerns\LoadsPageIntro;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Support\AdminEditLinks;
use App\Support\PageSeo;
use App\Support\RichContent;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    use LoadsPageIntro;

    public function index(): Response
    {
        $pageIntro = $this->loadPageIntro('services', [
            'eyebrow' => 'Capabilities',
            'title' => __('Services'),
            'lead' => 'Explore how we help teams ship — from strategy and UX to engineering, performance, and analytics.',
        ]);

        $services = Service::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (Service $s) => [
                'id' => $s->id,
                'title' => $s->getTranslation()?->title,
                'description' => $s->getTranslation()?->description,
                'slug' => $s->getTranslation()?->slug,
                'icon' => $s->icon ? Storage::url($s->icon) : null,
                'image' => $s->image ? Storage::url($s->image) : null,
            ]);

        return Inertia::render('Front/Services', [
            'adminEdits' => AdminEditLinks::build(request()->user(), AdminEditLinks::forServicesIndex()),
            'seo' => PageSeo::resolve('services', __('Services'), $pageIntro['intro']['lead']),
            'seoSettings' => AdminEditLinks::canManage(request()->user()) ? PageSeo::settingsPayload('services') : null,
            'pageContent' => $pageIntro['pageContent'],
            'localeMeta' => $pageIntro['localeMeta'],
            'intro' => $pageIntro['intro'],
            'services' => $services,
        ]);
    }

    public function show(string $locale, string $slug): Response
    {
        $translation = ServiceTranslation::query()
            ->where('locale', app()->getLocale())
            ->where('slug', $slug)
            ->with(['service.translations'])
            ->firstOrFail();

        $service = $translation->service;
        $t = $service->getTranslation();

        return Inertia::render('Front/ServiceShow', [
            'adminEdits' => AdminEditLinks::build(request()->user(), AdminEditLinks::forServiceShow($service->id)),
            'seo' => PageSeo::resolve('services', $t?->title ?? __('Services'), strip_tags($t?->description ?? '')),
            'service' => [
                'id' => $service->id,
                'title' => $t?->title,
                'description' => RichContent::expand($t?->description),
                'slug' => $t?->slug,
                'icon' => $service->icon ? Storage::url($service->icon) : null,
                'image' => $service->image ? Storage::url($service->image) : null,
            ],
        ]);
    }
}
