<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
    
        $token = $user->createToken('LaravelAuthApp')->accessToken;
    
        return response()->json(['token' => $token], 200);
    }

    // public function getUserDetails(Request $request)
    // {
    //     return response()->json([
    //         'user' => $request->user(),
    //     ], 200);
    // }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            
            auth()->user()->tokens()->delete();
            // $token = auth()->user()->createToken('AuthToken')->accessToken;
            $token = auth()->user()->createToken('AuthToken', ['*'], now()->addMinutes(10));
            // $token = auth()->user()->createToken('AuthToken', ['*'], now()->addHours(2))->accessToken;
            // $token = $token->plainTextToken;
            return response()->json(['token' => $token], 200);

        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // public function logout(Request $request){
    //     return $request->headers->all();
    //     auth()->user()->tokens()->delete();
    //     return response()->json(['message' => 'Successfully logged out.'], 200);
    // }

    public function logout(Request $request)
    {

        return response()->json(['message' => 'Logged out successfully.'], 200);
        Auth::logout();

            // Revoke the user's current token
            $token = $request->user()->token(); // This will throw error if user is null

            if ($token) {
                $token->revoke();
                return response()->json(['message' => 'Logged out successfully.'], 200);
            } else {
                return response()->json(['error' => 'Token not found.'], 400);
            }

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }

}
