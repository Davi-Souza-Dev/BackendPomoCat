<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CardController
{
    public function setCard(Request $request)
    {
        try {
            $card = json_decode($request->card, true);

            // ATUALIZA
            if ($card['id'] != 0) {
                $cardORM = Card::find($card['id']);
                $cardORM->title = $card['title'];
                $cardORM->rarity = $card['rarity'];
                if ($request->hasFile('imagem')) {
                    $imagem = ' ';
                    if ($request->imagem) {
                        $path = Storage::disk('public')->put('cards', $request->imagem);
                        $imagem = basename($path);
                    }
                    $cardORM->image = $imagem;
                }

                $cardORM->save();
                return [
                    'success' => [
                        'titulo' => 'Card Atualizado!',
                        'code' => 200,
                    ]
                ];
            } else {

                if ($request->hasFile('imagem')) {
                    $imagem = ' ';
                    if ($request->imagem) {
                        $path = Storage::disk('public')->put('cards', $request->imagem);
                        $imagem = basename($path);
                    }
                }

                // CRIA
                $newCard = Card::create([
                    "title" => $card["title"],
                    "rarity" => $card["rarity"],
                    "image" => $imagem,
                ]);

                if ($newCard) {
                    return [
                        'success' => [
                            'titulo' => 'Card Criado!',
                            'code' => 200,
                        ],
                        'card' => $newCard,
                    ];
                }
            }
        } catch (Throwable $error) {
            if ($error->getCode() == "23000") {
                return [
                    'error' => [
                        'titulo' => 'Card já existe!',
                    ]
                ];
            }
            return [
                'error' => [
                    'titulo' => 'Algo de errado!',
                    'message' => $error->getMessage(),
                    'code' => $error->getCode(),
                ]
            ];
        }
    }

    public function delete(Request $request)
    {
        try {
            $card = Card::find($request->id);
            $card->delete();

            return [
                'success' => [
                    'titulo' => 'Card Deletado!',
                ]
            ];
        } catch (Throwable $error) {
            return [
                'error' => [
                    'titulo' => 'Algo de errado!',
                    'message' => $error->getMessage(),
                    'code' => $error->getCode(),
                ]
            ];
        }
    }
}
