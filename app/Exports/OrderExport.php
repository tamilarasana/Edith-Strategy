<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
 
use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;



class OrderExport implements FromCollection,WithHeadings
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
        $query = Order::where([['created_at', '>=', [$this->fromDate.' 00:00:00']],['created_at', '<=',  [$this->toDate.' 23:59:59']]])->where('user_id', $this->userId)->get();
        return $query;   
    }

    public function headings():array{
        return[
            'Id',
            'User Id',
            'Basket Id',
            'token_name',
            'token_id ',
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
            'exit_price',
            'total_inv',
            'pnl_perc',
            'order_status_code',
            'is_delete',
            'created_at',
            'updated_at'
        ];
    } 
    // public function collection()
    // {
    //     return Basket::all();
    // }

    
}