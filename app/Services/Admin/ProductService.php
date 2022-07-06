<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\PAM\Enums\ProductStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use TheSeer\Tokenizer\Exception;

class ProductService
{
    public function save($serviceId, $email, $password, $recoveryEmail = null)
    {
        return DB::transaction(function () use ($serviceId, $email, $password, $recoveryEmail) {
            Product::create([
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
}
