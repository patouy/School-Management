<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Facture;
use App\Models\Autresfrais;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Auth;

class AutresfraisController extends Controller
{
    public function home()
    {
        //return view('autresfrais.index');
        return view('autresfrais.home');
    }
    public function index()
    {
        return view('autresfrais.index');
        
    }
    public function findname() {
    
        $geteleve = strtoupper(request('nom'));
        //str_replace('-', ' ', $series); 
        $getanneetrue = request('anneescolaire');
        $getannee = str_replace('/', '-',request('anneescolaire'));
        $eleves = Inscription::where([
          ['nom','like',''.$geteleve.'%' ],
          ['anneescolaire', '=', ''.$getanneetrue.'']
          ])->get();  

           return view('autresfrais.index', [
            'eleves' => $eleves,
            'getannee' => $getannee

          ]);
        
    }
    public function show($id,$anneescolaire) {
        // use the $id variable to query the db for a record
        
        $getannee = str_replace('-', '/',''.$anneescolaire.'');

        $inscriptions = DB::table('inscription')        
        ->where([
          ['inscription.id', '=', ''.$id.'']  
          ])->get();        


        $fraispayer = $inscriptions[0]->FraisPayer;

        $reste = $inscriptions[0]->FraisDu - $fraispayer;
        
                      
            return view('autresfrais.show', [ 'inscriptions' => $inscriptions,'reste' =>$reste]);
            //return $getannee;
        
            
    }
    public function store() {
        $inscriptions = Inscription::find(request('inscriptionid'));            

        
        $totalpayer =  request('fraispayer');          
       
      
        
            $autresfrais = new Autresfrais();            
            $autresfrais->idold = $inscriptions->eleve_id; 
            $autresfrais->nom = request('nom'); 
            $autresfrais->prenom = request('prenom'); 
            $autresfrais->classe = request('classe');            
            $autresfrais->fraispayer = request('fraispayer');
            $autresfrais->annee = request('anneescolaire');
            $autresfrais->email = Auth::User()->email;          
            
            $autresfrais->save();


                 return redirect('/autresfrais')->with('mssg','Entregistrer avec succes');
            
                        
          
        }
        public function printsearch() {
            $datenow = date('Y-m-d');
    $year = date('Y', strtotime($datenow));
    $month = date('m', strtotime($datenow));

    if($month >= 7){
    $getannee = (string)($year).'/'.(string)($year+1);
  }
  else{
    $getannee = (string)($year-1).'/'.(string)($year);
  }
    
    $autresfrais = Autresfrais::where([
      
      ['Annee', '=', $getannee]
      ])
      ->orderByRaw('created_at DESC')->get(); 

      $getannee = str_replace('/', '-',$getannee);
       return view('autresfrais.printsearch', [
        
        'autresfrais' => $autresfrais,
        'getannee' => $getannee

      ]);
      
     }

     public function autresfraisprint($id) {
        // use the $id variable to query the db for a record
        
        //$getannee = str_replace('-', '/',''.$anneescolaire.'');
        
      
        $autresfrais = Autresfrais::FindOrFail($id);
        $inscriptions = DB::table('inscription')        
          ->where([
            ['inscription.eleve_id', '=', ''.$autresfrais->idold.''],
            ['inscription.AnneeScolaire', '=', ''.$autresfrais->annee.'']  
            ])->get(); 
        
       
      $eleves = $inscriptions[0]->eleve_id;
      
      
        //$fraispayer = $factures->sum('fraispayer');
      
        //$reste = $inscriptions[0]->fraisdu - $fraispayer;
        
            return view('autresfrais.autresfraisprint', [  'eleves' =>$eleves,                                     
                                          'autresfrais' =>$autresfrais 
                                          
                                          
  
                                          ]);
            //return $eleves;
            
          }

          public function showsearch() {
            // use the $id variable to query the db for a record
            $datedu = request('datedu');
            $dateau = request('dateau');
          
            $datenow = date('Y-m-d');
            $year = date('Y', strtotime($datenow));
            $month = date('m', strtotime($datenow));
          
            if($month >= 7){
            $getannee = (string)($year).'/'.(string)($year+1);
          }
          else{
            $getannee = (string)($year-1).'/'.(string)($year);
          }
            
            $autresfrais = Autresfrais::whereBetween('autresfrais.created_at', [$datedu.' 00:00:00', $dateau.' 23:00:00'])
            ->orderByRaw('autresfrais.created_at DESC')->get(); 
          
            $totalfraispayer = $autresfrais->sum('fraispayer');
            
            $getannee = str_replace('/', '-',$getannee);
          
            // Calculs pour caisse
            
          
            return view('autresfrais.printsearch', [
              'autresfrais' => $autresfrais,
              'getannee' => $getannee,
              'totalfraispayer' => $totalfraispayer
              
          
            ]);
          
              }
              public function showsearchname() {
    
                $geteleve = strtoupper(request('nom'));
                //str_replace('-', ' ', $series); 
                $getannee = str_replace('/', '-',request('anneescolaire'));
                $getanneetrue = request('anneescolaire');
                $autresfrais = Autresfrais::where([
                  ['nom','like',''.$geteleve.'%' ],
                  ['annee', '=', ''.$getanneetrue.'']
                  ])
                  ->orderByRaw('created_at DESC')->get(); 
            
                return view('autresfrais.printsearch', [
                    'autresfrais' => $autresfrais,
                    'getannee' => $getannee
            
                  ]);
            }
            public function destroy($id) {

                $autresfrais = Autresfrais::findOrFail($id);
  
                
                 
  
                $autresfrais->delete();
            
                return redirect('/autresfrais')->with('mssg','Suprimmer avec succes');
                
            
              }
            

}
