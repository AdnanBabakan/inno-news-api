<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $hidden = [
        'password',
        'email_verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'feed'
    ];

    public function feed()
    {
        return $this->hasOne(Feed::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->email_verification_code = rand(100000, 999999);
        });

        static::created(function ($model) {
            $new_feed = new Feed;
            $new_feed->user_id = $model->id;
            $new_feed->settings = [];
            $new_feed->save();
        });
    }
}
