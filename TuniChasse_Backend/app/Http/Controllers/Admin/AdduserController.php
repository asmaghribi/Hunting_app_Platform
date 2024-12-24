<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AdduserController extends Controller
{
    public function addusers(){

        return view('Admin.Components.addusers');
    }
    public function store(Request $request)
{
    // Validation (add rules based on your requirements)
    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'Permis' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password'=> 'required|string|max:255',
        'adresse' => 'required|string|max:255',

    ]);

    try {
        // Create the user
        $user = new User();
        $user->Firstname = $request->input('firstname');
        $user->Lastname = $request->input('lastname');
        $user->Permis = $request->input('Permis');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->adresse = $request->input('adresse');


        $user->save();

        // Handle success
        return redirect()->route('admin.listusers')->with('success', 'User added successfully');
    } catch (\Exception $e) {
        // Handle error
        return back()->withInput()->withErrors(['error' => 'Failed to add user. Please try again.']);
    }
}




}
