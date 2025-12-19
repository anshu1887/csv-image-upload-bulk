<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;

class CsvImporter extends Component
{
    use WithFileUploads;

    public $csvFile;
    public $message;

    /**
     * Upload CSV and start import
     */
    public function import()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        // API call to backend
        $response = Http::attach(
            'csv_file',
            file_get_contents($this->csvFile->getRealPath()),
            $this->csvFile->getClientOriginalName()
        )->post(url('/api/import/csv'));

        if ($response->successful()) {
            $this->message = 'CSV import started successfully';
        } else {
            $this->message = 'Import failed';
        }
    }

    public function render()
    {
        return view('livewire.csv-importer');
    }
}
