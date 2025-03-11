<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use Auth;

class EleveController extends Controller
{
    public function index() {
        // get data from a database
        //$pizzas = Pizza::all();
        //$pizzas = Pizza::orderBy('name','desc')->get();
        $geteleve = strtoupper(request('nom'));
        $eleves = Eleve::where('nom','like',''.$geteleve.'%' )->get();
        //$pizzas = Pizza::latest()->get();
        return view('inscriptions.search', [
          'eleves' => $eleves,
        ]);
      }
    public function create() {
        // use the $id variable to query the db for a record
        return view('eleves.create');
      }

    public function findname() {
    
        $geteleve = strtoupper(request('nom'));
        $eleves = Eleve::where('Nom','like',''.$geteleve.'%' )->get();
        return view('inscriptions.search', [
            'eleves' => $eleves,
          ]);
    }

    public function show($id) {
        // use the $id variable to query the db for a record
        $eleve = Eleve::findOrFail($id);
        return view('inscription.show.show', [ 'eleve' => $eleve,]);
      }
    public function store() {

    $eleve = new Eleve();

    $eleve->nom = strtoupper(request('nom'));
    $eleve->prenom = strtoupper(request('prenom'));
    $eleve->datenaissance = request('datenaissance');
    $eleve->lieunaissance = strtoupper(request('lieunaissance'));
    $eleve->adresse = request('telephone1');
    $eleve->telephone = request('telephone2');
    $eleve->ancienecole = strtoupper(request('ancienecole'));
    $eleve->sex = strtoupper(request('sexe'));
    $eleve->email = Auth::User()->email;

    $eleve->save();
    //return (request('toppings'));
    //error_log($pizza);

    // use the $id variable to query the db for a record
    //error_log(request('name'));
    //error_log(request('type'));
    //error_log(request('base'));
    return redirect('/home')->with('mssg','Entregistrer avec succes');
    }
}
