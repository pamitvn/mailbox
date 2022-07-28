<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service as ServiceModel;
use App\Models\User;
use App\PAM\Enums\ProductStatus;
use App\PAM\Facades\ParentManager;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
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

    public function uploadFile(UploadedFile $file): string
    {
        $dir = 'handlers/products';
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
                        $order->products()->insert($products->toArray());

                        $products = Product::where(function ($q) use ($products) {
                            foreach ($products as $product) {
                                $q->orWhere('payload', Arr::get($product, 'payload'));
                            }
                        })->get(['id']);
                    }

//                    $productIds = $products->pluck('id');

                    $order->products()->sync($products);

                    return $order;
                }
            );
    }

    public function buyRandomProduct(ServiceModel $service, $quantity): array
    {
        $products = $service->products()
            ->randomQuantity($quantity)
            ->get();

        return $products->toArray();
    }

    public function buyProductFromParent(ServiceModel $service, $quantity): array
    {
        $parentManager = ParentManager::withAuth();
        $data = $parentManager
            ->withType($service->extras?->get('parent_type'))
            ->withQuantity($quantity)
            ->getMail();
        $products = [];

        if (! $data->count()) {
            return $products;
        }

        $products = $data->map(function ($item) use ($service) {
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
        })->filter(fn ($item) => filled($item));

        return $products->toArray();
    }
}
