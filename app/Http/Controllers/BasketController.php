<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Basket;
use GuzzleHttp\Client;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
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
        $user = Auth::User();                                  
        $user->id;                                             
        $basketcat = Basket::where('user_id',$user->id)->get();
        return view('basket.index', compact('basketcat'));     
    }                                                            
       
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('basket.create');
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->a  ll());
        $user = Auth::User();
        $u_id = $user->id;
        $gateway = Gateway::where('user_id', $u_id)->first();
        $api_key = $gateway['api_key'];
        $access_token = $gateway['access_token']; 
        $exchange = $request->segment;
        $orders_data = $request->data;
        $product = $request->ORDER_TYPE;
        $quantity = $request->qty;

        //  Create Basket Here....

        $basket = New Basket;
        $basket->user_id = Auth::User()->id;
        $basket->basket_name = $request->basket_name;
        $basket->target_strike = $request->target_strike;
        $basket->init_target = $request->init_target;
        $basket->stop_loss = $request->stop_loss;
        $basket->scheduled_exec = $request->scheduled_exec;
        $basket->scheduled_sqoff = $request->scheduled_sqoff;
        $basket->recorring = $request->recorring;
        $basket->weekDays = $request->weekDays;
        $basket->strategy = $request->strategy;
        $basket->qty = $request->qty;
        $basket->segments = $request->segment;
        $basket->created_by = "Self";
        $basket->status = "Active"; # Status as per the Scheduled Basket..
        $basket->intra_mis = $request->ORDER_TYPE; 
        $basket->save();


        foreach($orders_data as $o_data){

            $status_code = '';
            
            $o_type = $o_data['order_type'];

                if($o_type == 'Buy'){
                    $qty = $o_data['strick_qty'];
                    $tradingsymbol = $o_data['token_strike'];
                    $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                    }
                elseif($o_type == 'Sell'){
                    $qty = $o_data['strick_qty'];
                    $tradingsymbol = $o_data['token_strike'];
                    $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                    }
                
                    //Place Order Here..
                    $status_code = $placeOrder[0];
                    
                    $order = New Order;
                    $order->user_id = Auth::User()->id;
                    $order->basket_id = $basket->id;
                    $order->qty = $o_data['strick_qty'];
                    $order->token_id = $o_data['token_id'];
                    $order->token_name = $o_data['token_strike'];
                    $order->order_type = $o_data['order_type'];
                    $order->segments = $request->segment;
                    $order->order_status_code = $status_code;
                    
                    if($status_code == 403){
                        $order->status = "Active";
                        // $order->order_id = $placeOrder[1]->data->order_id;
                    }else{
                        $order->status = "Error";
                        $order->order_id = "error";
                    }
                    $order->save();
            }

// elseif($status_code == 500){
//     return redirect('basket')->with('error', 'something went wrong');
// }
// elseif($status_code == 400){
//     return redirect('basket')->with('error', 'Missing or bad request parameters or values');
// }
// elseif($status_code == 403){
//     return redirect('basket')->with('error', 'Session expired or invalidate. Must relogin');
// }
// elseif($status_code == 404){
//     return redirect('basket')->with('error', 'Request resource was not found');
// }
// elseif($status_code == 405){
//     return redirect('basket')->with('error', 'Request method (GET, POST etc.) is not allowed on the requested endpoint');
// }
// elseif($status_code == 410){
//     return redirect('basket')->with('error', 'The requested resource is gone permanently');
// }
// elseif($status_code == 429){
//     return redirect('basket')->with('error', 'Too many requests to the API (rate limiting)');
// }
// elseif($status_code == 502){
//     return redirect('basket')->with('error', 'The backend OMS is down and the API is unable to communicate with it');
// }
// elseif($status_code == 503){
//     return redirect('basket')->with('error', 'Service unavailable; the API is down');
// }
// elseif($status_code == 504){
//     return redirect('basket')->with('error', 'Gateway timeout; the API is unreachable');
// }

    return redirect('basket')->with('success', 'Basket  Created Successfully');
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
         $order = Basket::findOrFail($id);
          if($request->has('stop_loss')){
         $order['stop_loss'] = $request->stop_loss;
          }
          if($request->has('target_strike')){
         $order['target_strike'] = $request->target_strike;
          }
          if($request->has('current_target')){
         $order['current_target'] = $request->current_target;
          }
          if($request->has('prev_current_target')){
         $order['prev_current_target'] = $request->prev_current_target;
          }
          if($request->has('pnl_perc')){
         $order['pnl_perc'] = $request->pnl_perc;
          }
         if($request->has('pnl')){
         $order['pnl'] = $request->pnl;    
         }
          if($request->has('init_target')){
         $order['init_target'] = $request->init_target;
          }
          if($request->has('max_target_achived')){
         $order['max_target_achived'] = $request-> max_target_achived ;
          }
          if($request->has('stop_loss')){
         $order['stop_loss'] = $request->stop_loss;
          }
         $order->save();
         return response()->json(['status'=>403, 'message'=>'Basket Updated Successfully !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $basket = Basket::findOrFail($id);
        $basket->delete();
        return redirect('basket');
    }
}
