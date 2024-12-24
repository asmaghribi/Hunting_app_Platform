<?php

namespace App\Http\Controllers\Admin;
use App\Models\Lois;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class addloisController extends Controller
{
    public function addloi(){

        return view('Admin.Components.addloi');
    }
    public function store(Request $request)
    {
    // Validation (add rules based on your requirements)
    $request->validate([
        'text' => 'required|string|max:255',
        'type' => 'required|string|max:255',

    ]);

    try {

        $loi = new Lois();
        $loi->text = $request->input('text');
        $loi->type = $request->input('type');



        $loi->save();

        // Handle success
        return redirect()->route('admin.listlois')->with('success', 'loi added successfully');
    } catch (\Exception $e) {
        // Handle error
        return back()->withInput()->withErrors(['error' => 'Failed to add loi. Please try again.']);
    }
    }




    }
