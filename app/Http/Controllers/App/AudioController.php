<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\Audio\FormAudioRequest;
use App\Models\Audio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            $order = Audio::where('user_id', Auth::user()->id)->count() + 1;
            $audio = Audio::create([
                'title' => $request->title,
                'path' => basename($path),
                'user_id' => Auth::user()->id,
                'order' => $order
            ]);

            if($audio){
                return response()->json(['audio' => $audio],200);
            }
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function getPlaylist()
    {
        try {
            $user = Auth::user();
            $playlist = Audio::where('user_id', $user->id)->select('id', 'title', 'order', 'path')->orderBy('order', 'ASC')->limit(20)->get();
            return response()->json($playlist);
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $user = Auth::user();
            $audio = Audio::where('id', $request->id)->where('user_id', $user->id)->first();
            Storage::disk('public')->delete('audio/' . $audio->path);
            $audio->delete();
            return response()->json("Audio deletado!", 200);
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function reorder(Request $request){
        try{
            $user = Auth::user();
            $playlist = $request->playlist;
            foreach($playlist as $order){
                $audio = Audio::where('user_id',$user->id)->where('id',$order['id'])->first();
                $audio->order = $order['order'];
                $audio->save();
            }

        }catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }
}
