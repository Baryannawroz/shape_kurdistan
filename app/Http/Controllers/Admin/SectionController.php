<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::orderBy('sort_order')->get();
        return view('admin.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:100|unique:sections,slug',
            'name.*' => 'required|string',
            'subtitle.*' => 'nullable|string',
            'description.*' => 'nullable|string',
            'about_content.*' => 'nullable|string',
            'contact_email' => 'nullable|email|max:150',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address.*' => 'nullable|string',
            'maps_embed_url' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:1024',
            'color_theme' => 'required|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'meta_title.*' => 'nullable|string',
            'meta_description.*' => 'nullable|string',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('sections/covers', 'public');
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('sections/logos', 'public');
        }

        if ($request->has('is_active')) {
             $validated['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        } else {
             $validated['is_active'] = false;
        }

        Section::create($validated);

        return redirect()->route('admin.sections.index')->with('success', 'Section created successfully.');
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
    public function edit(Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:100|unique:sections,slug,' . $section->id,
            'name.*' => 'required|string',
            'subtitle.*' => 'nullable|string',
            'description.*' => 'nullable|string',
            'about_content.*' => 'nullable|string',
            'contact_email' => 'nullable|email|max:150',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address.*' => 'nullable|string',
            'maps_embed_url' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:1024',
            'color_theme' => 'required|string|max:20',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'meta_title.*' => 'nullable|string',
            'meta_description.*' => 'nullable|string',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('sections/covers', 'public');
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('sections/logos', 'public');
        }
        
        if ($request->has('is_active')) {
             $validated['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        } else {
             $validated['is_active'] = false;
        }

        $section->update($validated);

        return redirect()->route('admin.sections.index')->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('admin.sections.index')->with('success', 'Section deleted successfully.');
    }

    /**
     * Upload image for section rich text editor.
     */
    public function uploadEditorImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => 'required|image|max:4096',
        ]);

        $path = $request->file('upload')->store('sections/content', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }
}
