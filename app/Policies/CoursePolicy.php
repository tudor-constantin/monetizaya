<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use App\Services\SubscriptionService;

final class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Course $course): bool
    {
        if (! $course->is_premium) {
            return true;
        }

        if ($course->user_id === $user->id) {
            return true;
        }

        $creator = $course->creator;

        return app(SubscriptionService::class)->hasActiveSubscription($user, $creator);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('creator') || $user->hasRole('admin');
    }

    public function update(User $user, Course $course): bool
    {
        return $course->user_id === $user->id || $user->hasRole('admin');
    }

    public function delete(User $user, Course $course): bool
    {
        return $course->user_id === $user->id || $user->hasRole('admin');
    }

    public function restore(User $user, Course $course): bool
    {
        return $course->user_id === $user->id || $user->hasRole('admin');
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return $user->hasRole('admin');
    }
}
