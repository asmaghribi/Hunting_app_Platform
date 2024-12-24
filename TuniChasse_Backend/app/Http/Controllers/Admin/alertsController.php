<?php

namespace App\Http\Controllers\Admin;
use App\Models\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class alertsController extends Controller
{
    public function listalerts(){
        $alerts = $this->getAlertPaginted(10);
        return view('Admin.Components.listalert',compact('alerts'));
    }
    public function getAlertPaginted($paginate)
     {
    $alerts = Alert::paginate($paginate);
    return $alerts;
     }
}
