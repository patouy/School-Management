<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Eleve extends Model
{
    use HasFactory;
    use Sortable;
    protected $table = 'eleve';
    protected $fillable = [ 'nom', 'prenom' ];

    public $sortable = ['id', 'nom', 'prenom','created_at', 'updated_at'];

    public function inscription()
    {
        return $this->hasMany(Inscription::class, 'eleve_id');
    }
    
}
