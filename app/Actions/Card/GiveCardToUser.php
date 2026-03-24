<?php
namespace App\Actions\Card;
use App\Models\Card;
use App\Models\User;
use App\Models\UserCard;

class GiveCardToUser{
    public function execute(User $user): Card{
        $cards = Card::all();
        $totalWeight= $cards->sum('weight');
        $selectedCard = null;

        $sorteio = rand(1, $totalWeight);
        $acc = 0;

        foreach ($cards as $card) {
            $acc += $card->weight;
            if ($sorteio <= $acc) {
                UserCard::create([
                    "card_id" => $card->id,
                    "user_id" => $user->id,
                ]);
                $selectedCard = $card;
            }
        }
        return $selectedCard;
    }
}