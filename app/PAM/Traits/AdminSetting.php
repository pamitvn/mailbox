<?php

namespace App\PAM\Traits;

use Illuminate\Support\Str;

trait AdminSetting
{
    public function getAdminLabel(): ?string
    {
        return $this->adminLabel ?? self::group();
    }

    public function getAdminGroup(): ?string
    {
        return Str::snake($this->adminGroup ?? self::group());
    }

    abstract public function adminFields(): array;
}
