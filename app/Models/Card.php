<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Card extends Model
{

    protected $fillable = [
        "id",
        "title",
        "rarity",
        "image"
    ];
    /** @use HasFactory<\Database\Factories\CardFactory> */
    use HasFactory;
    protected $appends = ['image_url'];


    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return asset(Storage::url("cards/" . $this->image));
            },
            set: fn($image) => $image
        );
    }

    protected function peso(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->rarity) {
                'lendário' => 1,
                'epíco'    => 5,
                'raro'     => 20,
                default    => 74,
            }
        );
    }
}
