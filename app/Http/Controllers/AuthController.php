<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Fractal;
use Auth;

use App\User;
use App\Transformers\UserTransformer;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'api_token' => bcrypt($request->email),
        ]);

        return Fractal::item($user)
            ->transformWith(new UserTransformer())
            ->addMeta([
                'token' => $user->api_token
            ])
            ->respond(201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'You haven\'t registered yet!'
            ], 401);
        }

        $user = Auth::user();

        return Fractal::item($user)
            ->transformWith(new UserTransformer())
            ->addMeta([
                'token' => $user->api_token
            ])
            ->toArray();
    }
}
