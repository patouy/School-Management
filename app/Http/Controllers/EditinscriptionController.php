<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Inscription;
use Auth;

class EditinscriptionController extends Controller
{

    public function search() {
        // use the $id variable to query the db for a record
        if(Auth::User()->email == "patou_y@yahoo.com") { 
        return view('editinscriptions.search');
        }
      }
      
    public function findname() {
        // use the $id variable to query the db for a record
        $anneescolaire = strtoupper(request('anneescolaire'));
        $getannee = str_replace('/', '-',''.$anneescolaire.'');
        $geteleve = strtoupper(request('nom'));
        $eleves = Inscription::select('inscription.*')
        ->where('Nom','like',''.$geteleve.'%' )
        ->where('AnneeScolaire','=',$anneescolaire )->get();
        if(Auth::User()->email == "patou_y@yahoo.com" || Auth::User()->email == "chantal@iaes.com") { 
        return view('editinscriptions.search', [
            'eleves' => $eleves,
            'getannee' => $getannee
          ]);
          
        }
    }

    public function show($id,$anneescolaire) {
        // use the $id variable to query the db for a record
        
        $getannee = str_replace('-', '/',''.$anneescolaire.'');

        $inscriptions = Inscription::select('inscription.*')        
        ->where('id','=',$id )->get();

       
        $classes = Classe::orderBy('classe', 'ASC')->get();

            return view('editinscriptions.show', [ 'inscriptions' => $inscriptions,
                                                    
                                                    'classes' => $classes
                                                    
                                                    
                                                    ]);
            
            
          
        }

          public function store() {

            //$eleve = new Eleve();
            $inscription = Inscription::find(request('inscriptionid'));
        
            $inscription->classe = strtoupper(request('idclasse'));
            $inscription->fraisdu = strtoupper(request('fraisdu'));
            
        
            $inscription->save();
            
             return redirect('/edit')->with('mssg','Entregistrer avec succes');
            
            }

}
