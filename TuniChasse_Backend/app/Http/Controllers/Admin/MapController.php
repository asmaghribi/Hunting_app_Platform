<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proi;
use App\Models\Polygon;
use App\Models\ForeignTableProigeo;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\ListproiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class MapController extends Controller
{
    public function maps()
    {
        $jsonData = Storage::disk('local')->get('poly.json');
        $polygons = json_decode($jsonData);

        return view('Admin.Components.map', compact('polygons'));
    }
    public function updatePolygon(Request $request)
{
    // Valider les données
    $request->validate([
        'polygon_name' => 'sometimes|required|string',
        'polygon_color' => 'sometimes|required|in:green,red',
    ]);

    // Récupérer le contenu actuel du fichier JSON
    $jsonData = File::get(public_path('poly.json'));
    $polygons = json_decode($jsonData, true);

    // Rechercher le polygone à mettre à jour en fonction de l'ID
    $polygonId = $request->input('polygon_id');
    $polygonIndex = null;

    for ($i = 0; $i < count($polygons); $i++) {
        if ($polygons[$i]['id'] == $polygonId) {
            $polygonIndex = $i;
            break;
        }
    }

    // Mettre à jour les données du polygone
    if ($polygonIndex !== null) {
        // Vérifier si le nom du polygone a été modifié
        if ($request->has('polygon_name')) {
            $polygons[$polygonIndex]['title'] = $request->input('polygon_name');
        }

        // Vérifier si la couleur du polygone a été modifiée
        if ($request->has('polygon_color')) {
            $polygons[$polygonIndex]['color'] = $request->input('polygon_color');
        }

        // Enregistrer les modifications dans le fichier JSON
        File::put(public_path('poly.json'), json_encode($polygons, JSON_UNESCAPED_UNICODE));

        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false, 'error' => 'Polygon not found'], 404);
    }
}

public function storePolygon(Request $request)
{
    // Valider les données
    $request->validate([
        'newPolygonName' => 'required|string',
        'newPolygonColor' => 'required|in:green,red',
        'newPolygonCoordinates' => 'required|string',
    ]);

    // Générer un nouvel ID pour le polygone
    $polygonId = uniqid();

    // Récupérer le contenu actuel du fichier JSON
    $jsonData = File::get(public_path('poly.json'));
    $polygons = json_decode($jsonData, true);

    // Décoder les coordonnées du nouveau polygone
    $coordinates = json_decode($request->input('newPolygonCoordinates'), true);

    // Extraire le premier élément du tableau de coordonnées
    $coordinates = array_shift($coordinates);

    // Ajouter le nouveau polygone aux données
    $newPolygon = [
        'id' => $polygonId,
        'title' => $request->input('newPolygonName'),
        //'title' => utf8_encode($request->input('newPolygonName')),
        'color' => $request->input('newPolygonColor'),
        'coordinates' => json_encode($coordinates, JSON_UNESCAPED_UNICODE), // Convertir les coordonnées en chaîne de caractères
    ];
    $polygons[] = $newPolygon;

    // Enregistrer les modifications dans le fichier JSON
    File::put(public_path('poly.json'), json_encode($polygons, JSON_UNESCAPED_UNICODE));

    return response()->json(['success' => true]);
}




}
