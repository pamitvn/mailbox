<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service as ServiceModel;
use App\Models\User;
use App\PAM\Enums\ProductStatus;
use App\PAM\Facades\ParentManager;
use App\Settings\GeneralSetting;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use TheSeer\Tokenizer\Exception;

class ProductService
{
    public function save($serviceId, $payload)
    {
        return DB::transaction(fn () => Product::create([
            'payload' => $payload,
            'status' => ProductStatus::LIVE,
            'service_id' => $serviceId,
        ]));
    }

    public function bulkSave($values)
    {
        return DB::transaction(fn () => Product::insertOrIgnore($values));
    }

    public function delete(Product $model)
    {
        try {
            return DB::transaction(function () use ($model) {
                return $model->delete();
            });
        } catch (Exception $exception) {
            return false;
        }
    }

    public function uploadFile(UploadedFile $file, $dirname = 'products'): string
    {
        $dir = sprintf('handlers/%s', $dirname);
        $rootLocation = storage_path("app/$dir");
        $fileName = sprintf('%s.%s', md5(now().$file->getClientOriginalName()), $file->getClientOriginalExtension());

        File::ensureDirectoryExists($rootLocation);

        $file->move($rootLocation, $fileName);

        return "$dir/$fileName";
    }

    public function createOrderAndBuy(
        User $user,
        array $orderAttrs,
        Collection $products,
        bool $isLocal = false
    ): Order {
        return app(DatabaseServiceInterface::class)
            ->transaction(
                static function () use ($user, $orderAttrs, $products, $isLocal) {
                    $count = $products->count();

                    $orderAttrs['price'] = $orderAttrs['price'] * $count;
                    $orderAttrs['quantity'] = $count;

                    $order = Order::create($orderAttrs);

                    $amount = $orderAttrs['price'];
                    $user->withdraw($amount);

                    if (! $isLocal) {
                        foreach ($products->filter()->chunk(1000) as $product) {
                            $order->products()->insertOrIgnore($product->toArray());
                        }

                        $products = Product::whereIn('payload', $products->pluck('payload'))
                            ->get(['id']);
                    }

                    $productIds = $products->pluck('id');

                    $order->products()->sync($productIds);

                    return $order;
                }
            );
    }

    public function buyProduct(ServiceModel $service, $quantity)
    {
        $isRandom = app(GeneralSetting::class)?->buy_random ?? false;

        return $service->products()
            ->when($isRandom, fn ($q) => $q->randomQuantity($quantity))
            ->when(! $isRandom, fn ($q) => $q->quantity($quantity))
            ->get();
    }

    public function buyProductFromParent(ServiceModel $service, $quantity)
    {
        $parentManager = ParentManager::withAuth();

        return $parentManager
            ->withType($service->extras?->get('parent_type'))
            ->withQuantity($quantity)
            ->getMail()
            ->map(function ($item) use ($service) {
                if (blank($item)) {
                    return null;
                }

                $now = now()->toDateTimeString();

                return [
                    'service_id' => $service->id,
                    'payload' => $item,
                    'status' => ProductStatus::LIVE,
                    'updated_at' => $now,
                    'created_at' => $now,
                ];
            });
    }
}
