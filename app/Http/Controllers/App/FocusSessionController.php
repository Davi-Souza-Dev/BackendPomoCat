<?php

namespace App\Http\Controllers\App;

use App\Actions\Analytic\GetTodayFocus;
use App\Actions\Pomodoro\CreateFocusSessions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class FocusSessionController extends Controller
{
    public function newFocus(Request $request, CreateFocusSessions $createFocusSessions,GetTodayFocus $getTodayFocus)
    {

        try {
            // CHECK USER
            $user = Auth::user();

            // VALIDAR
            $validated = $request->validate([
                'duration' => 'integer',
                'status' => 'string',
            ]);

            if (!$validated) {
                throw new Exception('Erro ao validar dados');
            }

            $card = $createFocusSessions->execute($user, $request->only(['duration', 'status']));

            return response()->json(['success' => [
                'card' => $card,
                'todayfocus' => $getTodayFocus->execute($user),
            ]], 200);

        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }
}
