<?php

namespace App\PAM;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Str;

class Layout
{
    public function all($key = null, $default = [], $collect = false): Collection|array
    {
        $layouts = config('web-config.layouts');

        $value = Arr::get($layouts, $key, $default);

        return ! $key
            ? (! $collect ? $layouts : collect($layouts))
            : (! $collect ? $value : collect($value));
    }

    public function menu($type = 'main'): array
    {
        return $this->all("menu.{$type}", [], true)
            ->sortBy(fn ($item) => Arr::get($item, 'order', 10))
            ->toArray();
    }

    public function mergeGroupMenuItems($groupId, $items = [], $type = 'main'): void
    {
        try {
            $menu = $this->menu($type);
            $menuDoted = Arr::dot($menu);

            @[0 => [0 => $keys]] = Arr::divide(array_filter($menuDoted, function ($val) {
                return preg_match('/groupId/', $val);
            }, ARRAY_FILTER_USE_KEY));

            $path = Str::replaceLast('.groupId', '', $keys);

            $parents = Arr::get($menu, $path, []);
            $parentItems = Arr::get($parents, 'items', []);

            $parentPath = [
                'web-config.layouts.menu',
                $type,
                $path,
                'items',
            ];

            config([
                Arr::join($parentPath, '.') => array_merge($parentItems, $items),
            ]);
        } catch (Exception $exception) {
        }
    }

    public static function make(): self
    {
        return new self();
    }
}
