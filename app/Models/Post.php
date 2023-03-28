<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        'details' => 'array'
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string)Str::uuid();
        });
    }
}
