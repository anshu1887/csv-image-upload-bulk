<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illiminate\Foundation\Bus\Dispatchable;
use Illiminate\Queue\InteractsWithQueue;
use Illiminate\Queue\SerializesModels;
use App\Models\Image;
use App\Services\ImageVariantService;

class GenerateImageVariantsJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public int $tries = 2;
    public int $timeout = 120;

    protected Image $image;
    protected ImageVariantService $variantService;

    /**
     * Create a new job instance.
     */
    public function __construct(Image $image, ImageVariantService $variantService)
    {
        $this->image = $image;
        $this->variantService = $variantService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->variantService->generateVariants($this->image);
    }
}
