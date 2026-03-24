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
            $orderRarity = implode(' ',CardRarity::values());
            $cards = Card::orderByRaw("FIELD(rarity, '$orderRarity') ASC")->get();
            return [
                "cards" => CardResource::collection($cards),
                "total" => Card::count(),
            ];
            
        } catch (Throwable $error) {
            throw new Exception($error->getMessage());
        }
    }

}
