<?php

namespace App\Actions\Audio;

use App\Models\Audio;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DeleteAudioUser
{
    public function execute(User $user, int $audioID)
    {
        $audio = Audio::where('id', $audioID)->where('user_id', $user->id)->first();
        if (Storage::exists('audio/' . $audio->path)) {
            Storage::disk('public')->delete('audio/' . $audio->path);
        }
        $audio->delete();
    }
}
