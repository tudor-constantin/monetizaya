<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public int $totalUsers = 0;

    public int $totalCreators = 0;

    public float $platformRevenue = 0.0;

    public function mount(): void
    {
        $this->totalUsers = User::count();
        $this->totalCreators = User::role('creator')->count();
        $this->platformRevenue = (float) Transaction::sum('platform_fee');
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
