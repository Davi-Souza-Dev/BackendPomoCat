<?php

namespace App\Models;

use BcMath\Number;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    protected $casts = ['order' => 'integer', 'title' => 'string'];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn($path) => asset(Storage::url("audio/" . $this->path)),
            set: fn($path) => $path
        );
    }

    public function playlist(User $user): BelongsTo
    {
        return $this->belongsTo('user_id', 'id')->select('id', 'title', 'order', 'path')->orderBy('order', 'ASC')->limit(20);
    }

    public static  function reorder(array $playlist, int $userId)
    {
        DB::transaction(function () use ($playlist,$userId) {
            foreach ($playlist as $order) {
                $audio = Audio::where('user_id', $userId)->where('id', $order['id'])->first();
                $audio->order = $order['order'];
                $audio->save();
            }
        });
    }
}
