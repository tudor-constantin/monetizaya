<?php

declare(strict_types=1);

namespace App\Livewire\Creator\Resources;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.creator.resources.index');
    }
}
