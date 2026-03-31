<?php

declare(strict_types=1);

namespace App\Livewire\Creator;

use App\Models\Transaction;
use App\Services\RevenueService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public array $earnings = [];

    public int $activeSubscribers = 0;

    public int $totalPosts = 0;

    public int $totalViews = 0;

    public array $recentTransactions = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->earnings = app(RevenueService::class)->getCreatorEarnings($user, 'month');
        $this->activeSubscribers = $user->posts()->count();
        $this->totalPosts = $user->posts()->count();
        $this->totalViews = (int) $user->posts()->sum('views');
        $this->recentTransactions = Transaction::where('creator_id', $user->id)
            ->latest()
            ->take(5)
            ->with('subscriber')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.creator.dashboard');
    }
}
