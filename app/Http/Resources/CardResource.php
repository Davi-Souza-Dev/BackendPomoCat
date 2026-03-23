<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = $request->user();
        $isUnlocked = $this->isUnlockedBy($user);

        return [
            'id' => $this->id,
            'title' => $isUnlocked ? $this->title : '???',
            'image_url' => $isUnlocked ? $this->image_url : '/images/bloqueado.png',
            'rarity' => $this->rarity,
            'is_unlocked' => $isUnlocked,
        ];
    }
}
