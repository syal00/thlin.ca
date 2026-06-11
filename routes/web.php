<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BoardMemberController as AdminBoardMemberController;
use App\Http\Controllers\Admin\CareerController as AdminCareerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InlineEditController;
use App\Http\Controllers\Admin\NewsPostController as AdminNewsPostController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PortfolioItemController as AdminPortfolioItemController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/about/news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('/about', function () {
    return redirect('/');
})->name('about');

Route::get('/{section}/{page:slug}', [PageController::class, 'show'])
    ->whereIn('section', ['products', 'partners', 'about'])
    ->name('pages.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::view('inline-editing', 'admin.inline-editing')->name('inline-editing');
        Route::patch('inline-update', [InlineEditController::class, 'update'])->name('inline-update');
        Route::post('inline-upload-image', [InlineEditController::class, 'uploadImage'])->name('inline-upload-image');

        Route::get('pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');

        Route::resource('news', AdminNewsPostController::class)->except(['show']);
        Route::resource('careers', AdminCareerController::class)->except(['show']);
        Route::resource('board', AdminBoardMemberController::class)->except(['show'])->parameters(['board' => 'boardMember']);
        Route::resource('portfolio', AdminPortfolioItemController::class)->except(['show'])->parameters(['portfolio' => 'portfolioItem']);
        Route::resource('users', AdminUserController::class)->except(['show']);
    });
});
