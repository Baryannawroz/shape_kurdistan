<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'counts' => [
                'projects' => Project::query()->count(),
                'services' => Service::query()->count(),
                'product_categories' => ProductCategory::query()->count(),
                'products' => Product::query()->count(),
                'team' => TeamMember::query()->count(),
                'unread_messages' => ContactMessage::query()->where('is_read', false)->count(),
            ],
            'recentMessages' => ContactMessage::query()
                ->latest()
                ->limit(8)
                ->get(['id', 'name', 'email', 'subject', 'is_read', 'created_at']),
        ]);
    }
}
