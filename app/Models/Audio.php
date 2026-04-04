<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{

    protected $fillable = [
        'user_id',
        'title',
        'path',
        'order',
    ];
    /** @use HasFactory<\Database\Factories\AudioFactory> */
    use HasFactory;
}
