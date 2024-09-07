<?php

return [
    'secret_key' => env('SECRET_KEY_STRIPE'),//https://laravel.com/docs/11.x/helpers#method-env
    'webhook_secret' => env('WEBHOOK_SECRET_STRIPE')
];