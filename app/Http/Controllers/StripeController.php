<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function handlePayment(Request $request)
    {
        try {
            
            return StripeService::checkoutDoneWebhook($request);

        } catch (\Throwable $th) {

            file_put_contents(base_path('/storage/logs/webhook/stripe_checkout.log'), $th->getMessage(), FILE_APPEND);

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
