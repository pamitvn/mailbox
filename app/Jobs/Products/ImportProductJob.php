<?php

namespace App\Jobs\Products;

use App\Models\Service;
use App\Models\User;
use App\Services\Admin\ProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
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
        User           $user,
        Service        $service,
        array|string   $payload
    )
    {
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

        foreach ($payload as $key => $data) {
            $email = Arr::get($data, 'email');
            $password = Arr::get($data, 'password');
            $recoveryEmail = Arr::get($data, 'recovery_mail');

            if (blank($email) || blank($password)) continue;

            Log::info("$key", $data);
            $this->_productService->save($this->service->id, $email, $password, $recoveryEmail);
        }

    }

    private function getFilePayload($filePath): array
    {
        if (!Storage::exists($filePath)) return [];

        $content = Storage::get($filePath);
        $content = collect(explode("\n", $content))
            ->map(function ($val) {
                $item = explode('|', $val);

                return [
                    'email' => Arr::get($item, 0),
                    'password' => Arr::get($item, 1),
                    'recovery_mail' => Arr::get($item, 2),
                ];
            })
            ->filter(function ($item) {
                return filled(Arr::get($item, 'email')) && filled(Arr::get($item, 'password'));
            });

        Storage::delete($filePath);

        return array_unique($content->toArray(), SORT_REGULAR);
    }


    private function sendMessage($type, $message)
    {
        send_current_user_message($type, $message, $this->user?->id);
    }
}
