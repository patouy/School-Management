<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Classe;
use Auth;
use File;

class CarteController extends Controller
{
    public function cartes()
    {
        $classes = Classe::orderBy('classe', 'ASC')->get();

        return view('cartes.index', [ 'classes' => $classes]);
    }
    public function printall()
    {
        $datenow = date('Y-m-d');
                                    // $year = 
                                    $year = date('Y', strtotime($datenow));
                                    $month = date('m', strtotime($datenow));
                                    $realdate = '';
                                        
                                        if($month >= 7)
                                        {                                
                                            $realdate = ''.($year).'/'.($year +1).'';
                                    }
                                else{
                                        $realdate = ''.($year -1).'/'.($year).'';
                                     }
        $classe = request('classe');
        
        
        $eleves= Inscription::select('inscription.*')
        ->where('Classe', '=',$classe)
        ->where('AnneeScolaire', '=',$realdate)->get();

        if(Auth::User()->user_type == 0)
        {
        return view('cartes.printall', [ 'eleves' => $eleves]);
        //return $eleves;
        }               
        
    }

    public function find()
    {
        $datenow = date('Y-m-d');
                                    // $year = 
                                    $year = date('Y', strtotime($datenow));
                                    $month = date('m', strtotime($datenow));
                                    $realdate = '';
                                        
                                        if($month >= 7)
                                        {                                
                                            $realdate = ''.($year).'/'.($year +1).'';
                                    }
                                else{
                                        $realdate = ''.($year -1).'/'.($year).'';
                                     }
        $nom = request('nom');
        
        
        $eleves= Inscription::select('inscription.*')
        ->where('Nom', 'like',''.$nom.'%')
        ->where('AnneeScolaire', '=',$realdate)->get();

        if(Auth::User()->user_type == 0)
        {
        return view('cartes.index', [ 'eleves' => $eleves]);
        
        }               
    }

    public function scolaire($id,$Nom,$Classe)
    {
        $datenow = date('Y-m-d');
                                    // $year = 
                                    $year = date('Y', strtotime($datenow));
                                    $month = date('m', strtotime($datenow));
                                    $realdate = '';
                                        
                                        if($month >= 7)
                                        {                                
                                            $realdate = ''.($year).'/'.($year +1).'';
                                    }
                                else{
                                        $realdate = ''.($year -1).'/'.($year).'';
                                     }
        
        $eleves= Inscription::select('inscription.*')
        ->where('eleve_id', '=',''.$id.'%')
        ->where('AnneeScolaire', '=',$realdate)->get();
        $oneeleve= Eleve::select('eleve.*')
        ->where('id', '=',''.$id.'%')->get();

        //return view('cartes.scolaire',[ 'id' => $id, 'Nom' => $Nom, 'Classe' => $Classe]);
        return view('cartes.scolaire', [ 'eleves' => $eleves, 'oneeleve' => $oneeleve]);
        //return $eleves;
    }

}
