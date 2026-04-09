<?php

namespace App\Actions\Pomodoro;

use App\Actions\Card\GiveCardToUser;
use App\Enums\FocusSessionStatus;
use App\Models\User;

class CreateFocusSessions
{
    public function execute(User $user, array $data)
    {

        $newFocus = $user->focusSession()->create([
            'duration' => $data['duration'],
            'status' => $data['status'],
            'date' => now()->toDateString(),
        ]);
    }
}
