<?php
namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function calendar() {
        return view('Admin.Components.calendar');
    }

    public function store(Request $request) {
        // Valider les données de la requête
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        // Créer un nouvel événement
        $event = new Event();
        $event->title = $request->input('title');
        $event->start = $request->input('start');
        $event->end = $request->input('end');

        // Enregistrer l'événement dans la base de données
        $event->save();

        return response()->json(['success' => true]);
    }

    public function getEvents(Request $request) {
        
            $start = $request->input('start');
            $end = $request->input('end');

            $events = Event::whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end])
                ->get();

            return response()->json($events);
        }
}
