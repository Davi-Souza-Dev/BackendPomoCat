<?php

use App\Actions\Card\GiveCardToUser;
use App\Models\Card;
use App\Models\User;

it('da uma carta para o usuario', function () {
    $user = User::factory()->create();
    
    Card::factory()->create(['title' => 'Comum','image'=>'teste.php','rarity'=>'commun']);

    $action = new GiveCardToUser();

    $cardSorteado = $action->execute($user);

    expect($cardSorteado)->toBeInstanceOf(Card::class);
    
    $this->assertDatabaseHas('user_cards', [
        'user_id' => $user->id,
        'card_id' => $cardSorteado->id,
    ]);
});
