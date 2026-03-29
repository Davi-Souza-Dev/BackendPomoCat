<?php

use App\Actions\Card\GiveCardToUser;
use App\Actions\Pomodoro\CreateFocusSessions;
use App\Enums\FocusSessionStatus;
use App\Models\Card;
use App\Models\User;

// TESTES MODELO AAA
it('persiste a sessão de foco no banco', function () {

    //Arange 
    $user = User::factory()->create();
    Card::factory()->create(['title' => 'Comum', 'image' => 'teste.php', 'rarity' => 'commun']);
    $today = now()->toDateString();


    // ACT
    $action = new CreateFocusSessions(new GiveCardToUser);
    $action->execute($user, [
        'duration' => 25,
        'status'   => FocusSessionStatus::COMPLETED->value,
    ]);

    // ASSERT
    $this->assertDatabaseHas('focus_sessions', [
        'user_id'  => $user->id,
        'duration' => 25,
        'status'   => FocusSessionStatus::COMPLETED->value,
        'date' => $today
    ]);
});

it('apenas uma sessão por chamada', function () {
    $user = User::factory()->create();
    Card::factory()->create(['title' => 'Comum', 'image' => 'teste.php', 'rarity' => 'commun']);

    $action = new CreateFocusSessions(new GiveCardToUser);

    $action->execute($user, [
        'duration' => 25,
        'status'   => FocusSessionStatus::COMPLETED->value,
    ]);

    $this->assertDatabaseCount('focus_sessions', 1);
});
