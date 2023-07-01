<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Basket;
use Illuminate\Support\Facades\Auth;
use DateTimeZone;
use Carbon\Carbon;
use DB;
class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('history.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    public function getAllHistrory(Request $request)
    {
        date_default_timezone_set('Asia/kolkata');
        
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        
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
        
        // $from_ist = Carbon::createFromFormat('Y-m-d h:i:s', $from_ist);
        // $from_utc = $from_ist->setTimeZone(new DateTimeZone('UTC'));
        
        // $to_ist = Carbon::createFromFormat('Y-m-d h:i:s', $to_ist);
        // $to_utc = $to_ist->setTimeZone(new DateTimeZone('UTC'));
        
        
        $user = Auth::User();
        $user_id = $user->id;
                // $data = Basket::whereDateBetween('created_at',[$from_utc, $to_utc])->where('basket_name', 'like', $basket_name)->where('user_id', $user_id)->orderBy('status', 'ASC')->orderBy('created_at', 'DESC')->with('orders')->get();
                // $data = Basket::whereRaw("(created_at >= ? AND created_at <= ?)", [$from_date, $to_date])
                //                 ->where('basket_name', 'like', $basket_name)
                //                 ->where('user_id', $user_id)
                //                 ->orderBy('status', 'ASC')
                //                 ->orderBy('created_at', 'DESC')
                //                 ->with('orders')
                //                 ->get();
                
                if($basket_name == '%%'){
                    $data = Basket::where([['created_at', '>=', $from_date.' 00:00:00'],['created_at', '<=',  $to_date.' 23:59:59']])->where('basket_name','like', $basket_name)->where('user_id', $user_id)->with('orders')->get();
                }else{
                    $data = Basket::where([['created_at', '>=', $from_date.' 00:00:00'],['created_at', '<=',  $to_date.' 23:59:59']])->where('basket_name', $basket_name)->where('user_id', $user_id)->with('orders')->get();
                }
                
                // report data
                
                // $order_wise_asc = 'ASC';
                // $order_wise_ = 'ASC';
                
                
        // $basket_pnl = DB::table('baskets')->select('basket_name',DB::raw('sum(Pnl) as value'))->groupBy('basket_name')->orderBy('value', 'DESC')->take(15)->get();
        
        $basket_pnl = DB::select("select @basket := `basket_name` as basket_name, @pnl := sum(Pnl) as value from baskets where date(created_at) >= '$from_date' and date(created_at) <= '$to_date' and `user_id` = '$user_id' GROUP BY `basket_name` ORDER BY value DESC LIMIT 20");
                
        // $basket_status = DB::table('baskets')->select('status',DB::raw('sum(Pnl) as value'))->groupBy('status')->get();
        
        $basket_status = DB::select("select @basket := `status` as status, @pnl := sum(Pnl) as value from baskets where date(created_at) >= '$from_date' and date(created_at) <= '$to_date'  and `basket_name` like '$basket_name' and `user_id` = '$user_id' GROUP BY `status`");
        
        // $mean_pnl = DB::table('baskets')->select('basket_name',DB::raw('avg(Pnl) as avg_pnl, avg(max_target_achived) as avg_trend'))->groupBy('basket_name')->orderBy('avg_pnl', 'DESC')->take(10)->get();
        
        $mean_pnl = DB::select("select @basket_name := basket_name as basket_name, @basket := avg(Pnl) as avg_pnl, @pnl := avg(max_target_achived) as avg_trend from baskets where date(created_at) >= '$from_date' and date(created_at) <= '$to_date' and `user_id` = '$user_id'  GROUP BY basket_name ORDER BY avg_pnl DESC LIMIT 20");
        
        // $date_wise = DB::table('baskets')->select(DB::raw('DATE(created_at) as date'),DB::raw('sum(Pnl) as value'))->groupBy(DB::raw('DATE(created_at)'))->get();
       
       $date_wise = DB::select("select @basket := DATE(created_at) as date, @pnl := sum(Pnl) as value from baskets where `basket_name` like '$basket_name' and `user_id` = '$user_id' GROUP BY date");
       
        return response()->json(['status'=>200,'data'=>$data, 'basket_pnl'=>$basket_pnl,'basket_status'=>$basket_status, 'mean_pnl'=>$mean_pnl, 'date_wise'=>$date_wise]);

    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     public function getbasketName(Request $request)
    {
         $user = Auth::User();
        $bskt = Basket::where('user_id', $user->id)->select('basket_name')->groupBy('basket_name')->get();
        // $data = Basket::select( 'id', 'basket_name')->groupBy('basket_name')->get();
        return response()->json(['data' => $bskt]);
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
