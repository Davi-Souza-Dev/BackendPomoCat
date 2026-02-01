<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FocusSession extends Model
{

    protected $fillable = [
        "user_id",
        "duration",
        "status",
        "date",
    ];
    /** @use HasFactory<\Database\Factories\FocusSessionFactory> */
    use HasFactory;
}
