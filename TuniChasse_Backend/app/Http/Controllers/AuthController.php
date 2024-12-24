<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Valider les données de la demande
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentative de connexion
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        // Échec de l'authentification
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    public function getUserDetails()
{
    // Récupérer tous les utilisateurs
    $users = User::all();

    // Retourner les utilisateurs en réponse JSON
    return response()->json([
        'users' => $users,
    ]);
}
public function logout(Request $request)
{
    Auth::logout();

    return response()->json([
        'message' => 'Logged out successfully',
    ], 200);
}



public function updateProfile(Request $request)
{
    $email = $request->input('email');

    $user = User::where('email', $email)->first();

if (!$user) {
    return response()->json(['message' => 'User not found'], 404);
}

    $request->validate([
        'Firstname' => 'required',
        'Lastname' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'required',
        'adresse' => 'required',
    ]);

    $userData = $request->only('Firstname', 'Lastname', 'email', 'phone', 'adresse');

    $user->update($userData);

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $user,
    ]);
}



}
