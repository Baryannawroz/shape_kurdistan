<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools as SEO;

class SectionController extends Controller
{
    public function show(Section $section)
    {
        SEO::setTitle($section->meta_title ?? $section->name);
        SEO::setDescription($section->meta_description ?? $section->subtitle);
        SEO::opengraph()->setUrl(url()->current());
        if ($section->cover_image) {
            SEO::opengraph()->addImage(asset('storage/' . $section->cover_image));
        }

        return view('public.sections.show', compact('section'));
    }

    public function about(Section $section)
    {
        SEO::setTitle(__('About') . ' - ' . ($section->meta_title ?? $section->name));
        SEO::setDescription($section->meta_description ?? $section->subtitle);
        
        return view('public.sections.about', compact('section'));
    }

    public function contact(Section $section)
    {
        SEO::setTitle(__('Contact') . ' - ' . ($section->meta_title ?? $section->name));
        SEO::setDescription($section->meta_description ?? $section->subtitle);

        return view('public.sections.contact', compact('section'));
    }
}
