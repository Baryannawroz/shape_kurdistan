<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);
        
        $validated['section_id'] = $section->id;
        
        Contact::create($validated);
        
        return back()->with('success', __('Your message has been sent successfully.'));
    }
}
