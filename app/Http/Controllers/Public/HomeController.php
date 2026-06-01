<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Post;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools as SEO;

class HomeController extends Controller
{
    public function index()
    {
        SEO::setTitle(__('Home'));
        SEO::setDescription(__('Welcome to our organization website.'));
        SEO::opengraph()->setUrl(url()->current());
        
        $sections = Section::where('is_active', true)->orderBy('sort_order')->get();
        $latestPosts = Post::with(['section', 'category'])->where('status', 'published')->latest()->take(3)->get();
        return view('public.home', compact('sections', 'latestPosts'));
    }
}
