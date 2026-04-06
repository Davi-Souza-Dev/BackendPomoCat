<?php

use App\Actions\Audio\CreateAudioUser;
use App\Models\Audio;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

// --- FormAudioRequest validation tests ---

it('rejeita titulo com menos de 5 caracteres', function () {
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('song.mp3', 1024, 'audio/mpeg');
    $response = $this->actingAs($user)->post(route('audio.set'), [
        'file' => $file,
        'title' => 'abc',
    ]);

    $response->assertSessionHasErrors(['title']);
    expect(session('errors')->first('title'))->toContain('5 caracteres');
});

it('rejeita titulo vazio', function () {
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('song.mp3', 1024, 'audio/mpeg');
    $response = $this->actingAs($user)->post(route('audio.set'), [
        'file' => $file,
        'title' => '',
    ]);

    $response->assertSessionHasErrors(['title']);
    expect(session('errors')->first('title'))->toContain('obrigatorio');
});

it('rejeita titulo que nao e string', function () {
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('song.mp3', 1024, 'audio/mpeg');
    $response = $this->actingAs($user)->post(route('audio.set'), [
        'file' => $file,
        'title' => 12345,
    ]);

    $response->assertSessionHasErrors(['title']);
});

it('rejeita arquivo nao informado', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('audio.set'), [
        'title' => 'Test Audio',
    ]);

    $response->assertSessionHasErrors(['file']);
    expect(session('errors')->first('file'))->toContain('arquivo');
});

it('rejeita arquivo que nao e MP3', function () {
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('song.wav', 1024, 'audio/wav');
    $response = $this->actingAs($user)->post(route('audio.set'), [
        'file' => $file,
        'title' => 'Test Audio',
    ]);

    $response->assertSessionHasErrors(['file']);
    expect(session('errors')->first('file'))->toContain('MP3');
});

it('rejeita titulo com mais de 255 caracteres', function () {
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('song.mp3', 1024, 'audio/mpeg');
    $response = $this->actingAs($user)->post(route('audio.set'), [
        'file' => $file,
        'title' => str_repeat('a', 256),
    ]);

    $response->assertSessionHasErrors(['title']);
});

it('cria audio com titulo valido de 5 caracteres', function () {
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('song.mp3', 1024, 'audio/mpeg');
    $response = $this->actingAs($user)->post(route('audio.set'), [
        'file' => $file,
        'title' => 'abcde',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['audio' => ['id', 'title', 'path', 'order']]);
});

// --- Action unit tests ---

it('cria um audio para o usuario', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('ambient.mp3', 1024, 'audio/mpeg');

    $action = new CreateAudioUser();
    $audio = $action->execute($user, $file, 'Ambient Sound');

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->title)->toBe('Ambient Sound');
    expect($audio->user_id)->toBe($user->id);
    expect($audio->order)->toBe(1);
    expect($audio->path)->toContain('.mp3');

    $this->assertDatabaseHas('audio', [
        'user_id' => $user->id,
        'title' => 'Ambient Sound',
    ]);

    Storage::disk('public')->assertExists('audio/'.$audio->path);
});

it('incrementa a ordem corretamente para multiplas audios', function () {
    $user = User::factory()->create();
    $action = new CreateAudioUser();

    $audio1 = $action->execute($user, UploadedFile::fake()->create('audio1.mp3', 1024, 'audio/mpeg'), 'Audio 1');
    $audio2 = $action->execute($user, UploadedFile::fake()->create('audio2.mp3', 1024, 'audio/mpeg'), 'Audio 2');
    $audio3 = $action->execute($user, UploadedFile::fake()->create('audio3.mp3', 1024, 'audio/mpeg'), 'Audio 3');

    expect($audio1->order)->toBe(1);
    expect($audio2->order)->toBe(2);
    expect($audio3->order)->toBe(3);
});

it('nao interfere na ordem de audios de outros usuarios', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $action = new CreateAudioUser();

    $action->execute($user1, UploadedFile::fake()->create('u1a1.mp3', 1024, 'audio/mpeg'), 'U1 Audio 1');
    $action->execute($user1, UploadedFile::fake()->create('u1a2.mp3', 1024, 'audio/mpeg'), 'U1 Audio 2');

    $user2FirstAudio = $action->execute($user2, UploadedFile::fake()->create('u2a1.mp3', 1024, 'audio/mpeg'), 'U2 Audio 1');

    expect($user2FirstAudio->order)->toBe(1);
});

it('armazena o arquivo no disco public', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('rain.mp3', 2048, 'audio/mpeg');

    $action = new CreateAudioUser();
    
    $audio = $action->execute($user, $file, 'Rain Sounds');

    Storage::disk('public')->assertExists('audio/'.$audio->path);
    Storage::disk('public')->assertExists('audio/'.$audio->path);
});

it('não permite criar audios com o titulo vazio', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('test.mp3', 512, 'audio/mpeg');

    $action = new CreateAudioUser();
    $audio = $action->execute($user, $file, '');

    expect($audio->title)->toBe('');
    expect($audio->order)->toBe(1);
});
