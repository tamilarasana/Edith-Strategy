<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'instrument_token',
        'exchange_token',
        'tradingsymbol',
        'name',
        'last_price',
        'expiry',
        'strike',
        'tick_size',
        'lot_size',
        'instrument_type',
        'segment',
        'exchange',
        'strike_expiry',
    ];
}