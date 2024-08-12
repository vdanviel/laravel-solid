<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class UserController extends Controller
{
    
    public function register(Request $req) : JsonResponse
    {
        try {
            
            //https://laravel.com/docs/11.x/validation#quick-writing-the-validation-logic
            $req->validate(
                [
                    'name' => 'required',
                    'email' => ['required','unique:users,email','email'],
                    'phone_number' => 'required',
                    'password' => 'required'
                ]
            );
    
            $user = new User;
    
            $user->name = $req->input('name');
            $user->email = $req->input('email');
            $user->phone_number = $req->input('phone_number');
            $user->password = \Illuminate\Support\Facades\Hash::make($req->input('name'));//https://laravel.com/docs/11.x/hashing#hashing-passwords

            $user->save();

            return new JsonResponse([
                "status" => true,
                "id" => $user->id
            ], 200, ['Content-Type: applicaiton/json']);

        } catch (\Throwable $th) {
            
            return new JsonResponse([
                "status" => false,
                "message" => $th->getMessage(),
            ], 400, ['Content-Type: applicaiton/json']);

        }


    }

}
