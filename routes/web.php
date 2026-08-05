<?php

use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BoardMemberController as AdminBoardMemberController;
use App\Http\Controllers\Admin\CareerController as AdminCareerController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EditorUploadController;
use App\Http\Controllers\Admin\InlineEditController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NewsPostController as AdminNewsPostController;
use App\Http\Controllers\Admin\PortfolioItemController as AdminPortfolioItemController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\PasswordChangeController as AdminPasswordChangeController;
use App\Http\Controllers\Admin\PasswordResetController as AdminPasswordResetController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MediaDownloadController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/about', [CustomPageController::class, 'show'])
    ->defaults('slug', 'about')
    ->name('about');

Route::get('/about/news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('/media-files/{mediaFile}', [MediaDownloadController::class, 'show'])
    ->name('media.public');

Route::redirect('/partners/oht', '/partners/ontario-health-teams', 301);
Route::redirect('/about/board-of-directors', '/about/board', 301);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');

        Route::get('password/forgot', [AdminPasswordResetController::class, 'create'])->name('password.request');
        Route::post('password/forgot', [AdminPasswordResetController::class, 'store'])->name('password.email')->middleware('throttle:5,1');
        Route::get('password/reset/{token}', [AdminPasswordResetController::class, 'edit'])->name('password.reset');
        Route::post('password/reset', [AdminPasswordResetController::class, 'update'])->name('password.store')->middleware('throttle:5,1');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('password/change', [AdminPasswordChangeController::class, 'show'])->name('password.change');
        Route::put('password/change', [AdminPasswordChangeController::class, 'update'])->name('password.update');

        Route::middleware('password.changed')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::view('inline-editing', 'admin.inline-editing')->name('inline-editing');
            Route::patch('inline-update', [InlineEditController::class, 'update'])->name('inline-update');
            Route::post('inline-upload-image', [InlineEditController::class, 'uploadImage'])->name('inline-upload-image');

            Route::resource('pages', AdminPageController::class)->except(['show']);
            Route::get('pages/{page}/preview', [AdminPageController::class, 'preview'])->name('pages.preview');
            Route::patch('pages/{page}/publish', [AdminPageController::class, 'publish'])->name('pages.publish');
            Route::patch('pages/{page}/unpublish', [AdminPageController::class, 'unpublish'])->name('pages.unpublish');

            Route::get('messages', [AdminContactMessageController::class, 'index'])->name('messages.index');
            Route::get('messages/{message}', [AdminContactMessageController::class, 'show'])->name('messages.show');
            Route::patch('messages/{message}/read', [AdminContactMessageController::class, 'markRead'])->name('messages.read');
            Route::delete('messages/{message}', [AdminContactMessageController::class, 'destroy'])->name('messages.destroy');

            Route::resource('media', MediaController::class)
                ->only(['index', 'create', 'store', 'destroy'])
                ->parameters(['media' => 'mediaFile']);

            Route::post('editor/upload-image', [EditorUploadController::class, 'uploadImage'])
                ->name('editor.upload-image');

            Route::resource('news', AdminNewsPostController::class)->except(['show']);
            Route::resource('careers', AdminCareerController::class)->except(['show']);
            Route::resource('board', AdminBoardMemberController::class)->except(['show'])->parameters(['board' => 'boardMember']);
            Route::resource('portfolio', AdminPortfolioItemController::class)->except(['show'])->parameters(['portfolio' => 'portfolioItem']);
            Route::get('settings', [AdminSiteSettingController::class, 'index'])->name('settings.index');
            Route::patch('settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');

            Route::middleware('primary.admin')->group(function () {
                Route::resource('users', AdminUserController::class)->except(['show']);
            });
        });
    });
});

// Custom child pages (e.g. /products-services/testing) must be registered before
// section routes because both use two URL segments. Built-in section pages are
// handled inside CustomPageController::showChild when no custom child matches.
Route::get('/{parentSlug}/{childSlug}', [CustomPageController::class, 'showChild'])
    ->where('parentSlug', '[a-z0-9\-]+')
    ->where('childSlug', '[a-z0-9\-]+')
    ->name('custom-pages.child.show');

Route::get('/{section}/{page:slug}', [PageController::class, 'show'])
    ->whereIn('section', ['products', 'partners', 'about'])
    ->name('pages.show');

Route::get('/{slug}', [CustomPageController::class, 'show'])
    ->where('slug', '^(?!admin|search|contact|up)[a-z0-9\-]+$')
    ->name('custom-pages.show');
