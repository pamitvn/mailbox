<?php

namespace App\Services\Admin;

use App\Models\Service as ServiceModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TheSeer\Tokenizer\Exception;

class Service
{
    public function create(array $data): bool
    {
        try {
            return DB::transaction(function () use ($data) {
                return (bool) ServiceModel::create($data);
            });
        } catch (Exception $exception) {
            return false;
        }
    }

    public function update(ServiceModel $model, array $data): bool
    {
        try {
            return DB::transaction(function () use ($model, $data) {
                return $model->update($data);
            });
        } catch (Exception $exception) {
            return false;
        }
    }

    public function delete(ServiceModel $model)
    {
        try {
            return DB::transaction(function () use ($model) {
                $status = $model->delete();

                if ($status) {
                    $this->deleteFeatureImage($model->feature_image);
                }

                return $status;
            });
        } catch (Exception $exception) {
            return false;
        }
    }

    public function uploadFeatureImage(UploadedFile $file, $old = null): string
    {
        if (filled($old)) {
            $this->deleteFeatureImage($old);
        }

        $folder = storage_path('app/public/services');
        $filename = md5(date('YmdHi').$file->getClientOriginalName()).'.'.$file->extension();

        File::ensureDirectoryExists($folder);
        $file->move($folder, $filename);

        return 'services/'.$filename;
    }

    public function deleteFeatureImage($path): void
    {
        $path = Str::start($path, '/');
        $path = "public/$path";

        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
