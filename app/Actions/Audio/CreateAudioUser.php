<?php

namespace App\Actions\Audio;

use App\Models\Audio;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class CreateAudioUser
{
    public function execute(User $user, UploadedFile $file, string $title) : Audio
    {
        $path = $file->store('audio', 'public');
        $order = Audio::where('user_id', $user->id)->count() + 1;
        $audio = Audio::create([
            'title' => $title,
            'path' => basename($path),
            'user_id' => $user->id,
            'order' => $order
        ]);

        return $audio;
    }
}
