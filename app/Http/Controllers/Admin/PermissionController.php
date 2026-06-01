<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
        ]);

        $input = $request->name;
        // Split by newline or comma
        $permissions = preg_split('/[\n,]/', $input);
        
        $count = 0;
        foreach ($permissions as $permissionName) {
            $permissionName = trim($permissionName);
            if (!empty($permissionName)) {
                // Check if permission already exists to avoid unique constraint violation error
                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create(['name' => $permissionName]);
                    $count++;
                }
            }
        }

        if ($count === 0) {
             return redirect()->route('admin.permissions.index')->with('warning', 'No new permissions were created (they might already exist).');
        }

        return redirect()->route('admin.permissions.index')->with('success', "$count permission(s) created successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $permission->id],
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
