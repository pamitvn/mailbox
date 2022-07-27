<?php

namespace App\Jobs\Products;

use App\Models\Service;
use App\Models\User;
use App\Services\Admin\ProductService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductService $_productService;

    private User $user;

    private Service $service;

    private array|string $payload;

    public function __construct(
        ProductService $productService,
        User $user,
        Service $service,
        array|string $payload
    ) {
        $this->_productService = $productService;
        $this->user = $user;
        $this->service = $service;
        $this->payload = $payload;
    }

    public function handle()
    {
        $this->sendMessage('info', __('Begin processing import merchandise'));
        $payload = $this->payload;

        if (is_string($this->payload)) {
            $payload = $this->getFilePayload($payload);
        }

        foreach ($payload as $data) {
            if (blank($data)) {
                continue;
            }

            try {
                $this->_productService->save($this->service->id, $data);
            } catch (Exception $exception) {
                Log::error(sprintf('Product::Import::%s %s', $this->service->id, $exception->getMessage()));
            }
        }
    }

    private function getFilePayload($filePath): array
    {
        if (! Storage::exists($filePath)) {
            return [];
        }

        $content = Storage::get($filePath);
        $content = collect(explode("\n", $content))
            ->map(fn ($val) => trim($val))
            ->filter(fn ($item) => filled($item));

        Storage::delete($filePath);

        return array_unique($content->toArray(), SORT_REGULAR);
    }

    private function sendMessage($type, $message)
    {
        send_current_user_message($type, $message, $this->user?->id);
    }
}
