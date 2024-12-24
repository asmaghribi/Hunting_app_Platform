<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Alert;
use App\Models\Event;
use App\Models\Proi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
{
    // Filtrer les utilisateurs en "New Visitor" et "Old Visitor"
    $newVisitorsCount = User::whereDate('created_at', '>=', now()->subMonth())->count();
    $oldVisitorsCount = User::whereDate('created_at', '<', now()->subMonth())->count();

    $users = User::all();
    $alerts = Alert::all();
    $events = Event::all();
    $prois = Proi::all();
    // Récupérer les données pour le tableau
    $proiTypes = Proi::select('type')->get()->toArray();
    $proiTypesCount = [];
    $proiTypesPercentages = [];
    foreach ($proiTypes as $proiType) {
        $type = $proiType['type'];
        $count = Proi::where('type', $type)->count();
        $proiTypesCount[$type] = $count;
        $proiTypesPercentages[$type] = round(($count / $prois->count()) * 100, 2);
    }

    return view('Admin.Components.index', compact('newVisitorsCount', 'oldVisitorsCount', 'alerts', 'events', 'prois','users', 'proiTypesCount', 'proiTypesPercentages'));
}


}
