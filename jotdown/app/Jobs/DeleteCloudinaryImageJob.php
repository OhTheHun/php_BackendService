<?php

namespace App\Jobs;

use App\Services\Interface\ICloudinaryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteCloudinaryImageJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $imageUrl,
    ) {
    }

    public function handle(ICloudinaryService $cloudinaryService): void
    {
        $cloudinaryService->deleteImageByUrl($this->imageUrl);
    }
}
