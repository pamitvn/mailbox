<?php

namespace App\PAM\Enums;

enum ProductStatus: int
{
    const LIVE = 1;
    const DIE = 2;

    static function toLabelArray(): array
    {
        return [
            self::LIVE => __('Live'),
            self::DIE => __('Die'),
        ];
    }

    static function toBadgeHtmlArray(): array
    {
        return [
            self::LIVE => '<span class="badge bg-success text-white rounded-pill">' . self::label(self::LIVE) . '</span>',
            self::DIE => '<span class="badge bg-danger text-white rounded-pill">' . self::label(self::DIE) . '</span>',
        ];
    }

    static function label($status): string|int
    {
        return self::toLabelArray()[$status];
    }

    static function toBadgeHtml($status): string|int
    {
        return self::toBadgeHtmlArray()[$status];
    }

}
