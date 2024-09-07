<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Order;
use Stripe\Exception\SignatureVerificationException;

/*
    {
        "object": {
            "id": "cs_test_b14PaQxnDxaj1yQ3K8TWLS4kx9IuTtW5UxwWfoO9ZQIJeyhsxss3sKXaCe",
            "object": "checkout.session",
            "after_expiration": null,
            "allow_promotion_codes": null,
            "amount_subtotal": 100,
            "amount_total": 100,
            "automatic_tax": {
            "enabled": false,
            "liability": null,
            "status": null
            },
            "billing_address_collection": null,
            "cancel_url": "http://127.0.0.1:8000/api/order/cancel",
            "client_reference_id": null,
            "client_secret": null,
            "consent": null,
            "consent_collection": null,
            "created": 1725663750,
            "currency": "brl",
            "currency_conversion": null,
            "custom_fields": [
            ],
            "custom_text": {
            "after_submit": null,
            "shipping_address": null,
            "submit": null,
            "terms_of_service_acceptance": null
            },
            "customer": null,
            "customer_creation": "if_required",
            "customer_details": {
            "address": {
                "city": null,
                "country": "BR",
                "line1": null,
                "line2": null,
                "postal_code": null,
                "state": null
            },
            "email": "victordn.araujo@gmail.com",
            "name": "VICTOR DANIEL",
            "phone": null,
            "tax_exempt": "none",
            "tax_ids": [
            ]
            },
            "customer_email": null,
            "expires_at": 1725750149,
            "invoice": null,
            "invoice_creation": {
            "enabled": false,
            "invoice_data": {
                "account_tax_ids": null,
                "custom_fields": null,
                "description": null,
                "footer": null,
                "issuer": null,
                "metadata": {
                },
                "rendering_options": null
            }
            },
            "livemode": false,
            "locale": null,
            "metadata": {
            },
            "mode": "payment",
            "payment_intent": "pi_3PwBBbI0e8IT3H341HWLNrcJ",
            "payment_link": null,
            "payment_method_collection": "if_required",
            "payment_method_configuration_details": {
            "id": "pmc_1MuOCYI0e8IT3H34cvzxG95S",
            "parent": null
            },
            "payment_method_options": {
            "card": {
                "request_three_d_secure": "automatic"
            }
            },
            "payment_method_types": [
            "card"
            ],
            "payment_status": "paid",
            "phone_number_collection": {
            "enabled": false
            },
            "recovered_from": null,
            "saved_payment_method_options": null,
            "setup_intent": null,
            "shipping_address_collection": null,
            "shipping_cost": null,
            "shipping_details": null,
            "shipping_options": [
            ],
            "status": "complete",
            "submit_type": null,
            "subscription": null,
            "success_url": "http://127.0.0.1:8000/api/order/success",
            "total_details": {
            "amount_discount": 0,
            "amount_shipping": 0,
            "amount_tax": 0
            },
            "ui_mode": "hosted",
            "url": null
        }
    }
*/

class StripeService {

    public static function checkoutDoneWebhook(Request $request)
    {
        $endpointSecret = config('stripe.webhook_secret');
    
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
    
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
    
            //file_put_contents(base_path('storage/logs/webhook/stripe_checkout.log'), json_decode($payload, true)['data']['object']['id'], FILE_APPEND);
    
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            file_put_contents(
                base_path('storage/logs/webhook/stripe_checkout.log'), 
                $e->getMessage() . PHP_EOL,
                FILE_APPEND
            );
            return new JsonResponse(['error' => 'Invalid signature'], 400);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            file_put_contents(
                base_path('storage/logs/webhook/stripe_checkout.log'), 
                $e->getMessage() . PHP_EOL,
                FILE_APPEND
            );
            return new JsonResponse(['error' => 'Invalid payload'], 400);
        }
    
        $data = json_decode($payload, true);
    
        $order = Order::where('stripe_session_id', $data['data']['object']['id'])->first();
    
        if (!$order) {
            file_put_contents(
                base_path('storage/logs/webhook/stripe_checkout.log'), 
                '404 ORDER NOT FOUND ' . PHP_EOL, 
                FILE_APPEND
            );
            return new JsonResponse(['error' => 'Order not found'], 404);
        }
    
        switch ($event->type) {
            case 'checkout.session.completed':
                $order->status = 'done';
                $order->save();
                break;
    
            case 'checkout.session.expired':
                $order->status = 'cancelled';
                $order->save();
                break;
    
            case 'payment_intent.succeeded':
                $order->status = 'done';
                $order->save();
                break;
    
            default:
                // Handle other events if necessary
                return new JsonResponse(['error' => 'Event type not handled'], 400);
        }
    
        return new JsonResponse(['status' => 'success'], 200);
    }
    

}