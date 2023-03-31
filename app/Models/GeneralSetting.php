<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string)Str::uuid();
        });
    }

    public static function getPublicSettings()
    {
        return self::where('type', 'public')->get();
    }

    public static function getPrivateSettings()
    {
        return self::where('type', 'private')->get();
    }

    public static function getSetting($key)
    {
        return self::where('key', $key)->first();
    }

    public static function setPublicSetting($key, $value)
    {
        $setting = self::getSetting($key);
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'type' => 'public',
            ]);
        }
    }

    public static function setPrivateSetting($key, $value)
    {
        $setting = self::getSetting($key);
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'type' => 'private',
            ]);
        }
    }
}
