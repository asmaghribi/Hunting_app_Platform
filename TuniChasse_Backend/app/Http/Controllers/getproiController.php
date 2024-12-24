<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proi;


class getproiController extends Controller
{
    public function getproies(){
        $proi = Proi::all();
        if ($proi->count() > 0) {
            \Log::info('Proi retrieved successfully', ['proi' => $proi]);
            return response()->json(['message' => 'Proi retrieved successfully', 'proi' => $proi], 200);
        } else {
            \Log::info('No proi found');
            return response()->json(['message' => 'No proi found'], 404);
        }
     }
}
