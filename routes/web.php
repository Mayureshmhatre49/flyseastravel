<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\EnquiryController as AdminEnquiryController;

// Public site
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tours', [PackageController::class, 'index'])->name('packages.index');
Route::get('/tours/{slug}', [PackageController::class, 'show'])->name('packages.show');
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Admin auth (publicly accessible)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.attempt');
    Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('logout');
});

// Admin (auth + admin role required)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/packages',                [AdminPackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create',         [AdminPackageController::class, 'create'])->name('packages.create');
    Route::post('/packages',               [AdminPackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [AdminPackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}',      [AdminPackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}',   [AdminPackageController::class, 'destroy'])->name('packages.destroy');

    Route::get('/enquiries',                    [AdminEnquiryController::class, 'index'])->name('enquiries.index');
    Route::patch('/enquiries/{enquiry}/status', [AdminEnquiryController::class, 'updateStatus'])->name('enquiries.status');
});
