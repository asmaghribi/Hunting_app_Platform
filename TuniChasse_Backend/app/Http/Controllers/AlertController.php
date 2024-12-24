<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Alert;



class AlertController extends Controller
{
    public function sendAlert(Request $request)
    {
        // Récupérer le message d'alerte et les coordonnées de position envoyés depuis l'application cliente
        $message = $request->input('message');
        $position = $request->input('position');

        // Enregistrer l'alerte dans la base de données
        $alert = Alert::create([
            'message' => $message,
            'position' => $position,
        ]);

        // Réponse à l'application cliente
        return response()->json(['message' => 'Alerte reçue avec succès!']);
    }

}
