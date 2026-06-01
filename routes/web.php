<?php

use App\Http\Controllers\Front\AboutController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PortfolioController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ServiceController;
use Illuminate\Support\Facades\Route;

$localePattern = implode('|', array_keys(config('app.locales', ['en' => []])));

Route::get('/', function () {
    return redirect('/'.config('app.fallback_locale'));
});

Route::prefix('{locale}')
    ->where(['locale' => $localePattern])
    ->middleware(['set.locale'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('site.home');
        Route::get('/about', [AboutController::class, 'index'])->name('site.about');
        Route::get('/services', [ServiceController::class, 'index'])->name('site.services');
        Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('site.services.show');
        Route::get('/portfolio', [PortfolioController::class, 'index'])->name('site.portfolio');
        Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('site.portfolio.show');
        Route::get('/products', [ProductController::class, 'index'])->name('site.products');
        Route::get('/products/{slug}', [ProductController::class, 'show'])->name('site.products.show');
        Route::get('/contact', [ContactController::class, 'index'])->name('site.contact');
        Route::post('/contact', [ContactController::class, 'store'])->name('site.contact.store');

        Route::get('/section/{section:slug}', [\App\Http\Controllers\Public\SectionController::class, 'show'])->name('section.show');
        Route::get('/section/{section:slug}/about', [\App\Http\Controllers\Public\SectionController::class, 'about'])->name('section.about');
        Route::get('/section/{section:slug}/contact', [\App\Http\Controllers\Public\SectionController::class, 'contact'])->name('section.contact');
        Route::post('/section/{section:slug}/contact', [\App\Http\Controllers\Public\ContactController::class, 'store'])->name('contact.store');

        Route::get('/blog', [\App\Http\Controllers\Public\BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/{post:slug}', [\App\Http\Controllers\Public\BlogController::class, 'show'])->name('blog.show');
    });

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
