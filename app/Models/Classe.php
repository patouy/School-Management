<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Classe extends Model
{
    use HasFactory;
    use Sortable;
    protected $fillable = [ 'classe' ];

    public $sortable = ['id', 'classe','created_at', 'updated_at'];
    public function inscriptions() {
     //$this->hasMany('\App\Models\Inscription');
     return $this->hasMany(Inscription::class, 'classe_id');
  }
}
