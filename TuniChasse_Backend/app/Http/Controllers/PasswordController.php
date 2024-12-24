<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PasswordController extends Controller
{
    public function updatePassword(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'password' => 'required',
            'newpassword' => 'required|string|min:6|confirmed',
        ]);

        

        // Vérifier si le mot de passe actuel fourni correspond à celui stocké dans la base de données
        if (empty($user->password) || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Mot de passe actuel incorrect'], 400);
        }

        // Mettre à jour le mot de passe de l'utilisateur dans une transaction
        try {
            DB::beginTransaction();

            $user->password = Hash::make($request->newpassword);
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la mise à jour du mot de passe'], 500);
        }

        return response()->json(['message' => 'Mot de passe mis à jour avec succès'], 200);
    }
}
