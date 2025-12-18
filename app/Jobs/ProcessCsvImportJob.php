<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\ImportJob;
use App\Services\CsvImportService;
use Illiminate\Foundation\Bus\Dispatchable;
use Illiminate\Queue\InteractsWithQueue;
use Illiminate\Queue\SerializesModels;

class ProcessCsvImportJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    protected ImportJob $importJob;
    protected CsvImportService $importService;

    /**
     * Create a new job instance.
     */
    public function __construct(ImportJob $importJob, CsvImportService $importService)
    {
        $this->importJob = $importJob;
        $this->importService = $importService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->importService->process($this->importJob);
    }

    /**
     * If job fails after retries
     */
    public function failed(\Throwable $exception): void {
        $this->importJob->update([
            'status' => 'failed',
            'errors' => array_merge($this->importJob->errors ?? [], [['error' => $exception->getMessage()]]),
        ]);
    }
}
