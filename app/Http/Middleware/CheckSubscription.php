<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
    ) {}

    public function handle(Request $request, Closure $next, string $param = 'creator'): Response
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $creator = $request->route($param);

        if ($creator instanceof User && $creator->is_creator) {
            if (! $this->subscriptionService->hasActiveSubscription($user, $creator)) {
                abort(403, 'Active subscription required.');
            }
        }

        return $next($request);
    }
}
