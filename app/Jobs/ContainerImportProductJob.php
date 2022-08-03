<?php

namespace App\Jobs;

use App\Models\Storage;
use App\Models\StorageContainer;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage as StorageFacade;

class ContainerImportProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected ProductService $_productService,
        protected User $user,
        protected Storage $storage,
        protected array|string $payload
    ) {
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
                ->map(function ($item) use ($now) {
                    return filled($item) ? [
                        'payload' => $item,
                        'status' => ProductStatus::LIVE,
                        'storage_id' => $this->storage->id,
                        'created_at' => $now,
                    ] : null;
                })
                ->filter();

            pam_system_log()->info(sprintf('Container::Product::Import::%s count %s', $this->storage->id, $values->count()));

            foreach ($values->chunk(1000) as $item) {
                DB::transaction(fn () => StorageContainer::insertOrIgnore($item->toArray()));
            }

            $this->sendMessage('success', __('Import finished product'));
        } catch (Exception $exception) {
            pam_system_log()->error(sprintf('Container::Product::Import::%s %s', $this->storage->id, $exception->getMessage()));
            Log::error($exception);

            $this->sendMessage('danger', __('An error occurred while importing the product'));
        }
    }

    private function getFilePayload($filePath): Collection
    {
        if (! StorageFacade::exists($filePath)) {
            return collect();
        }

        $content = StorageFacade::get($filePath);
        $content = collect(explode("\n", $content))
            ->map(fn ($val) => trim($val))
            ->filter(fn ($item) => filled($item));

        StorageFacade::delete($filePath);

        return $content->unique();
    }

    private function sendMessage($type, $message)
    {
        send_current_user_message($type, $message, $this->user?->id);
    }
}
