<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\FocusSession;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FocusSessionController extends Controller
{
    public function newFocus(Request $request)
    {

        try {
            // CHECK USER
            $user = User::where('username',$request->username)->first();
  

            $focus = FocusSession::create([
                "user_id" => $user->id,
                "duration" => $request->duration,
                "status" => $request->status,
                "date" => today(),
            ]);

            if ($focus) {
                return response()->json(['success' => [
                    "card" => $this->getCard($user->id),
                ]], 200);
            }

        } catch (Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 422);
        }
    }

    public function getCard($userId)
    {
        $cards = Card::all();
        $totalPeso = $cards->sum('peso');

        $sorteio = rand(1, $totalPeso);
        $acumulado = 0;

        foreach ($cards as $card) {
            $acumulado += $card->peso;
            if ($sorteio <= $acumulado) {
                UserCard::create([
                    "card_id" => $card->id,
                    "user_id" => $userId,
                ]);
                return $card;
            }
        }
    }
}
