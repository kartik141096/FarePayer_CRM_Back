<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{

    public function register(Request $request)
    {
        $errors = [];

        if (empty($request->name)) {
            $errors[] = 'Name is required.';
        }
        if (empty($request->role_id)) {
            $errors[] = 'User Role is required.';
        }
        if (empty($request->mobile)) {
            $errors[] = 'Mobile is required.';
        }
        if (empty($request->user_type)) {
            $errors[] = 'User Type is required.';
        }

        if (empty($request->email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is not valid.';
        } else {
            $existingUser = User::where('email', $request->email)->withTrashed()->first();

            if ($existingUser) {
                if ($existingUser->trashed()) {
                    // Restore the user if they are soft deleted
                    $existingUser->deleted_at = null;
                    $existingUser->save();
                    return response()->json(['message' => 'User restored successfully.'], 200);
                } else {
                    $errors[] = 'Email is already taken.';
                }
            }
        }

        if (empty($request->password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($request->password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'mobile' => $request->mobile
        ]);
        return response()->json([
            'message' => 'User registered successfully',
            'user_id' => $user->id
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User ID not found'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Wrong password'], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('AuthToken', ['*'], now()->addMinutes(10))->accessToken;

        return response()->json([
            'token' => $token,
            'role' => $user->role,
            'user_type' => $user->user_type,
            'user_id' => $user->id
        ], 200);
    }

    public function isLoggedin(Request $request)
    {
        // Retrieve the authenticated user
        $user = $request->user();

        // Check if user exists and has a token
        if ($user && $user->token()) {
            $token = $user->token();

            // Check if the token has expired
            if ($token->expires_at < now()) {
                return response()->json(['error' => 'Connection Time Out'], 401);
            }

            return response()->json(['message' => 'User is Logged In'], 200);
        } else {
            return response()->json(['error' => 'User is not logged in or token not found.'], 401);
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

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function getUsers(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('search_by_name_email_mobile')) {
            $search = $request->search_by_name_email_mobile;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%");
            });
        }
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }
        if ($request->filled('type')) {
            $query->where('user_type', $request->type);
        }
        if ($request->filled('created_from')) {
            $query->where('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->where('created_at', '<=', $request->created_to);
        }
        $users = $query->paginate(10);
        return response()->json($users);
    }

    public function getAllRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
    
    public function getUserDetails($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user['img'] = asset('storage/'.$user['img']);
        $user['role_id'] = (int)$user['role_id'];
        return response()->json($user);
    }

    public function deleteUser($id)
    {
        // dd("hello");
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully.']);
        }


        return response()->json(['message' => 'User not found.'], 404);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'mobile' => 'nullable|string|max:15',
            'role_id' => 'nullable|exists:roles,id',
            'user_type' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user->update($validatedData);
        if ($request->hasFile('img')) {
            if ($user->img) {
                $oldImagePath = storage_path('app/public/' . $user->img);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);  // Delete the old image file
                }
            }
            $imagePath = $request->file('img')->store('profile_images', 'public');
            $user->img = $imagePath;
        } elseif ($request->has('delete_image') && $request->delete_image) {    //For Deleting user image
            if ($user->img) {
                $oldImagePath = storage_path('app/public/' . $user->img);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $user->img = null;
            }
        }
        $user->save();
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function getSalesUsers(Request $request)
    {
        $request->validate([
            'user_type' => 'required|string'
        ]);
    
        // Retrieve users with the specified user_type and role_id = 3
        $users = DB::table('users')
            ->where('role_id', 3)
            ->when($request->user_type !== 'all', function ($query) use ($request) {
                return $query->where('user_type', $request->user_type);
            })
            ->select('id', 'name')
            ->get();
    
        return response()->json($users);
    }
    
}
