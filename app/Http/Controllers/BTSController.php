<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BTSController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'email' => 'string',
            'password' => 'string',
            'username' => 'string'
        ]);

        $user = User::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'name' => $fields['username']
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $stat = 200;
        $result = [];
        $fields = $request->validate([
            'email' => 'string',
            'password' => 'string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            $stat = 401;
            $result = ['message' => 'Email is not register!'];
        }

        $result = ['user' => $user->name, 'token' => $user->createToken('myapptoken')->plainTextToken];
        return Response($result,$stat);
    }
}
