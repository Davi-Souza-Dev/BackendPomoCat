<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $user = User::where('username',$request->username)->first();
            $cards = Card::orderByRaw("FIELD(rarity, 'comum', 'incomum', 'raro', 'epico', 'lendario') ASC")->get();
            $userCollection = UserCard::where('user_id',$user->id)->pluck('card_id')->toArray();
            $totalCards = Card::all()->count();
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
        } catch (Throwable $error) {
            return response()->json(['error' => $error->getMessage()], 422);
        }
    }

}
