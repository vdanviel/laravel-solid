<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

#services
use App\Services\CardService;

#requests
use App\Http\Requests\Card\CardRegisterRequest;
use App\Http\Requests\Card\CardIndexUserItemsRequest;
use App\Http\Requests\Card\CardDeleteRequest;


class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexUserItems(CardIndexUserItemsRequest $request): JsonResponse
    {
        
        try {
            
            return CardService::all_user_items($request->query->getInt('user_id'));

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CardRegisterRequest $request) : JsonResponse
    {
        try {
            
            return CardService::register($request->toArray());

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CardDeleteRequest $request): JsonResponse
    {
        
        try {
            
            return CardService::delete($request->card_id);

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }

    }
}
