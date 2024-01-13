<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Laravel\Passport\Passport;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Input missing', 'details' => $validator->errors()], 422);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
    
            if (!$user) {
                return response()->json(['error' => 'Invalid account'], 401);
            }
    
            return response()->json(['error' => 'Incorrect password'], 401);
        }
    
        $user = Auth::user();
        $role = DB::table('users')->where('id', $user->id)->value('role');

        $expirationTimeInMinutes = 60;
        $token = $user->createToken('authToken', ['*'], null, 3600)->accessToken;
        $token->expires_at = now()->addMinutes($expirationTimeInMinutes);
        $token->save();

        return response()->json([
            'token' => $token->token,
            'expire' => $token->expires_at,
            'role' => $role,
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registerEmail' => 'required|email|min:4|max:100|unique:users,email',
            'registerPassword' => 'required|min:8',
            'confirmRegisterPassword' => 'required|same:registerPassword|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'email' => $request->input('registerEmail'),
            'password' => Hash::make($request->input('registerPassword')),
        ]);

        $token = $user->createToken('ticket-app')->accessToken;

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
        }

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

}
