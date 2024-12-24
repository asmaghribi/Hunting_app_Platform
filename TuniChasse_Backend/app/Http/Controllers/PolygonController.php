<?php

namespace App\Http\Controllers;

use App\Models\Polygon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PolygonController extends Controller
{
    public function getPolygons()
    {
        $polygons = Polygon::all();

        if ($polygons->count() > 0) {
            \Log::info('Polygons retrieved successfully', ['polygons' => $polygons]);
            return response()->json(['message' => 'Polygons retrieved successfully', 'polygons' => $polygons], 200);
        } else {
            \Log::info('No polygons found');
            return response()->json(['message' => 'No polygons found'], 404);
        }
    }
}
