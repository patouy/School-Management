<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Inscription;
use Auth;

class InscriptionController extends Controller
{
    public function search() {
        // use the $id variable to query the db for a record
        return view('inscriptions.search');
      }

    public function show($id) {
    // use the $id variable to query the db for a record
    
    $eleve = Eleve::findOrFail($id);
    $classes = Classe::orderBy('classe', 'ASC')->get();
    
        return view('inscriptions.show', [ 'eleve' => $eleve,'classes' => $classes]);
        //return $eleve;
    
      }

      public function store() {

        $inscription = new Inscription();
    
        $inscription->eleve_id = request('ideleve');
        $inscription->nom = request('nom');
        $inscription->prenom = request('prenom');
        $inscription->datenaissance = request('datenaissance');
        $inscription->classe = request('classe');
        $inscription->fraisdu = request('fraisdu');
        $inscription->fraispayer = 0;
        $inscription->anneescolaire = request('anneescolaire');
        $inscription->sex = request('sex');        
        $inscription->redoublant = request('redoublant');
        $inscription->email = Auth::User()->email;
        
        $inscription->save();

        
        //return (request('toppings'));
        //error_log($pizza);
    
        // use the $id variable to query the db for a record
        //error_log(request('name'));
        //error_log(request('type'));
        //error_log(request('base'));
        
        return redirect('/home')->with('mssg','Entregistrer Inscription avec succes');
        
        }

        public function destroy($id) {

          $inscription = Inscription::findOrFail($id);

          

          $inscription->delete();
      
          return view('recherches.recherche');
          //return $inscription;
          
      
        }
    

}
