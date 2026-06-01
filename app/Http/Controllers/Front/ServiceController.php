<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Support\RichContent;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
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
