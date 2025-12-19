<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{

    public function csvImporter()
    {
        $this->redirectRoute('csv.import', navigate: true);
    }

    public function imageUploader()
    {
        $this->redirectRoute('image.upload', navigate: true);
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
