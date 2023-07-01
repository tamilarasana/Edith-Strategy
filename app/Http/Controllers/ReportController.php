<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tick;
use App\Models\Order;
use App\Models\Basket;
use App\Models\Webhook;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use DateTimeZone;

use Illuminate\Support\Facades\Auth;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $basket_pnl = DB::table('baskets')->select('basket_name',DB::raw('sum(Pnl) as value'))->groupBy('basket_name')->take(20)->get();
        
        $basket_status = DB::table('baskets')->select('status',DB::raw('sum(Pnl) as value'))->groupBy('status')->get();
        
        $mean_pnl = DB::table('baskets')->select('basket_name',DB::raw('avg(Pnl) as avg_pnl, avg(max_target_achived) as avg_trend'))->groupBy('basket_name')->orderBy('avg_pnl', 'DESC')->take(10)->get();
        
        $date_wise = DB::table('baskets')->select(DB::raw('DATE(created_at) as date'),DB::raw('sum(Pnl) as value'))->groupBy(DB::raw('DATE(created_at)'))->get();
       
        return response()->json(['basket_pnl'=>$basket_pnl,'basket_status'=>$basket_status, 'mean_pnl'=>$mean_pnl, 'date_wise'=>$date_wise]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request)
    {
        
        
        
    //     $squared_mis_status = DB::table('baskets')->where('status','Squared-MIS')
    //     ->select('status',DB::raw('sum(Pnl) as value'))->groupBy('status')->get();
       
    //     $squared_ls_status = DB::table('baskets')->where('status','Squared-Sl')
    //     ->select('status',DB::raw('sum(Pnl) as value'))->groupBy('status')->get();
       
    //     $squared_status = DB::table('baskets')->where('status','Squared')
    //     ->select('status',DB::raw('sum(Pnl) as value'))->groupBy('status')->get();
       
    //   $data = [$squared_status, $squared_ls_status, $squared_mis_status];
      
      
    //     return response()->json(['status_body'=>$data]);
    
    
    
    
    
    
     date_default_timezone_set('Asia/kolkata');
        
        $from_date = '2022-06-01';
        $to_date = '2022-06-06';
        
        $basket_name = $request->basketNames;
        
        if($basket_name != "All" and $basket_name != ""){
            $basket_name = $basket_name;
        }else{
            $basket_name = "%%";
        }
        
        
        $from_ist = $from_date;
        $to_ist = $to_date;
        
        $from_ist = Carbon::createFromFormat('Y-m-d', $from_ist);
        $from_utc = $from_ist->setTimeZone(new DateTimeZone('UTC'));
        
        $to_ist = Carbon::createFromFormat('Y-m-d', $to_ist);
        $to_utc = $to_ist->setTimeZone(new DateTimeZone('UTC'));
        
         $user_id = 1;
           
        //   $data =  DB::select("SELECT * FROM `baskets` WHERE (DATE(created_at) >= '$from_date' and DATE(created_at) <= '$to_date') and `basket_name` like '$basket_name' and `user_id` = '$user_id'");
                
        $data = Basket::where([['created_at', '>=', $from_date.' 00:00:00'],['created_at', '<=',  $to_date.' 23:59:59']])->where('basket_name', 'like', '%' . $basket_name . '%')->where('user_id', $user_id)->with('orders')->get();
      
        $basket_pnl = DB::select("select @basket := `basket_name` as basket_name, @pnl := sum(Pnl) as value from baskets where date(created_at) >= '$from_date' and date(created_at) <= '$to_date' GROUP BY `basket_name` ORDER BY value DESC LIMIT 20");
        
        $basket_status = DB::select("select @basket := `status` as status, @pnl := sum(Pnl) as value from baskets where date(created_at) >= '$from_date' and date(created_at) <= '$to_date' and `basket_name` like '$basket_name' GROUP BY `status`");
        
        $mean_pnl = DB::select("select @basket_name := basket_name as basket_name, @basket := avg(Pnl) as avg_pnl, @pnl := avg(max_target_achived) as avg_trend from baskets where date(created_at) >= '$from_date' and date(created_at) <= '$to_date'  GROUP BY basket_name ORDER BY avg_pnl DESC LIMIT 20");
        
        $date_wise = DB::select("select @basket := DATE(created_at) as date, @pnl := sum(Pnl) as value from baskets where `basket_name` like '$basket_name' GROUP BY date");
       
        // return response()->json(['status'=>200,'data'=>$data, 'basket_pnl'=>$basket_pnl,'basket_status'=>$basket_status, 'mean_pnl'=>$mean_pnl, 'date_wise'=>$date_wise]);
        return response()->json(['status'=>200,'data'=>$data]);

    }
    
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
   
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
