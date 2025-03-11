<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Facture extends Model
{
    use HasFactory;
    protected $fillable = [ 'fraispayer' ];

    public $sortable = ['id', 'inscription_id', 'fraispayer','created_at', 'updated_at'];
    use Sortable;
    protected $dates = ['created_at'];

    public function inscription()
    {
        return $this->hasOne(Inscription::class,'id');
    }
}
