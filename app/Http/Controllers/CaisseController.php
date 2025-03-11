<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caisse;
use Auth;

class CaisseController extends Controller
{
    public function create() {
        // use the $id variable to query the db for a record
        return view('caisses.create');
      }

      public function show() {
        // use the $id variable to query the db for a record
        $caisses = Caisse::latest()->take(50)->get();
        return view('caisses.show', [ 'caisses' => $caisses,]);
      }

      public function update($id) {

        $caisse = Caisse::findOrFail($id); 
        $caisse->statut = 1;
        $caisse->save();

        $caisses = Caisse::latest()->take(10)->get();
        return view('caisses.show', [ 'caisses' => $caisses,]);

      }
      public function delete($id){
        $caisses = Caisse::findOrFail($id);

        return view('caisses.delete', compact('caisses'));
      }

      public function destroy($id) {

        $caisse = Caisse::findOrFail($id);      


        $caisse->delete();
    
        $caisses = Caisse::latest()->take(10)->get();
        return view('caisses.show', [ 'caisses' => $caisses,]);
        
    
      }

      public function filter()
    {
       $datedu = request('datedu');
       $dateau = request('dateau');
       $receveur = request('receveur');
       
       $totalfraispayer=0;
        if($receveur ==""){
          $caisses = Caisse::select("*")
          ->where('caisse.statut', '=', 1)
          ->whereBetween('caisse.updated_at', [$datedu.' 00:00:00', $dateau.' 23:00:00'])      
          ->orderByDesc('caisse.updated_at')->get();
          
          $totalfraispayer = $caisses->sum('Somme');
        }
        else{
          $caisses = Caisse::select("*")
          ->where('caisse.statut', '=', 1)
          ->where('caisse.receveur', '=', $receveur)
          ->whereBetween('caisse.updated_at', [$datedu.' 00:00:00', $dateau.' 23:00:00'])      
          ->orderByDesc('caisse.updated_at')->get();
          
          $totalfraispayer = $caisses->sum('Somme');
        }
        
        return view('caisses.show', [ 'caisses' => $caisses,'totalfraispayer' => $totalfraispayer]);
        
         
    }


      public function store() {

        $caisse = new Caisse();
    
        $caisse->Compte = strtoupper(request('compte'));
        $caisse->Somme = request('somme');
        $caisse->Libelle = strtoupper(request('libelle'));
        $caisse->receveur = strtoupper(request('receveur'));
        $caisse->statut = 0;
        $caisse->Username = Auth::User()->email;
        $caisse->save();
        //return (request('toppings'));
        //error_log($pizza);
    
        // use the $id variable to query the db for a record
        //error_log(request('name'));
        //error_log(request('type'));
        //error_log(request('base'));
        return redirect('/home')->with('mssg','Entregistrer avec succes');
        }
}
