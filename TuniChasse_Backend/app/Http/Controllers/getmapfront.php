<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Polygon;

use Illuminate\Support\Facades\DB;

class getmapfront extends Controller
{



  public function getmapfront(){
    $data = DB::table('polygons')
    ->select('polygons.id','polygons.coordinates','polygons.title' ,'polygons.color')
    ->get();


        return response()->json(['polygons' => $data]);

   }
}
