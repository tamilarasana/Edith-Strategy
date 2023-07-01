<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tick extends Model
{
    use HasFactory;

    protected $casts = [
        'properties' => 'array'
    ];

    protected $fillable = [
        'properties',
        'status',
    ];

    protected $guarded = [];
}
