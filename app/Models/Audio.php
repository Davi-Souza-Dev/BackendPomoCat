<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'path',
        'order',
    ];
    /** @use HasFactory<\Database\Factories\AudioFactory> */
    use HasFactory;
    protected $appends = ['url'];
    protected $casts = ['order' => 'integer','title' => 'string'];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn($path) => asset(Storage::url("audio/" . $this->path)),
            set: fn($path) => $path
        );
    }
}
