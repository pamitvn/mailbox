<?php

namespace App\PAM;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ApiResponse implements Arrayable
{
    public static string $getFacadeAccessor = 'core.support.api-response';

    protected bool $success;

    protected ?string $message;

    protected array|Collection|JsonResource|null $data;

    protected array|Collection|null $errors;

    protected array $endData;

    public function __construct()
    {
        $this->success = false;
        $this->message = null;
        $this->data = null;
        $this->errors = null;
        $this->endData = [];
    }

    protected function setSuccess($success): self
    {
        $this->success = $success;

        return $this->extraOptions('success', $success);
    }

    protected function setMessage($message): self
    {
        $this->message = $message;

        return $this->extraOptions('message', $message);
    }

    protected function setData(array|Collection|JsonResource $data): self
    {
        $this->data = $data;

        if ($data instanceof Collection) {
            $this->data = $this->data->toArray();
        }

        return $this->extraOptions('data', $data);
    }

    protected function setErrors(array|Collection $errors): self
    {
        $this->errors = $errors;

        if ($errors instanceof Collection) {
            $this->errors = $this->errors->toArray();
        }

        return $this->extraOptions('errors', $errors);
    }

    public function extraOptions(string|array $key, $value): self
    {
        if (is_string($key)) {
            $key = [$key => $value];
        }

        foreach ($key as $path => $value) {
            Arr::set($this->endData, $path, $value);
        }

        return $this;
    }

    public function withFailed(): self
    {
        $this->setSuccess(false);

        return $this;
    }

    public function withSuccess($success = true): self
    {
        $this->setSuccess($success);

        return $this;
    }

    public function withErrors(array|Collection $errors): self
    {
        return $this->setSuccess(false)->setErrors($errors);
    }

    public function withData(array|Collection|JsonResource $data): self
    {
        return $this->setData($data);
    }

    public function withMessage(string $message): self
    {
        return $this->setMessage($message);
    }

    public function end(): array
    {
        return $this->endData;
    }

    public function toArray(): array
    {
        return $this->end();
    }
}
