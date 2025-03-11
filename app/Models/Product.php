<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use Sortable;

    protected $fillable = [ 'name', 'price','details' ];

    public $sortable = ['id', 'name', 'price','details', 'created_at', 'updated_at'];
}