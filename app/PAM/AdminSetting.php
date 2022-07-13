<?php

namespace App\PAM;

use App\Settings\PaymentSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelSettings\Exceptions\MissingSettings;
use Spatie\LaravelSettings\Settings;

class AdminSetting
{
    static string $getFacadeAccessor = 'core.support.admin-setting';
    static string $cacheKey = 'core.support.admin-setting';

    protected Collection $configs;
    protected array $defines;
    protected array $groups;
    protected array $settings;

    public function __construct(array $configs = [])
    {
        $this->configs = new Collection($configs);
        $this->settings = [];
        $this->groups = [];
        $this->defines = $this->configs->get('defines', []);

        $this->defining();
    }

    protected function defining(): void
    {
        [$groups, $settings] = Cache::rememberForever(self::$cacheKey, function () {
            $groups = [];
            $settings = [];

            foreach ($this->defines as $define) {
                $setting = app($define);

                if (!$setting instanceof Settings) continue;

                [$groupKey, $groupLabel, $groupFields] = [
                    method_exists($setting, 'getAdminGroup') ? $setting->getAdminGroup() : null,
                    method_exists($setting, 'getAdminLabel') ? $setting->getAdminLabel() : null,
                    method_exists($setting, 'adminFields') ? $setting->adminFields() : [],
                ];

                if (!$groupKey) continue;

                $groups[$groupKey] = [
                    'label' => $groupLabel,
                    'key' => $groupKey,
                    'define' => $define,
                    'fields' => $groupFields,
                ];
                try {
                    $settings[$groupKey] = $setting->toArray();
                } catch (MissingSettings $exception) {
                }
            }

            return [$groups, $settings];
        });

        $this->groups = $groups;
        $this->settings = $settings;
    }

    public function groups(): array
    {
        return $this->groups;
    }

    public function groupsOnly($keys): array
    {
        return collect($this->groups())->map(fn($item) => Arr::only($item, $keys))->values()->toArray();
    }

    public function get($group = null)
    {
        if (blank($group)) return $this->settings;

        return Arr::get($this->settings, $group, []);
    }

    public function getGroup($group): ?array
    {
        return is_array($group)
            ? $group
            : Arr::get($this->groups, $group, []);
    }

    public function getValidationRules($group): array
    {
        $rules = [];
        $group = $this->getGroup($group);

        foreach (Arr::get($group, 'fields') as $key => $option) {
            $rule = Arr::get($option, 'rule', []);

            if (blank($rule)) {
                $rule[$key][] = 'nullable';
            } else {
                $rules[$key] = $rule;
            }
        }

        return $rules;
    }

    public function groupOnly($group, $keys): array
    {
        return Arr::only($this->getGroup($group), $keys);
    }

    public function groupExists($group): bool
    {
        return Arr::exists($this->groups, $group);
    }

    public function fillAndSave($group, $values): bool
    {
        try {
            $setting = app(Arr::get($this->getGroup($group), 'define'));

            if (!$setting instanceof Settings) return false;

            $setting->fill($values);
            $setting->save();

            $this->defining();

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }
}
