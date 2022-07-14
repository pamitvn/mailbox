<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\PAM\Enums\ProductStatus;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use TheSeer\Tokenizer\Exception;

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

    public function buy(\App\Models\Service $service, Product $product, User $user, $price)
    {
        return app(DatabaseServiceInterface::class)->transaction(function () use ($service, $product, $user, $price) {
            if ($price <= 0) $user->payFree($product);

            $user->pay($product);

            return Order::create([
                'service_id' => $service->id,
                'product_id' => $product->id,
                'user_id' => $user->id,
                'price' => $price
            ]);
        });
    }

    public function createProductAndBuy(
        \App\Models\Service $service,
        array               $product,
        User                $user,
                            $price
    )
    {
        return app(DatabaseServiceInterface::class)->transaction(function () use ($service, $product, $user, $price) {
            $product = Product::create($product);

            if ($price <= 0) $user->payFree($product);

            $user->pay($product);

            return Order::create([
                'service_id' => $service->id,
                'product_id' => $product->id,
                'user_id' => $user->id,
                'price' => $price
            ]);
        });
    }
}
