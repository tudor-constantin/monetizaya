<?php

use App\Http\Controllers\StripeWebhookController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    try {
        $creators = User::role('creator')->withCount('posts')->latest()->take(6)->get();
    } catch (Exception $e) {
        $creators = collect();
    }

    return view('welcome', ['creators' => $creators]);
})->name('home');

Route::get('/creators/{user:slug}', function (User $user) {
    $posts = $user->posts()->published()->latest()->get();

    return view('creator.profile', ['creator' => $user, 'posts' => $posts]);
})->name('creators.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('dashboard', 'pages.dashboard')->name('dashboard');
    Volt::route('profile', 'pages.profile')->name('profile');

    Route::middleware('creator')->prefix('creator')->name('creator.')->group(function () {
        Volt::route('dashboard', 'creator.dashboard')->name('dashboard');
        Volt::route('posts', 'creator.posts.index')->name('posts.index');
        Volt::route('posts/create', 'creator.posts.create')->name('posts.create');
        Volt::route('posts/{post}/edit', 'creator.posts.edit')->name('posts.edit');
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
