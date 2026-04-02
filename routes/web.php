<?php

use App\Http\Controllers\CreatorSubscriptionController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [PublicPageController::class, 'home'])->name('home');

Route::get('/creators', [PublicPageController::class, 'creatorsIndex'])
    ->name('creators.index');

Route::get('/creators/{user:slug}', [PublicPageController::class, 'showCreator'])->name('creators.show');
Route::get('/creators/{user:slug}/posts/{post:slug}', [PublicPageController::class, 'showPost'])->name('creators.posts.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/creators/{user:slug}/subscribe', [CreatorSubscriptionController::class, 'store'])
        ->name('creators.subscribe');
    Route::get('/creators/{user:slug}/subscribe/success', [CreatorSubscriptionController::class, 'success'])
        ->name('creators.subscribe.success');
    Route::get('/creators/{user:slug}/subscribe/cancel', [CreatorSubscriptionController::class, 'cancel'])
        ->name('creators.subscribe.cancel');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('dashboard', 'pages.dashboard')->name('dashboard');
    Volt::route('profile', 'pages.profile')->name('profile');

    Route::middleware('creator')->prefix('creator')->name('creator.')->group(function () {
        Volt::route('dashboard', 'creator.dashboard')->name('dashboard');
        Volt::route('posts', 'creator.posts.index')->name('posts.index');
        Volt::route('posts/create', 'creator.posts.create')->name('posts.create');
        Volt::route('posts/{post:slug}/edit', 'creator.posts.edit')->name('posts.edit');
        Volt::route('resources', 'creator.resources.index')->name('resources.index');
        Volt::route('courses', 'creator.courses.index')->name('courses.index');
        Volt::route('analytics', 'creator.analytics')->name('analytics');
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Volt::route('dashboard', 'admin.dashboard')->name('dashboard');
        Volt::route('users', 'admin.users')->name('users');
        Volt::route('settings', 'admin.settings')->name('settings');
    });
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('cashier.webhook');

require __DIR__.'/auth.php';
