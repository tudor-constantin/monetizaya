<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Services\SubscriptionService;

final class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        if (! $post->is_premium) {
            return true;
        }

        if ($post->user_id === $user->id) {
            return true;
        }

        $creator = $post->author;

        return app(SubscriptionService::class)->hasActiveSubscription($user, $creator);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('creator') || $user->hasRole('admin');
    }

    public function update(User $user, Post $post): bool
    {
        return $post->user_id === $user->id || $user->hasRole('admin');
    }

    public function delete(User $user, Post $post): bool
    {
        return $post->user_id === $user->id || $user->hasRole('admin');
    }

    public function restore(User $user, Post $post): bool
    {
        return $post->user_id === $user->id || $user->hasRole('admin');
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasRole('admin');
    }
}
