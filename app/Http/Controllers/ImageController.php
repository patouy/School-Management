<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Models\Inscription;
use Image;
use Auth;
use File;

class ImageController extends Controller
{
    public function eleves()
    {
        return view('eleves.index');
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

        
        return view('eleves.index', [ 'eleves' => $eleves]);
        
                      
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resizeImage($id,$Prenom,$Nom,$Classe)
    {
        return view('eleves.resizeImage',[ 'id' => $id, 'Prenom' => $Prenom, 'Nom' => $Nom, 'Classe' => $Classe]);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resizeImagePost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'Nom' => 'required',
            'Classe' => 'required',
            //'image' => 'required|image|mimes:jpeg,jpg|max:2048',
            'image' => 'required|image|mimes:jpeg,jpg',
        ]);
  
        $image = $request->file('image');
        
        //$input['imagename'] = $image->getClientOriginalName(); 
        $input['Classe'] = $request['Classe'];
        $input['imagename'] = $request['title'].'_'.$request['Nom'].'.'.$image->extension();

        //$path = public_path('public/pdf/'.$resultsevalsgroupe1[0]->Class.'');

        
     
        //$destinationPath = public_path('/thumbnail');
        $destinationPath = public_path('/thumbnail/'.strtoupper($request['Classe']).'');
        if(!File::isDirectory($destinationPath)){
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        $img = Image::make($image->path());         
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);

        $img = Image::make($image->path());
        $img->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
        }); 
              
       //$img->text('This is a example ', 120, 100);  
       $img->text('', 320, 60, function($font) {  
        $font->file(public_path('font/Roboto-Bold.ttf')); 
       // $font->file('font/Roboto-Bold.ttf');   
        $font->size(50);  
          $font->color('#000000');  
          $font->align('center');  
          $font->valign('bottom');  
          $font->angle(0);  
    });  
    $img->text(''.$request['Nom'].' '.$request['Prenom'].'', 10, 500, function($font) {  
        $font->file(public_path('font/Roboto-Bold.ttf')); 
       // $font->file('font/Roboto-Bold.ttf');   
        $font->size(30);  
          $font->color('#000000');  
          //$font->align('center');  
          $font->valign('bottom');  
          $font->angle(0);  
    });
    $text = "";
    $date = '15/11/'.date("Y").''; 
    switch($request['Classe']) {
        case('TD'):
                $text = "BAD CR 4470";                  
                break;
        case('TA4ALL'):
                $text = "BAA4 ALL CR 4470";                
                break;
        case('TA4ESP'):
                $text = "BAA4 ESP CR 4470";                 
                break;
        case('TC'):
                $text = "BAC CR 4470";                
                break;
        case('TCG'):
                $text = "BACG CR 4470";                
                break;
        case('TACC'):
                $text = "BAACC CR 4470";                
                break; 
        case('TESF'):
                $text = "BA ESF CR 4470";                
                break;
        case('TF3'):
                $text = "BTF3 F3 CR 4171";                 
                break; 
        case('TIH'):
                $text = "BT IH CR 4470";                 
                break;
        case('TMVT'):
                $text = "BT MVT CR 4470";                 
                break; 
        case('TTI'):
                $text = "BATI CR 4470";                 
                break;
        case('1ereACC'):
                $text = "PBACC CR  4470";
                break;
        case('1EREESF'):
                $text = "PT ESF CR 4470";
                break;
        case('1ereIH'):
                $text = "PT IH CR 4470";
                break;
        case('1ereD'):
                $text = "PBD CR 4470";
                break;
        case('1EREF3'):
                $text = "PTF3 CR 4171";
                break;
        case('1ereMVT'):
                $text = "PTMVT CR 4470";
                break;
        case('1ereCG'):
                $text = "PBCG CR 4470";
                break;
        case('1ereC'):
                $text = "PBC CR 4470";
                break;
        case('1ereTI'):
                $text = "PBTI CR 4470";
                break;
        case('1ereA4ALL'):
                $text = "PBA4 ALL CR 4470";
                break;
        case('1ereA4ESP'):
                $text = "PBA4 ESP CR 4470";
                break;               
            
        default:
            $text = $request['Classe'];
            $date = ""; 
        }
    /*if($request['Classe'] == "TD")
    $text = "BAD CR 4470";
    if($request['Classe'] == "TA4ALL")
    $text = "BAA4 ALL  CR 4470";
    if($request['Classe'] == "TA4ESP")
    $text = "BAA4 ESP CR 4470";
    if($request['Classe'] == "TC")
    $text = "BAC CR 4470";
    if($request['Classe'] == "TCG")
    $text = "BACG CR 4470";
    if($request['Classe'] == "TACC")
    $text = "BAACC CR 4470";
    if($request['Classe'] == "TESF")
    $text = "BA ESF CR 4470";
    if($request['Classe'] == "TF3")
    $text = "BTF3 F3 CR 4470";
    if($request['Classe'] == "TIH")
    $text = "BT IH CR 4470";
    if($request['Classe'] == "TMVT")
    $text = "BT MVT CR 4470";
    if($request['Classe'] == "TTI")
    $text = "BATI CR 4470";
    if($request['Classe'] == "1ereACC")
    $text = "PBACC CR  4470";
    if($request['Classe'] == "1ereESF")
    $text = "PT ESF CR 4470";
    if($request['Classe'] == "1ereIH")
    $text = "PT IH CR 4470";
    if($request['Classe'] == "1ereD")
    $text = "PBD CR 4470";
    if($request['Classe'] == "1ereF3")
    $text = "PTF3 CR 4470";
    if($request['Classe'] == "1ereMVT")
    $text = "PTMVT CR 4470";
    if($request['Classe'] == "1ereCG")
    $text = "PBCG CR 4470";
    if($request['Classe'] == "1ereC")
    $text = "PBC CR 4470";
    if($request['Classe'] == "1ereTI")
    $text = "PBTI CR 4470";
    if($request['Classe'] == "1ereA4ALL")
    $text = "PBA4 ALL CR 4470";
    if($request['Classe'] == "1ereA4ESP")
    $text = "PBA4 ESP CR 4470";*/

    
    //$text = $request['Classe'];

    $img->text($date, 100, 50, function($font) {  
        $font->file(public_path('font/Roboto-Bold.ttf')); 
       // $font->file('font/Roboto-Bold.ttf');   
        $font->size(50);  
          $font->color('#000000');  
          //$font->align('center');  
          $font->valign('bottom');  
          $font->angle(0);  
    });
    
    $img->text($text, 100, 550, function($font) {  
        $font->file(public_path('font/Roboto-Bold.ttf')); 
       // $font->file('font/Roboto-Bold.ttf');   
        $font->size(50);  
          $font->color('#000000');  
          //$font->align('center');  
          $font->valign('bottom');  
          $font->angle(0);  
    });
    $destinationPath = public_path('/images/'.strtoupper($request['Classe']).'/name');
        if(!File::isDirectory($destinationPath)){
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        $img->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('images/'.strtoupper($request['Classe']).'/name/'.$request['title'].'_'.$request['Nom'].'.jpg'));   
       //$img->save(public_path('images/'.$request['Classe'].'/name/'.$request['title'].'_'.$request['Nom'].'.jpg')); 
       //$img->save('images/'.$request['title'].'_id_name.jpg');  
   
        $destinationPath = public_path('/images/'.strtoupper($request['Classe']).'');
        $img = Image::make($image->path());         
        $img->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);
        //$image->move($destinationPath, $input['imagename']);

       
        return back()
            ->with('success','Image Upload successful')
            ->with('imageName',$input['imagename']);
    }
}
