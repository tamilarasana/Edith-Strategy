<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasketCat extends Model
{
    use HasFactory;

    protected $table = 'basket_cats';

    protected $fillable =['basket_name' , 'description'];

    public $timestamps = false;
}
