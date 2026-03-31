<?php

declare(strict_types=1);

namespace App\Livewire\Creator;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Analytics extends Component
{
    public function render()
    {
        return view('livewire.creator.analytics');
    }
}
