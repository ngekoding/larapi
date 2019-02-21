<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Transformers\UserTransformer;
use Auth;
use Fractal;

class UserController extends Controller
{
    /**
     * Get all registered users
     *
     * @return array
     */
    public function users()
    {
        $users = User::all();

        return Fractal::collection($users)
            ->transformWith(new UserTransformer())
            ->toArray();
    }

    /**
     * Get current user profile
     *
     * @return array
     */
    public function profile()
    {
        $user = Auth::user();

        return Fractal::item($user)
            ->transformWith(new UserTransformer())
            ->includePosts()
            ->toArray();
    }
}
