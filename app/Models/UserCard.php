<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{

    protected $fillable = [
        'user_id',
        'card_id',
        'unlock_at',
    ];
    /** @use HasFactory<\Database\Factories\UserCardFactory> */
    use HasFactory;

    
}
