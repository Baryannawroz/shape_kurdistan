<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools as SEO;

class BlogController extends Controller
{
    public function index()
    {
        SEO::setTitle(__('Blog'));
        SEO::setDescription(__('Latest news and updates.'));

        $posts = Post::with(['section', 'category', 'author'])->where('status', 'published')->latest()->paginate(12);
        return view('public.blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        SEO::setTitle($post->meta_title ?? $post->title);
        SEO::setDescription($post->meta_description ?? $post->excerpt);
        SEO::opengraph()->setUrl(url()->current());
        if ($post->featured_image) {
            SEO::opengraph()->addImage(asset('storage/' . $post->featured_image));
        }

        $post->increment('views');
        return view('public.blog.show', compact('post'));
    }
}
