<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Inscription extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'inscription';

    protected $fillable = [ 'eleve_id', 'classe_id','fraisdu','anneescolaire' ];

    public $sortable = ['id', 'eleve_id', 'classe_id','fraisdu','anneescolaire', 'created_at', 'updated_at'];

    public function eleve()
    {
        return $this->hasOne(Eleve::class,'id');
    }
    public function classe()
    {

        return $this->hasOne(Classe::class, 'id');
    }

    public function facture()
    {
        return $this->hasMany(Facture::class, 'inscription_id');
    }
}
