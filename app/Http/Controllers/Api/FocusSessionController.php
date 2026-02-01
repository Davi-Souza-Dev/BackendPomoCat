<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\FocusSession;
use App\Models\UserCard;
use Illuminate\Http\Request;
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
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(["error" => 'Usuario Não Encontrado'], 404);
            }

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

        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Erro ao processar o token'], 500);
        } catch (Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 500);
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
