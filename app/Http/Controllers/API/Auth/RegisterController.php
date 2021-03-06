<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        Auth::login($user);

        // assign super-admin role by email

        $user->email == config('permission.default_super_admin_email') ? $user->assignRole('super-admin') : $user->assignRole('user');

        $token = $user->createToken('user_token');

        return response()->json([
            'status' => true,
            'massage' => 'user created successfully',
        ], Response::HTTP_CREATED);
    }
}
