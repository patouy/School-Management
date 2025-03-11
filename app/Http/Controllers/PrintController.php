<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Facture;
use App\Models\Inscription;
use App\Models\Caisse;
use Illuminate\Support\Facades\DB;
use Auth;

class PrintController extends Controller
{
  public function search() {
    $datenow = date('Y-m-d');
    $year = date('Y', strtotime($datenow));
    $month = date('m', strtotime($datenow));

    if($month >= 7){
    $getannee = (string)($year).'/'.(string)($year+1);
  }
  else{
    $getannee = (string)($year-1).'/'.(string)($year);
  }
    
    $factures = Facture::where([
      
      ['Annee', '=', $getannee]
      ])
      ->orderByRaw('created_at DESC')->get(); 

      $getannee = str_replace('/', '-',$getannee);
       return view('print.search', [
        
        'factures' => $factures,
        'getannee' => $getannee

      ]);
      
  }

  public function findname() {
    
    $geteleve = strtoupper(request('nom'));
    //str_replace('-', ' ', $series); 
    $getannee = str_replace('/', '-',request('anneescolaire'));
    $getanneetrue = request('anneescolaire');
    $factures = Facture::where([
      ['nom','like',''.$geteleve.'%' ],
      ['Annee', '=', ''.$getanneetrue.'']
      ])
      ->orderByRaw('created_at DESC')->get(); 

    return view('print.search', [
        'factures' => $factures,
        'getannee' => $getannee

      ]);
}

public function show() {
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
  
  $factures = Facture::whereBetween('factures.created_at', [$datedu.' 00:00:00', $dateau.' 23:00:00'])
  ->orderByRaw('factures.created_at DESC')->get(); 

  $totalfraispayer = $factures->sum('PensionPaye');
  
  $getannee = str_replace('/', '-',$getannee);

  // Calculs pour caisse
  $caisses = Caisse::select("*")
        ->where('caisse.statut', '=', 1)
        ->whereBetween('caisse.updated_at', [$datedu.' 00:00:00', $dateau.' 23:00:00'])      
        ->orderByDesc('caisse.updated_at')->get();
  
        $totalfraispayercaisse = $caisses->sum('Somme');

  return view('print.search', [
    'factures' => $factures,
    'getannee' => $getannee,
    'totalfraispayer' => $totalfraispayer,
    'totalfraispayercaisse' => $totalfraispayercaisse

  ]);

    }

    public function print($id) {
      // use the $id variable to query the db for a record
      
      //$getannee = str_replace('-', '/',''.$anneescolaire.'');
      
    
      $facture = Facture::FindOrFail($id);
      $inscriptions = DB::table('inscription')        
        ->where([
          ['inscription.eleve_id', '=', ''.$facture->idold.''],
          ['inscription.AnneeScolaire', '=', ''.$facture->Annee.'']  
          ])->get(); 
      
     
    $eleves = $inscriptions[0]->eleve_id;
    $reste = $inscriptions[0]->FraisDu - $inscriptions[0]->FraisPayer;
    
      //$fraispayer = $factures->sum('fraispayer');
    
      //$reste = $inscriptions[0]->fraisdu - $fraispayer;
      
          return view('print.print', [  'eleves' =>$eleves,                                     
                                        'facture' =>$facture, 
                                        
                                        'reste' =>$reste

                                        ]);
          //return $eleves;
          
        }
  }



