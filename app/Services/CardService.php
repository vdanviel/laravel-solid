<?php

use App\Services;
use Illuminate\Http\JsonResponse;
use App\Models\Card;

class CardService {

    public function register(array $data) : JsonResponse
    {

        $card = new Card;

        $card->id_user = $data['id_user'];
        $card->amount = $data['amount'];

        $card->save();

    }

}