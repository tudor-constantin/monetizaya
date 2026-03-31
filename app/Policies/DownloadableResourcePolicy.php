<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DownloadableResource;
use App\Models\User;
use App\Services\SubscriptionService;

final class DownloadableResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, DownloadableResource $resource): bool
    {
        if (! $resource->is_premium) {
            return true;
        }

        if ($resource->user_id === $user->id) {
            return true;
        }

        $creator = $resource->creator;

        return app(SubscriptionService::class)->hasActiveSubscription($user, $creator);
    }

    public function download(User $user, DownloadableResource $resource): bool
    {
        return $this->view($user, $resource);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('creator') || $user->hasRole('admin');
    }

    public function update(User $user, DownloadableResource $resource): bool
    {
        return $resource->user_id === $user->id || $user->hasRole('admin');
    }

    public function delete(User $user, DownloadableResource $resource): bool
    {
        return $resource->user_id === $user->id || $user->hasRole('admin');
    }

    public function restore(User $user, DownloadableResource $resource): bool
    {
        return $resource->user_id === $user->id || $user->hasRole('admin');
    }

    public function forceDelete(User $user, DownloadableResource $resource): bool
    {
        return $user->hasRole('admin');
    }
}
