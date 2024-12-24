<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class calendarfrontController extends Controller
{
    public function getcalendar(Request $request)
    {
        // Récupérer tous les événements depuis la base de données
        $events = Event::all();

        // Retourner les événements sous forme de réponse JSON
        return response()->json($events);
    }
}
