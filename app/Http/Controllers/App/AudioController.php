<?php

namespace App\Http\Controllers\App;

use App\Actions\Audio\CreateAudioUser;
use App\Actions\Audio\DeleteAudioUser;
use App\Http\Requests\Audio\FormAudioRequest;
use App\Models\Audio;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Throwable;

class AudioController extends Controller
{
    public function setAudio(FormAudioRequest $request, CreateAudioUser $createAudio)
    {
        try {
            if (!$request->hasFile('file')) {
                throw new Exception('Sem arquivo de audio!');
            }
            $file = $request->file('file');
            return response()->json(['audio' => $createAudio->execute(Auth::user(),$file,$request->title)], 200);
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function getPlaylist()
    {
        try {
            $playlist = Auth::user()->playlist()->get()->values();
            return response()->json($playlist);
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function delete(Request $request,DeleteAudioUser $deleteAudio)
    {
        try {
            $request->validate([
                'id' => ['integer']
            ]);
            $user = Auth::user();
            $deleteAudio->execute($user,$request->id);
            return response()->json("Audio deletado!", 200);
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }

    protected $playlist = [];
    public function reorder(Request $request)
    {
        try {
            $this->playlist = $request->playlist;
            Audio::reorder($this->playlist,Auth::user()->id);
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }
}
