<?php

namespace App\Actions\Card;

use App\Models\Card;
use Illuminate\Http\File;

class UpdateCard
{
    public function execute(Card $card,array $data, File $file): Card
    {
        $card->title = $data['title'];
        $card->rarity = $data['rarity'];
        if(isset($file)){
            $card->updateImage($file);
        }

        return $card;
    }
}
