<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Default settings mapped by group and name.
     */
    protected array $defaults = [
        'general' => [
            'app_name' => 'Hook ERP',
            'language' => 'id',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'currency_locale' => 'id-ID',
            'date_format' => 'DD/MM/YYYY',
            'decimal_precision' => 0,
            'default_tax_rate' => 0,
        ],
        'transaction_prefix' => [
            'purchase_order' => 'PO',
            'sales_manual' => 'PJ',
            'sales_pos' => 'POS',
            'trade_in' => 'TT',
        ],
    ];

    /**
     * Get a setting value by group and name.
     *
     * @param string $group
     * @param string $name
     * @param mixed|null $default Override the default from $this->defaults
     * @return mixed
     */
    public function get(string $group, string $name, $default = null)
    {
        $settings = $this->getGroup($group);

        return $settings[$name] ?? $default ?? ($this->defaults[$group][$name] ?? null);
    }

    /**
     * Get all settings for a group as an associative array.
     *
     * @param string $group
     * @return array
     */
    public function getGroup(string $group): array
    {
        return Cache::rememberForever("settings.group.{$group}", function () use ($group) {
            $settings = Setting::where('group', $group)->pluck('payload', 'name')->toArray();

            // Merge with defaults
            $defaults = $this->defaults[$group] ?? [];

            return array_merge($defaults, $settings);
        });
    }

    /**
     * Set a setting value.
     *
     * @param string $group
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set(string $group, string $name, $value): void
    {
        Setting::updateOrCreate(
            ['group' => $group, 'name' => $name],
            ['payload' => $value]
        );

        $this->invalidateCache($group);
    }

    /**
     * Bulk set multiple settings in a group.
     *
     * @param string $group
     * @param array $settings
     * @return void
     */
    public function setGroup(string $group, array $settings): void
    {
        foreach ($settings as $name => $value) {
            Setting::updateOrCreate(
                ['group' => $group, 'name' => $name],
                ['payload' => $value]
            );
        }

        $this->invalidateCache($group);
    }

    /**
     * Invalidate cache for a specific group.
     *
     * @param string $group
     * @return void
     */
    public function invalidateCache(string $group): void
    {
        Cache::forget("settings.group.{$group}");
    }
}
