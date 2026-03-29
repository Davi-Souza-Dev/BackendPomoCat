<?php

use App\Actions\Analytic\GetHeatmap;
use App\Enums\FocusSessionStatus;
use App\Models\FocusSession;
use App\Models\User;
use Carbon\Carbon;

it('retorna uma collection', function () {
    $user = User::factory()->create();
    $result = (new GetHeatmap)->execute($user);
    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

it('retorna collection vazia quando não há sessões', function () {
    $user = User::factory()->create();
    $result = (new GetHeatmap)->execute($user);
    expect($result)->toBeEmpty();
});

it('retorna a data de uma sessão completada', function () {
    $user = User::factory()->create();
    $date = Carbon::now()->subDays(5)->format('Y-m-d');
    FocusSession::factory()->for($user)->create(['date' => $date]);
    $result = (new GetHeatmap)->execute($user);
    expect($result)->toContain($date);
});

it('não duplica a data quando há múltiplas sessões no mesmo dia', function () {
    $user = User::factory()->create();
    $date = Carbon::now()->subDays(5)->format('Y-m-d');
    FocusSession::factory()->count(3)->for($user)->create(['date' => $date]);
    $result = (new GetHeatmap)->execute($user);
    expect($result)->toHaveCount(1)
        ->and($result)->toContain($date);
});

it('ignora sessões não completadas', function () {
    $user = User::factory()->create();

    FocusSession::factory()->interrupted()->for($user)->create([
        'date' => Carbon::now()->subDays(5)->format('Y-m-d')
    ]);

    $result = (new GetHeatmap)->execute($user);

    expect($result)->toBeEmpty();
});

it('retorna apenas sessões do usuário informado', function () {
    $user      = User::factory()->create();
    $otherUser = User::factory()->create();

    FocusSession::factory()->completed()->for($otherUser)->create([
        'date' => Carbon::now()->subDays(5)->format('Y-m-d')
    ]);

    $result = (new GetHeatmap)->execute($user);

    expect($result)->toBeEmpty();
});

it('ignora sessões com data futura', function () {
    $user = User::factory()->create();

    FocusSession::factory()->completed()->for($user)->create([
        'date' => Carbon::now()->addDays(5)->format('Y-m-d')
    ]);

    $result = (new GetHeatmap)->execute($user);

    expect($result)->toBeEmpty();
});

it('retorna as datas em ordem cronológica crescente', function () {
    $user = User::factory()->create();

    FocusSession::factory()->completed()->for($user)->create([
        'date' => Carbon::now()->subDays(10)->format('Y-m-d')
    ]);
    
    FocusSession::factory()->completed()->for($user)->create([
        'date' => Carbon::now()->subDays(3)->format('Y-m-d')
    ]);

    $result = (new GetHeatmap)->execute($user);

    expect($result->first())->toBeLessThan($result->last());
});