<?php

namespace App\Http\Controllers\Admin;
use App\Models\Position;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PositionController extends Controller
{
  public function suivimap()
{
    $positions = Position::all();
    $positionsJson = json_encode($positions);
    return view('Admin.Components.suivimap', ['positionsJson' => $positionsJson]);
}


}
