<?php

namspace App\Services;

use App\Models\ImportJob;
use Illuminate\Support\Facades\DB;

class CsvImportService {
    public function process(ImportJob $job): void {
        // Implementation of CSV import processing
        $job->update(['status' => 'processing']);

        $handle = fopen(storage_path('app/' . $job->file_path), 'r');
        if(!$handle) {
            throw new \Exception('Unable to open CSV file.');
        }

        $header = fgetcsv($handle); // Read header row

        DB::beginTransaction();

        try {
            while(($row = fgetcsv($handle)) !== false) {
                try {
                    $data = array_combine($header, $row);

                    // User::updateOrCreate([...])

                    $job->updateProgress(1, 0);
                } catch (\Exception $e) {
                    $job->updateProgress(0, 1);
                    $errors = $job->errors ?? [];
                    $errors[] = [
                        'row' => $row,
                        'error' => $e->getMessage(),
                    ];

                    $job->update(['errors' => $errors]);
                }
            }

            DB::commit();
            fclose($handle);
            $job->update(['status' => 'completed']);
        } catch (\Exception $e) {
            DB::rollBack();

            fclose($handle);
            
            \Log::error($e->getMessage());

            $job->update([
                'status' => 'failed',
                'errors' => array_merge($job->errors ?? [], [['error' => $e->getMessage()]]),
            ]);
        }
    }
}