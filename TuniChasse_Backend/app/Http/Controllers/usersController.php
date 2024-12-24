<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class usersController extends Controller
{
    public function getusers()
    {
        $users = User::all();

        if ($users->count() > 0) {
            \Log::info(['users' => $users]);
            return response()->json([ 'users' => $users], 200);
        } else {
            \Log::info('No users found');
            return response()->json(['message' => 'No us found'], 404);
        }
    }
}
