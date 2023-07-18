<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);
        $data['role'] = 1;
        DB::beginTransaction();
        try {
            $user = User::create($data);
        } catch (\Exception $exception){
            DB::rollback();
            throw $exception;
        }
        DB::commit();

        Auth::login($user, 1);
        $request->session()->regenerate();
        return UserResource::make($user);
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->Json(["Вы вышлки из аккуанта"],200);
    }

    public function show()
    {
        $user = auth()->user();
        return UserResource::make($user);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (Hash::check($data['password'], $user->password)){
            Auth::login($user, 1);
            $request->session()->regenerate();
            return UserResource::make($user);
        } else {
            return response()->json(['errors'=> ['password' => ['Неверный пароль']]], 422);
        }

    }
}
