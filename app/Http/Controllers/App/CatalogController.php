<?php

namespace App\Http\Controllers\App;

use App\Enums\CardRarity;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\User;
use App\Models\UserCard;
use Exception;
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
            $rarities    = CardRarity::values();
            $placeholders = implode(',', array_fill(0, count($rarities), '?'));

            $cards = Card::orderByRaw(
                "FIELD(rarity, $placeholders)",
                $rarities                          // Laravel faz o binding seguro
            )->get();

            return [
                "cards" => CardResource::collection($cards),
                "total" =>  $cards->count(),
            ];
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }
}
