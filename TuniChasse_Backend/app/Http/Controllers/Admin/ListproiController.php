<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proi;
use App\Models\Polygon;

class ListproiController extends Controller
{
    public function listproies(){
        $proi = $this->getProiPaginted(10);
        return view('admin.Components.listproies',compact('proi'));
    }
    public function getProiPaginted($paginate)
     {
    $proi = Proi::paginate($paginate);
    return $proi;
     }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'species' => 'required|string|max:255',
        'type' => 'required|string|max:255',
    ]);

    $pr = Proi::findOrFail($id);

    if ($request->hasFile('image')) {
        // Supprimer l'ancienne image si elle existe
        if ($pr->image && file_exists(public_path('images/' . $pr->image))) {
            unlink(public_path('images/' . $pr->image));
        }

        // Stocker la nouvelle image
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $pr->image = $imageName;
    }

    $pr->name = $request->input('name');
    $pr->species = $request->input('species');
    $pr->type = $request->input('type');
    $pr->save();

    return redirect()->back()->with('success', 'Les informations de proie ont été mises à jour avec succès.');
}

     public function destroy($id)
     {
         $pr = Proi::findOrFail($id);
         $pr->delete();

         return redirect()->back()->with('success', 'Proie supprimé avec succès.');
     }

    public function getProiNames()
    {
        $proiNames = Proi::pluck('name', 'id'); // Récupérer les noms de la table 'proi'

        return $proiNames;
    }

     }
