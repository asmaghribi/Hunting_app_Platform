<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Notification;

class ListnotifController extends Controller
{
    public function listnotif(){
        $notif = $this->getnotifPaginted(10);
        return view('Admin.Components.listnotif',compact('notif'));
    }
    public function getnotifPaginted($paginate)
     {
    $notif = Notification::paginate($paginate);
    return $notif;
     }

     public function update(Request $request, $id)
     {
           $notif = Notification::findOrFail($id);

         // Mettez à jour les données de l'utilisateur en fonction des données du formulaire
          $notif->update([
             'title' => $request->title,
             'content' => $request->content,

             // Ajoutez d'autres champs à mettre à jour ici
         ]);

         // Rediriger vers une page appropriée après la mise à jour
         return redirect()->back()->with('success', 'Les informations de notif ont été mises à jour avec succès.');
     }
     public function destroy($id)
     {
         $notif = Notification::findOrFail($id);
         $notif->delete();

         return redirect()->back()->with('success', 'notif supprimé avec succès.');
     }



     }

