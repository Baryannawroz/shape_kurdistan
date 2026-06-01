<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Section;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['section', 'category', 'author'])->latest()->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('sections', 'categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:200|unique:posts,slug',
            'title.*' => 'required|string',
            'excerpt.*' => 'nullable|string',
            'body.*' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'section_id' => 'nullable|exists:sections,id',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'allow_comments' => 'boolean',
            'meta_title.*' => 'nullable|string',
            'meta_description.*' => 'nullable|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts/featured', 'public');
        }
        
        $validated['author_id'] = Auth::id();
        
        if ($request->has('allow_comments')) {
             $validated['allow_comments'] = filter_var($request->allow_comments, FILTER_VALIDATE_BOOLEAN);
        } else {
             $validated['allow_comments'] = false;
        }

        $post = Post::create($validated);
        
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $sections = Section::all();
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'sections', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:200|unique:posts,slug,' . $post->id,
            'title.*' => 'required|string',
            'excerpt.*' => 'nullable|string',
            'body.*' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'section_id' => 'nullable|exists:sections,id',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'allow_comments' => 'boolean',
            'meta_title.*' => 'nullable|string',
            'meta_description.*' => 'nullable|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts/featured', 'public');
        }
        
        if ($request->has('allow_comments')) {
             $validated['allow_comments'] = filter_var($request->allow_comments, FILTER_VALIDATE_BOOLEAN);
        } else {
             $validated['allow_comments'] = false;
        }

        $post->update($validated);
        
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * Upload image for post rich text editor.
     */
    public function uploadEditorImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => 'required|image|max:4096',
        ]);

        $path = $request->file('upload')->store('posts/content', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }
}
