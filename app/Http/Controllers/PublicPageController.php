<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

final class PublicPageController extends Controller
{
    public function home(): View
    {
        $creators = $this->loadLatestCreators();

        return view('welcome', ['creators' => $creators]);
    }

    public function showCreator(User $user, SubscriptionService $subscriptionService): View
    {
        $viewer = Auth::user();

        if ($viewer?->id !== $user->id && ! $this->isPublicCreator($user)) {
            abort(404);
        }

        $canViewPremium = $this->canViewPremiumPosts($viewer, $user, $subscriptionService);

        $posts = $user->posts()->published()->latest()->get();

        return view('creator.profile', ['creator' => $user, 'posts' => $posts, 'canViewPremium' => $canViewPremium]);
    }

    public function showPost(User $user, Post $post, SubscriptionService $subscriptionService): View
    {
        $viewer = Auth::user();

        if ($post->user_id !== $user->id || $post->status !== 'published') {
            abort(404);
        }

        $canViewPremium = $this->canViewPremiumPosts($viewer, $user, $subscriptionService);
        $isLocked = $post->is_premium && ! $canViewPremium;

        if ($isLocked) {
            $plainBody = trim(strip_tags($post->body));
            $previewLength = max(Str::length($plainBody) - 1, 0);
            $displayBody = Str::substr($plainBody, 0, min(220, $previewLength));
            $displayBody = $displayBody !== '' ? rtrim($displayBody).'…' : '';
        } else {
            $displayBody = $post->body;
        }

        $recentPosts = $user->posts()
            ->published()
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(4)
            ->get();

        return view('creator.post', [
            'creator' => $user,
            'post' => $post,
            'isLocked' => $isLocked,
            'displayBody' => $displayBody,
            'recentPosts' => $recentPosts,
            'canViewPremium' => $canViewPremium,
        ]);
    }

    public function creatorsIndex(): View
    {
        $creators = $this->publicCreatorsBaseQuery()
            ->withCount([
                'posts as published_posts_count' => fn (Builder $query) => $query->published(),
            ])
            ->latest()
            ->take(24)
            ->get();

        return view('creator.index', [
            'creators' => $creators,
        ]);
    }

    private function loadLatestCreators(): Collection
    {
        try {
            return $this->publicCreatorsBaseQuery()
                ->withCount([
                    'posts as published_posts_count' => fn (Builder $query) => $query->published(),
                ])
                ->latest()
                ->take(6)
                ->get();
        } catch (Throwable) {
            return collect();
        }
    }

    private function publicCreatorsBaseQuery(): Builder
    {
        return User::query()
            ->where('is_active', true)
            ->where('is_public', true)
            ->whereHas('roles', fn (Builder $query) => $query->whereIn('name', ['creator', 'admin']))
            ->whereHas('posts', fn (Builder $query) => $query->published());
    }

    private function isPublicCreator(User $user): bool
    {
        if (! $user->is_active || ! $user->is_public) {
            return false;
        }

        if (! $user->hasRole('creator') && ! $user->hasRole('admin')) {
            return false;
        }

        return $user->posts()->published()->exists();
    }

    private function canViewPremiumPosts(?User $viewer, User $creator, SubscriptionService $subscriptionService): bool
    {
        if (! $viewer) {
            return false;
        }

        if ($viewer->id === $creator->id || $viewer->hasRole('admin')) {
            return true;
        }

        return $subscriptionService->hasActiveSubscription($viewer, $creator);
    }
}
