<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Simple key-value settings store, backed by the `settings` table.
 *
 * Usage anywhere in the app:
 *   Setting::get('hotel_name', 'My Hotel');
 *   Setting::set('hotel_name', 'New Name');
 *
 * Values are cached forever and the cache is cleared on write, so reading a
 * setting is cheap even if called on every request (e.g. in a layout).
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function cacheKey(string $key): string
    {
        return "setting:{$key}";
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(
            static::cacheKey($key),
            fn () => static::query()->where('key', $key)->value('value') ?? $default,
        );
    }

    public static function set(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(static::cacheKey($key));
    }

    /**
     * All settings as a flat [key => value] array — handy for filling a
     * Filament form in one call.
     */
    public static function allAsArray(): array
    {
        return static::query()->pluck('value', 'key')->all();
    }
}
