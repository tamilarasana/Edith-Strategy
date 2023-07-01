<?php

namespace App\Models;


use App\Models\Basket;
use App\Models\Webhookbasket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Webhook extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'hook_name',
        'webhookbasket_id',
        'order_details',
        'token_id',
        'trading_type',
        'api_source',
        'basket_id',
        'recurring',
        'recurring_status',    
        'qty',
        'order_date_time',
        'status', 
        'post_api',
        'alert_payload',
        'sq_signal'
    ];


    public function baskets(){
        return $this->hasMany(Basket::class, 'basket_id');
    }

    public function webbaskets(){
        return $this->hasOne(Webhookbasket::class, 'webhookbasket_id');
    }
    
}

