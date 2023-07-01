<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zero_token',
        'api_key',
        'access_token',
        'user_name',
        'password',
        't_otp',
        'type',
        'remarks',
        'status',
        ];


     public function users(){
        return $this->belongsTo(User::class);
     }   
       
}
