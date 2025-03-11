<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use Auth;


class RechercheController extends Controller
{
    /**
     * Display the products dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
       
        $classes = Classe::orderBy('classe', 'ASC')->get();
        
       
        
          return view('recherches.recherche',compact('classes'));
        
        
            
        
    }
    

    public function show()
    {
        $classesearch = request('classe');
        $anneescolaire = request('anneescolaire');
        $nom = request('nom');
        $classes = Classe::orderBy('classe', 'ASC')->get();
                
        $inscriptions = '';
       if($nom != "" && $classesearch == "" )
       {
        $inscriptions = Inscription::select('inscription.id','inscription.eleve_id','inscription.FraisDu','inscription.created_at','inscription.AnneeScolaire','inscription.Classe', 'inscription.Nom','inscription.Prenom','inscription.FraisPayer','eleve.DateNaissance','eleve.LieuNaissance')           
         
       
        ->join('eleve', 'inscription.eleve_id', '=', 'eleve.id')                   
        ->where('inscription.Nom', 'like',''.$nom.'%')
        ->Where('inscription.AnneeScolaire', '=', $anneescolaire)      
                   
        ->orderByRaw('inscription.Nom ASC')->sortable()->paginate(200); 
       }
       if($nom == "" && $classesearch != "" )
       {
        $inscriptions = Inscription::select('inscription.id','inscription.eleve_id','inscription.FraisDu','inscription.created_at','inscription.AnneeScolaire','inscription.Classe', 'inscription.Nom','inscription.Prenom','inscription.FraisPayer','eleve.DateNaissance','eleve.LieuNaissance')           
         
       
        ->join('eleve', 'inscription.eleve_id', '=', 'eleve.id')                  
        ->where('inscription.Classe', '=',$classesearch)
        ->Where('inscription.AnneeScolaire', '=', $anneescolaire)      
                   
        ->orderByRaw('inscription.Nom ASC')->sortable()->paginate(200); 
       }
       if($nom == "" && $classesearch == "" )
       {
        $inscriptions = Inscription::select('inscription.id','inscription.eleve_id','inscription.FraisDu','inscription.created_at','inscription.AnneeScolaire','inscription.Classe', 'inscription.Nom','inscription.Prenom','inscription.FraisPayer','eleve.DateNaissance','eleve.LieuNaissance')           
                                
        ->join('eleve', 'inscription.eleve_id', '=', 'eleve.id')
        ->Where('inscription.AnneeScolaire', '=', $anneescolaire)      
                   
        ->orderByRaw('inscription.Classe ASC')->sortable()->paginate(3000); 
       }
        //$inscriptions = Inscription::with(['eleve','classe']);
       if($nom != "" && $classesearch != "" ){
        $inscriptions = Inscription::select('inscription.id','inscription.eleve_id','inscription.FraisDu','inscription.created_at','inscription.AnneeScolaire','inscription.Classe', 'inscription.Nom','inscription.Prenom','inscription.FraisPayer')           
             
        
                              
             ->where('inscription.Nom', 'like',''.$nom.'%')             
             ->where('inscription.Classe', '=', $classesearch)             
             ->Where('inscription.AnneeScolaire', '=', $anneescolaire)
              ->orderByRaw('inscription.Nom ASC')->sortable()->paginate(200);  
            }

        //$getannee = str_replace('-', '/',''.$anneescolaire.'');
         //$classes = Classe::all();
         
 
            // $factures = DB::table('factures')        
         //->where('inscription_id', '=', $inscriptions[0]->id)->get();
 
         //$fraispayer = $factures->sum('fraispayer');
 
         //$reste = $inscriptions[0]->fraisdu - $fraispayer;
         $search = 0;
          return view('recherches.recherche',compact('inscriptions','classes','search'));
         //return $inscriptions;
         
    }

    public function filter()
    {
       $datedu = request('datedu');
       $dateau = request('dateau');
       
       $classes = Classe::orderBy('classe', 'ASC')->get();
        $inscriptions = Inscription::select('inscription.id','inscription.FraisDu','inscription.created_at','inscription.AnneeScolaire','inscription.Classe', 'inscription.Nom','inscription.Prenom',DB::raw('SUM(factures.PensionPaye) As fraispayer'))           
         
        ->join('factures', 'inscription.eleve_id', '=', 'factures.idold')                     
        ->whereBetween('factures.created_at', [$datedu.' 00:00:00', $dateau.' 23:00:00'])      
        ->groupBy('inscription.id','inscription.FraisDu','inscription.created_at','inscription.AnneeScolaire','inscription.Classe','inscription.Nom','inscription.Prenom')
        ->orderByRaw('factures.created_at DESC')->sortable()->paginate(200);

        $totalfraispayer = $inscriptions->sum('fraispayer');

        //$reste = $inscriptions[0]->fraisdu - $fraispayer;
        
        return view('recherches.recherche',compact('inscriptions','totalfraispayer','classes'));
         //return $inscriptions;
        
         
    }
}
