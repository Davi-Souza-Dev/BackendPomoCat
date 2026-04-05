<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\Audio\FormAudioRequest;
use App\Models\Audio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AudioController extends Controller
{
    public function setAudio(FormAudioRequest $request)
    {

        try {
            if (!$request->hasFile('file')) {
                throw new Exception('Sem arquivo de audio!');
            }

            $path = $request->file('file')->store('audio', 'public');
            $order = Audio::where('user_id',Auth::user()->id)->count() + 1;
            Audio::create([
                'title' => $request->title,
                'path' => basename($path),
                'user_id' => Auth::user()->id,
                'order' => $order
            ]);

        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function getPlaylist()
    {
        try {
            $user = Auth::user();
            $playlist = Audio::where('user_id', $user->id)->select('title','order','path')->orderBy('order','ASC')->limit(20)->get();
            return response()->json($playlist);
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }
}
