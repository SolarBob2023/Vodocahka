<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);
        $data['role'] = 1;
        $user = User::create($data);
        Auth::login($user, false);
        $user->createToken('user', ['user'])->plainTextToken;
        $request->session()->regenerate();
        return UserResource::make($user);
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
//        $user = auth()->user();
//        if ($user->tokenCan('user')){
//            return $user->tokens()->get();
//        } else return 222;
        return response()->Json(["Вы вышлки из аккуанта"],200);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (Hash::check($data['password'], $user->password)){
            Auth::login($user, false);
            $request->session()->regenerate();
            if ($user->role == 1) {
                $user->createToken('user', ['user'])->plainTextToken;
            }
            if ($user->role == 2) {
                $user->createToken('admin', ['admin'])->plainTextToken;
            }
            return UserResource::make($user);
        } else {
            return response()->json(['errors'=> ['password' => ['Неверный пароль']]], 422);
        }

    }
}
