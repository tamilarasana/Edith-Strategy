<?php

namespace App\Models;

use App\Models\Basket;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'basket_id',
        'token_name',
        'token_id',
        'leg_type',
        'qty',
        'status',
        'delta', 
        'theta',
        'vega',
        'gamma',
        'order_type',
        'order_id',
        'order_date_time',
        'order_avg_price',
        'ltp',
        'pnl',
        'pnl_perc',
        'total_inv',
        'is_delete',
        'order_status_code',
        'segments',
    ];



    public function basket(){
      return $this->belongsTo(Basket::class ,'basket_id');
    }

    public function user(){
      return $this->belongsTo(User::class ,'user_id');
    }



}