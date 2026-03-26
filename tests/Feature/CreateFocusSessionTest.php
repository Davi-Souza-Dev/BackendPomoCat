<?php

use App\Actions\Pomodoro\CreateFocusSessions;
use App\Models\Card;
use App\Models\User;

test('cria session de foco', function () {
    // Etapa 1 - Preparação
    $createFocusSession = new CreateFocusSessions();
    $user = User::factory()->create();
    Card::factory()->create(['title' => 'Comum', 'image' => 'teste.php', 'rarity' => 'commun']);
    $data = [
        "duration" => 50,
        "status" =>  "completed",
    ];

    // Etapa 2 - Agir
    $cardColected = $createFocusSession->execute($user, $data);
    expect($cardColected)->toBeInstanceOf(Card::class);
    
    // Etapa 3 - Assert
    $this->assertEquals(Card::class);
});
