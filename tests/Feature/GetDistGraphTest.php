<?php

use App\Actions\Analytic\GetFocusDist;
use App\Enums\FocusSessionStatus;
use App\Models\FocusSession;
use App\Models\User;
use Carbon\Carbon;

it('retorna a estrutura correta de dados', function () {
    $user = User::factory()->create();
    $result = (new GetFocusDist)->execute($user, 0);
    expect($result)
        ->toHaveKeys(['title', 'data', 'total'])
        ->and($result['data'])->toBeArray()
        ->and($result['data'])->toHaveCount(7) // sempre 7 dias na semana
        ->and($result['total'])->toBeInt();
});

it('retorna array de zeros quando não há sessões na semana', function () {
    $user = User::factory()->create();

    $result = (new GetFocusDist)->execute($user, 0);

    expect($result['data'])->toBe([0, 0, 0, 0, 0, 0, 0])
        ->and($result['total'])->toBe(0);
});

it('retorna dados da semana anterior quando offset é 1', function () {
    $user             = User::factory()->create();
    $lastWeekTuesday  = Carbon::now()->subWeeks(1)
                            ->startOfWeek(Carbon::SUNDAY)
                            ->addDays(2)
                            ->format('Y-m-d');

    FocusSession::factory()->create([
        'user_id'  => $user->id,
        'status'   => FocusSessionStatus::COMPLETED,
        'duration' => 50,
        'date'     => $lastWeekTuesday,
    ]);

    $currentWeek = (new GetFocusDist)->execute($user, 0);
    expect($currentWeek['total'])->toBe(0);

    $lastWeek = (new GetFocusDist)->execute($user, 1);
    expect($lastWeek['total'])->toBe(50);
});