<?php

namespace App\Jobs\Products;

use App\Models\Service;
use App\Models\User;
use App\PAM\Enums\ProductStatus;
use App\Services\Admin\ProductService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
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

        $payload = is_string($this->payload)
            ? $this->getFilePayload($payload)
            : collect($this->payload)->filter();

        try {
            $now = now()->toDateTimeString();
            $values = $payload
                ->map(fn ($item) => filled($item) ? [
                    'payload' => $item,
                    'status' => ProductStatus::LIVE,
                    'service_id' => $this->service->id,
                    'created_at' => $now,
                ] : null);

            Log::info(sprintf('Product::Import::%s count %s', $this->service->id, $values->count()));

            foreach ($values->chunk(1000) as $item) {
                $this->_productService->bulkSave($item->toArray());
            }

            $this->sendMessage('success', __('Import finished product'));
        } catch (Exception $exception) {
            Log::error(sprintf('Product::Import::%s %s', $this->service->id, $exception->getMessage()));

            $this->sendMessage('danger', __('An error occurred while importing the product'));
        }
    }

    private function getFilePayload($filePath): Collection
    {
        if (! Storage::exists($filePath)) {
            return collect();
        }

        $content = Storage::get($filePath);
        $content = collect(explode("\n", $content))
            ->map(fn ($val) => trim($val))
            ->filter(fn ($item) => filled($item));

        Storage::delete($filePath);

        return $content->unique();
    }

    private function sendMessage($type, $message)
    {
        send_current_user_message($type, $message, $this->user?->id);
    }
}
