<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\Audio\FormAudioRequest;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class AudioController extends Controller
{
    public function setAudio(FormAudioRequest $request)
    {

        try {
            if (!$request->hasFile('file')) {
                throw new Exception('Sem arquivo de audio!');
            }

            $path = $request->file('file')->store('audio','public');

        } catch (Throwable $error) {
            dd($error->getMessage());
        }
    }
}
