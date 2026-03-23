<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class Card extends Model
{
    protected $fillable = [
        'id',
        'title',
        'rarity',
        'image',
    ];

    /** @use HasFactory<\Database\Factories\CardFactory> */
    use HasFactory;

    protected $appends = ['image_url'];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return asset(Storage::url('cards/'.$this->image));
            },
            set: fn ($image) => $image
        );
    }

    protected function weight(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->rarity) {
                'legendary' => 2,
                'epic' => 5,
                'rare' => 12,
                'uncommun' => 10,
                default => 65,
            }
        );
    }

    public function isUnlockedBy(User $user)
    {
        return $user->cards()->where('card_id', $this->id)->exists();
    }

    public function updateImage(File $file)
    {
        Storage::disk('public')->delete("cards/$this->image");
        $path = Storage::disk('public')->put('cards', $file);
        $this->image = basename($path);
        $this->save();
    }
}
