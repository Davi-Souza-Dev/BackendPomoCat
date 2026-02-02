<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\UserCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CatalogController
{
    public function getCatalog(Request $request)
    {
        try {
            // CHECK USER
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(["error" => 'Usuario Não Encontrado'], 404);
            }
            $cards = Card::orderByRaw("FIELD(rarity, 'comum', 'incomum', 'raro', 'epico', 'lendario') ASC")->get();
            $userCollection = $user->cards()->pluck('card_id')->toArray();
            $totalCards = Card::all()->count();
            $unlockCards = 0;
            foreach ($cards as $card) {
                if (!in_array($card->id, $userCollection)) {
                    $card->image = "bloqueado.png";
                    $card->title = "???";
                }
            }
            return [
                "cards" => $cards,
                "total" => $totalCards,
            ];
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

}
