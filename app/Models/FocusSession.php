<?php

namespace App\Models;

use App\Enums\FocusSessionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    protected $casts = ['status' => FocusSessionStatus::class];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
