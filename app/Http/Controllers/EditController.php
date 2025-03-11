<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use Illuminate\Support\Facades\DB;

class EditController extends Controller
{
    public function index()
    {
        return view('edit');
    }

    public function search() {
        // use the $id variable to query the db for a record
        return view('editeleves.search');
      }

    public function findname() {
    
        $geteleve = strtoupper(request('nom'));
        $eleves = Eleve::where('nom','like',''.$geteleve.'%' )->get();
        return view('editeleves.search', [
            'eleves' => $eleves,
          ]);
    }

    public function show($id) {
        // use the $id variable to query the db for a record
        
        $eleves = Eleve::findOrFail($id);        
    
            return view('editeleves.show', ['eleves' => $eleves]);
            //return $eleves;
          }

          public function store() {

            //$eleve = new Eleve();
            $eleve = Eleve::find(request('id'));
        
            $eleve->nom = strtoupper(request('nom'));
            $eleve->prenom = strtoupper(request('prenom'));
            $eleve->datenaissance = request('datenaissance');
            $eleve->lieunaissance = strtoupper(request('lieunaissance'));
            $eleve->adresse = request('telephone1');
            $eleve->telephone = request('telephone2');
            $eleve->ancienecole = strtoupper(request('ancienecole'));
            $eleve->sex = strtoupper(request('sexe'));
        
            $eleve->save();

            DB::update('update inscription set Nom = ?,Prenom=?,DateNaissance=? where eleve_id =?',[strtoupper(request('nom')),strtoupper(request('prenom')),strtoupper(request('datenaissance')),request('id')]);

          
            return redirect('/edit')->with('mssg','Entregistrer avec succes');
            //return $inscriptions;
            }

            public function delete($id) {
                
                DB::table('eleves')->where('id', '=', $id)->delete();       
            
                 return redirect('/edit')->with('mssg','Entregistrer avec succes');
                 
                  }
}
