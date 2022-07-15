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
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use TheSeer\Tokenizer\Exception;
use function Clue\StreamFilter\fun;

class ProductService
{
    public function save($serviceId, $email, $password, $recoveryEmail = null)
    {
        return DB::transaction(function () use ($serviceId, $email, $password, $recoveryEmail) {
            return Product::create([
                'mail' => $email,
                'password' => $password,
                'recovery_mail' => $recoveryEmail,
                'status' => ProductStatus::LIVE,
                'service_id' => $serviceId
            ]);
        });
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
        $dir = "handlers/products";
        $rootLocation = storage_path("app/$dir");
        $fileName = sprintf('%s.%s', md5(now() . $file->getClientOriginalName()), $file->getClientOriginalExtension());

        File::ensureDirectoryExists($rootLocation);

        $file->move($rootLocation, $fileName);

        return "$dir/$fileName";
    }

    public function createOrderAndBuy(
        User       $user,
        array      $orderAttrs,
        Collection $products,
        bool       $isLocal = false
    ): Order
    {
        return app(DatabaseServiceInterface::class)
            ->transaction(
                static function () use ($user, $orderAttrs, $products, $isLocal) {
                    $orderAttrs['price'] = $orderAttrs['price'] * $products->count();
                    $orderAttrs['quantity'] = $products->count();

                    $order = Order::create($orderAttrs);

                    $amount = $orderAttrs['price'];
                    $user->withdraw($amount);

                    if (!$isLocal) {
                        $order->products()->insert($products->toArray());

                        $products = Product::where(function ($q) use ($products) {
                            foreach ($products as $product) {
                                $q->orWhere('mail', Arr::get($product, 'mail'));
                            }
                        })->get(['id']);

                    }

                    $productIds = $products->pluck('id');

                    $order->products()->sync($productIds);

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

        if (!$data->count()) return $products;

        $products = $data->map(function ($item) use ($service) {
            $data = explode('|', $item);

            $mail = Arr::get($data, '0');
            $password = Arr::get($data, '1');
            $recoveryEmail = Arr::get($data, '2');

            if (blank($mail) || blank($password)) return null;

            $now = now()->toDateTimeString();

            return [
                'service_id' => $service->id,
                'mail' => $mail,
                'password' => $password,
                'recovery_mail' => $recoveryEmail,
                'status' => ProductStatus::LIVE,
                'updated_at' => $now,
                'created_at' => $now
            ];
        })->filter(fn($item) => filled($item));

        return $products->toArray();
    }
}
