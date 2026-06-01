<?php

use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['admin'])->prefix('cms')->name('cms.')->group(function () {
        Route::post('services/reorder', [ServiceController::class, 'reorder'])->name('services.reorder');
        Route::post('projects/reorder', [ProjectController::class, 'reorder'])->name('projects.reorder');
        Route::post('team-members/reorder', [TeamMemberController::class, 'reorder'])->name('team-members.reorder');
        Route::post('testimonials/reorder', [TestimonialController::class, 'reorder'])->name('testimonials.reorder');
        Route::post('product-categories/reorder', [ProductCategoryController::class, 'reorder'])->name('product-categories.reorder');
        Route::post('products/reorder', [ProductController::class, 'reorder'])->name('products.reorder');

        Route::post('pages/upload-video', [PageController::class, 'uploadVideo'])->name('pages.upload-video');
        Route::resource('pages', PageController::class)->except(['show']);
        Route::resource('services', ServiceController::class)->except(['show']);
        Route::resource('projects', ProjectController::class)->except(['show']);
        Route::resource('team-members', TeamMemberController::class)->except(['show']);
        Route::resource('testimonials', TestimonialController::class)->except(['show']);
        Route::resource('product-categories', ProductCategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
        Route::get('site-settings', [SiteSettingController::class, 'index'])->name('site-settings.index');
        Route::put('site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');
        Route::get('contact-settings', [ContactSettingController::class, 'edit'])->name('contact-settings.edit');
        Route::put('contact-settings', [ContactSettingController::class, 'update'])->name('contact-settings.update');
    });

    Route::post('sections/editor-image', [SectionController::class, 'uploadEditorImage'])->name('sections.editor-image');
    Route::resource('sections', SectionController::class);
    Route::post('posts/editor-image', [PostController::class, 'uploadEditorImage'])->name('posts.editor-image');
    Route::resource('posts', PostController::class);
    Route::resource('settings', SettingController::class);

    Route::group(['middleware' => ['role:super-admin']], function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class);
    });

    Route::get('lang/{locale}', function ($locale) {
        if (array_key_exists($locale, config('app.locales'))) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    })->name('switch.language');
});
