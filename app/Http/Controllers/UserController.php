<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function store(RegisterUserRequest $request)
    {

        //dd($request->validated());
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => \Illuminate\Support\Facades\Hash::make($request->name)//https://laravel.com/docs/11.x/hashing#hashing-passwords,
        ]);

        return new UserResource($user);
    }
}
