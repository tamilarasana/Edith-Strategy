<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
 
use App\Models\Basket;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;



class BasketExport implements FromCollection,WithHeadings
{

    use Exportable;

    protected $fromDate;
    protected $toDate;

    function __construct($fromDate,$toDate, $userId) {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->userId = $userId;
}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $query = Basket::select("*")->whereBetween('created_at', [$this->fromDate, $this->toDate])->get();
        $query = Basket::where([['created_at', '>=', [$this->fromDate.' 00:00:00']],['created_at', '<=',  [$this->toDate.' 23:59:59']]])->where('user_id', $this->userId)->get();


        // where('created_at','>=', $this->fromDate)
        // ->where('created_at', '<=',$this->toDate)->get();
        return $query;   
    }

    public function headings():array{
        return[
            'Id',
            'User Id',
            'Basket Name',
            'segments',
            'webhook_basket_id ',
            'scheduled_basket_id',
            'webhook_id',
            'sq_target',
            'sq_loss',
            'scheduled_exec',
            'scheduled_sqoff',
            'recorring',
            'weekDays',
            'strategy',
            'qty',
            'Pnl',
            'Pnl_perc',
            'init_target',
            'current_target',
            'prev_current_target',
            'target_strike',
            'max_target_achived',
            'stop_loss',
            'status',
            'fb_token',
            'intra_mis',
            'is_delete',
            'created_at',
            'created_by',
            'updated_at'
        ];
    } 
    // public function collection()
    // {
    //     return Basket::all();
    // }

    
}