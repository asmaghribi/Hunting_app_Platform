<?php

namespace App\Http\Controllers\Admin;
use App\Models\Lois;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class loisController extends Controller
{
    public function listlois(){
        $lois = $this->getLoiPaginted(10);
        return view('Admin.Components.listlois',compact('lois'));
    }
    public function getLoiPaginted($paginate)
     {
    $lois = Lois::paginate($paginate);
    return $lois;
     }


     public function update(Request $request, $id)
{
      $loi = Lois::findOrFail($id);

    // Mettez à jour les données de l'utilisateur en fonction des données du formulaire
     $loi->update([
        'text' => $request->text,
        'type' => $request->type,



        // Ajoutez d'autres champs à mettre à jour ici
    ]);

    // Rediriger vers une page appropriée après la mise à jour
    return redirect()->back()->with('success', 'Les informations de lois ont été mises à jour avec succès.');
}
public function destroy($id)
{
    $loi = Lois::findOrFail($id);
    $loi->delete();

    return redirect()->back()->with('success', 'Loi supprimé avec succès.');
}
}
