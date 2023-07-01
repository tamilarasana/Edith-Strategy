<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Webhookbasket;
use App\Models\Webhook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Basket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'basket_name',
        'webhook_basket_id',
        'webhook_id',
        'sq_target',
        'stop_loss',
        'target_strike',
        'init_target',
        'current_target',
        'prev_current_target',
        'sq_loss',
        'scheduled_exec',
        'scheduled_sqoff',
        'recorring',
        'weekDays', 
        'strategy',
        'max_target_achived',
        'qty',
        'Pnl',
        'Pnl_perc',
        'intra_mis',
        'status',
        'segments',
        'min_target',
        'sq_signal'
    ];


    public function orders(){
       return $this->hasMany(Order::class);
    }

    public function user(){
       return $this->belongsTo(User::class);
    }
    
     public function hookbasket(){
       return $this->belongsTo(Webhookbasket::class, 'webhook_basket_id');
    }
    
     public function webHook(){
       return $this->belongsTo(Webhook::class, 'webhook_id');
    }
}

