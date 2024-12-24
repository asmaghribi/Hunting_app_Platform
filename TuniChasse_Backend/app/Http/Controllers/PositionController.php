<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $position = Position::create($validatedData);

        return response()->json(['message' => 'Position created successfully']);
    }
   
}
