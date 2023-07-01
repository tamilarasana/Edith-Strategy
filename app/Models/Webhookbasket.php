<?php

namespace App\Models;

use App\Models\User;
use App\Models\Webhook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Webhookbasket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'webhook_id',
        'basket_name',
        'segments',
        'indices',
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
        'intra_mis',
        'Pnl_perc',
        'status',  
        'sq_signal'    
    ];

    public function webhook(){
        return $this->belongsTo(Webhook::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }


}
