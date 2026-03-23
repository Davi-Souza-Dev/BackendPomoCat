<?php

use App\Actions\Card\GiveCardToUser;
use App\Models\Card;
use App\Models\User;

it('da uma carta para o usuario', function () {
    $user = User::factory()->create();
    
    // Criamos cards com pesos diferentes para testar a lógica
    Card::factory()->create(['title' => 'Comum','image'=>'teste.php','rarity'=>'commun']);
    $raro = Card::factory()->create(['title' => 'Lendário','image'=>'teste.php','rarity'=>'rare']);

    $action = new GiveCardToUser();

    // 2. Act (Agir)
    $cardSorteado = $action->execute($user);

    // 3. Assert (Verificar)
    // Verifica se retornou uma instância de Card
    expect($cardSorteado)->toBeInstanceOf(Card::class);
    
    // Verifica se o registro foi criado na tabela pivot user_cards
    $this->assertDatabaseHas('user_cards', [
        'user_id' => $user->id,
        'card_id' => $cardSorteado->id,
    ]);
});
