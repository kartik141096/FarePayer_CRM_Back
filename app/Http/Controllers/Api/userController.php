<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{

    public function register(Request $request)
    {

        $errors = [];

        // Check if name is provided
        if (empty($request->name)) {
            $errors[] = 'Name is required.';
        }

        // Check if email is provided and valid
        if (empty($request->email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is not valid.';
        } else {
            // Check if email already exists
            if (User::where('email', $request->email)->exists()) {
                $errors[] = 'Email is already taken.';
            }
        }

        // Check if password is provided and meets criteria
        if (empty($request->password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($request->password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        // If there are errors, return response
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            
            auth()->user()->tokens()->delete();
            
            $token = auth()->user()->createToken('AuthToken', ['*'], now()->addMinutes(15));

            return response()->json(['token' => $token], 200);

        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {

        if($request->user()->token() != NULL){
            
            $token = $request->user()->token();
            $token->revoke();
            return response()->json(['message' => 'Logged out successfully.'], 200);
            
        }else{
            return response()->json(['error' => 'Token not found.'], 400);
        }
    }

    public function changePassword(Request $request)
    {
        if (empty($request->current_password)) {
            $errors[] = 'Current-Password is required.';
        }
        
        if (empty($request->new_password)) {
            $errors[] = 'New-Password is required.';
        } elseif (strlen($request->new_password) < 8) {
            $errors[] = 'New-Password must be at least 8 characters.';
        }
        
        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect.'], 400);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully.']);
    }

}
