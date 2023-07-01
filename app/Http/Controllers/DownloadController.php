<?php

namespace App\Http\Controllers;

use DateTimeZone;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Basket;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\BasketExport;
use App\Exports\OrderExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;



class DownloadController extends Controller
{
    public function basketExport(Request $request)
    {
        // DB::enableQueryLog();
        //     $fromDate = $request->from;
        //     $toDate = $request->to;
        //    return Excel::download(new BasketExport($fromDate,$toDate), 'basket.xlsx');


        date_default_timezone_set('Asia/kolkata');
        
        $user = Auth::User();
        $userId = $user->id;

        $fromDate = $request->from;
        $toDate = $request->to;
        
        // $basket_name = $request->basketNames;
        
        // if($basket_name != "All" and $basket_name != ""){
        //     $basket_name = $basket_name;
        // }else{
        //     $basket_name = "%%";
        // }
        
        
        $from_ist = $fromDate;
        $to_ist = $toDate;
        
        $from_ist = Carbon::createFromFormat('Y-m-d', $from_ist);
        $from_utc = $from_ist->setTimeZone(new DateTimeZone('UTC'));
        
        $to_ist = Carbon::createFromFormat('Y-m-d', $to_ist);
        $to_utc = $to_ist->setTimeZone(new DateTimeZone('UTC'));


        return Excel::download(new BasketExport($fromDate,$toDate,$userId), 'basket.xlsx');
    }
    
    
        public function orderExport(Request $request)
    {
        

        date_default_timezone_set('Asia/kolkata');
        
        $user = Auth::User();
        $userId = $user->id;

        $fromDate = $request->from;
        $toDate = $request->to;
        
        
        $from_ist = $fromDate;
        $to_ist = $toDate;
        
        $from_ist = Carbon::createFromFormat('Y-m-d', $from_ist);
        $from_utc = $from_ist->setTimeZone(new DateTimeZone('UTC'));
        
        $to_ist = Carbon::createFromFormat('Y-m-d', $to_ist);
        $to_utc = $to_ist->setTimeZone(new DateTimeZone('UTC'));


        return Excel::download(new OrderExport($fromDate,$toDate,$userId), 'order.xlsx');
    }
    
    
    
}