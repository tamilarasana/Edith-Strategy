<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'ltp',
        'expiry_prefix',
    ];
}
