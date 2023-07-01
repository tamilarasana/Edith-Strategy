<?php

namespace App\Models;

use App\Models\User;
use App\Models\Basket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduledBasket extends Model
{
    use HasFactory;

    protected $table = 'scheduled_baskets';


    protected $fillable = [
        'user_id',
        'scbasket_name',
        'segments',
        'indices',
        'sq_target',
        'stop_loss',
        'target_strike',
        'init_target',
        'scheduled_exec',
        'scheduled_sqoff',
        'recurring', #default off (on/Off)
        'isScheduled', #default off (on/Off)
        'orders', # to be in JSON Storage
        'strick_qty',
        'intra_mis',
        'status',      
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function baskets(){
        return $this->hasMany(Basket::class);
    }


}
