<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Auth;

class FactureController extends Controller
{
    public function search() {
        // use the $id variable to query the db for a record
         return view('factures.search');
        
      }
      //Find name of student and send back with annescolaire
      public function findname() {
    
        $geteleve = strtoupper(request('nom'));
        //str_replace('-', ' ', $series); 
        $getanneetrue = request('anneescolaire');
        $getannee = str_replace('/', '-',request('anneescolaire'));
        $eleves = Inscription::where([
          ['nom','like',''.$geteleve.'%' ],
          ['anneescolaire', '=', ''.$getanneetrue.'']
          ])->get();  

            return view('factures.search', [
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
        
                      
           return view('factures.show', [ 'inscriptions' => $inscriptions,'reste' =>$reste]);
            //return $getannee;
        
            
          }

          public function store() {
            $inscriptions = Inscription::find(request('inscriptionid'));            

            
            $totalpayer = $inscriptions->FraisPayer + request('fraispayer');          
           
          
            if($totalpayer <= $inscriptions->FraisDu)

              {
                $facture = new Facture();            
                $facture->idold = $inscriptions->eleve_id; 
                $facture->nom = request('nom'); 
                $facture->prenom = request('prenom'); 
                $facture->classe = request('classe');
                $facture->pensiondu = $inscriptions->FraisDu;
                $facture->PensionPaye = request('fraispayer');
                $facture->annee = request('anneescolaire');
                $facture->email = Auth::User()->email;          
                
                $facture->save();

                $inscriptions->FraisPayer = $totalpayer;
                $inscriptions->save();


                //return view('print/{{$facture->id}}')->with('mssg','Entregistrer facture avec succes');
                  return redirect('/home')->with('mssg','Entregistrer facture avec succes');
                
              }              
              elseif($totalpayer > $inscriptions->FraisDu)
              {
                if(Auth::User()->email == "patou_y@yahoo.com") { 
                return back()->with('mssg','Cette Facture ne peut pas etre enrgistrer');             
                }
              }
            }

            public function impression() {
              
               return view('print.impressions');
              
              
            }

            public function destroy($id) {

              $facture = Facture::findOrFail($id);

              $inscriptions = DB::table('inscription')        
              ->where([
                ['inscription.eleve_id', '=', ''.$facture->idold.''],
                ['inscription.AnneeScolaire', '=', ''.$facture->Annee.'']  
                ])->get();

                
                $totalpayer = $inscriptions[0]->FraisPayer - $facture->PensionPaye;
                 
                
                $inscriptionsedit = Inscription::find($inscriptions[0]->id);
                $inscriptionsedit->FraisPayer = $totalpayer;
                $inscriptionsedit->save();  

              $facture->delete();
          
              return view('factures.search');
              
          
            }

}
