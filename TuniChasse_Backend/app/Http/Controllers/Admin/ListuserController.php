<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class ListuserController extends Controller
{
    public function listusers(){
        $users = $this->getUserPaginted(10);

        return view('Admin.Components.listusers',compact('users'));
    }
    public function getUserPaginted($paginate)
     {
    $users = User::paginate($paginate);
    return $users;
     }

     public function getUserStats()
     {
         $users = User::select(
             DB::raw('YEAR(created_at) year'),
             DB::raw('MONTH(created_at) month'),
             DB::raw('COUNT(*) users')
         )
         ->groupBy('year', 'month')
         ->orderBy('year', 'asc')
         ->orderBy('month', 'asc')
         ->get();

         $labels = [];
         $new_visitors = [];
         $old_visitors = [];

         foreach ($users as $user) {
             $labels[] = $user->month . '/' . $user->year;

             if ($user->users == 1) {
                 $new_visitors[] = $user->users;
                 $old_visitors[] = 0;
             } else {
                 $new_visitors[] = 1;
                 $old_visitors[] = $user->users - 1;
             }
         }

         return response()->json([
             'labels' => $labels,
             'new_visitors' => $new_visitors,
             'old_visitors' => $old_visitors,
         ]);
     }





     public function update(Request $request, $id)
{
      $user = User::findOrFail($id);

    // Mettez à jour les données de l'utilisateur en fonction des données du formulaire
     $user->update([
        'Firstname' => $request->firstname,
        'Lastname' => $request->lastname,
        'Permis' => $request->Permis,
        'phone' => $request->phone,
        'email' => $request->email,
        'adresse' => $request->adresse,


        // Ajoutez d'autres champs à mettre à jour ici
    ]);

    // Rediriger vers une page appropriée après la mise à jour
    return redirect()->back()->with('success', 'Les informations de l\'utilisateur ont été mises à jour avec succès.');
}
public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
}

}
