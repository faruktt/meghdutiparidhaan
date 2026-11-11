<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['id'];

    protected static $cache = [];

    public static function get($key, $default = null)
    {
        if (!isset(static::$cache[$key])) {
            $setting = static::where('key', $key)->first();
            static::$cache[$key] = $setting ? $setting->value : $default;
        }
        return static::$cache[$key];
    }

    public static function set($key, $value)
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$cache[$key] = $value;
    }
}
