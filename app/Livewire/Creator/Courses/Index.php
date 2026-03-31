<?php

declare(strict_types=1);

namespace App\Livewire\Creator\Courses;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.creator.courses.index');
    }
}
