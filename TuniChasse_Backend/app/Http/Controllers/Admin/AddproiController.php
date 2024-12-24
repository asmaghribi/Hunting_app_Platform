<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proi;

class AddproiController extends Controller
{
    public function addproies(){

    return view('admin.Components.addproies');
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'species' => 'required|string|max:255',
        'type' => 'required|string|max:255',
    ]);

    $imageName = time().'.'.$request->image->extension();
    $request->image->move(public_path('images'), $imageName);

    $pr = new Proi();
    $pr->name = $request->input('name');
    $pr->image = $imageName;
    $pr->species = $request->input('species');
    $pr->type = $request->input('type');
    $pr->save();

    return redirect()->route('admin.listproies')->with('success', 'Proi added successfully');
}




}
