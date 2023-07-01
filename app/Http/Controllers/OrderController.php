<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Basket;
use App\Models\Gateway;
use App\Models\Webhook;
use Illuminate\Http\Request;
use App\Models\Webhookbasket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
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
        $basket = Basket ::where('user_id',$user->id)->get();
        return view('strategy-builder',['basket' => $basket]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $order = New Order;
        $order->user_id = Auth::User()->id;
        $order->basket_id = $request->basket_id;
        $order->token_name = $request->token_name;
        $order->token_id = $request->token_id;
        $order->leg_type = $request->leg_type;
        $order->qty = $request->qty;
        $order->status = $request->status;
        $order->order_type = $request->order_type;
        $order->delta = $request->delta;
        $order->theta = $request->theta;
        $order->vega = $request->vega;
        $order->gamma = $request->gamma;
        $order->save();
        return redirect('holdings');
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
    public function getOrder()
    {
        $data = Order::where('status','Active')->pluck('token_id');
        return $data;
    }
    
    public function getAllOrder()
    {

         date_default_timezone_set('Asia/kolkata');   
         $from_date = strtotime(date("Y-m-d"));
         $t1 = '2022-07-04';
         
        $user = Auth::User();
        $user_id = $user->id;
        $data = Basket::where('user_id', $user_id)->whereDate('updated_at', Carbon::today())->with('orders')->get();
        return response()->json(['status'=>200,'data'=>$data]);

    }

    public function getAllWebhookOrder()
    {

        $user = Auth::User();
        $user_id = $user->id;
        $data = Webhookbasket::where('user_id',$user_id)->orderBy('updated_at', 'DESC')->with('webhook')->get();
               
        return response()->json(['status'=>200,'data'=>$data]);

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
         $order = Order::findOrFail($id);         
        if($request->has('ltp')){
             $order['ltp'] = $request->ltp;
        }
        if($request->has('order_avg_price')){
            $order['order_avg_price'] = $request->order_avg_price;
        }
         
        if($request->has('pnl')){
            $order['pnl'] = $request->pnl;
          }
        if($request->has('pnl_perc')){
            $order['pnl_perc'] = $request->pnl_perc;
          }
        if($request->has('status')){
            $order['status'] = $request->status;    
         }
          if($request->has('total_inv')){
         $order['total_inv'] = $request->total_inv;
          }
           if($request->has('init_target')){
         $order['init_target'] = $request->init_target;
           }
            if($request->has('current_target')){
         $order['current_target'] = $request->current_target;
            }
            if($request->has('stop_loss')){
         $order['stop_loss'] = $request->stop_loss;
            }
         $order->save();
         return response()->json(['status'=>200, 'message'=>'Order Updated Successfully !']);
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
    
    public function exitOrder($id){
        $order = Order::where('basket_id',$id)->get();
        $user = Auth::User();
        $u_id = $user->id;
        $gateway = Gateway::where('user_id', $u_id)->first();
        $api_key = $gateway['api_key'];
        $access_token = $gateway['access_token']; 
        $bskt =  Basket::where('id', $id)->first();
         $exchange = $bskt->segments;
         $product = $bskt->intra_mis;
         $webhook_basket_id = $bskt->webhook_basket_id;
         $webhook_id = $bskt->webhook_id;
        
        foreach($order as $each_order){
            
            $o_type = $each_order['order_type'];
            if($o_type == 'Buy'){
                $qty = $each_order['strick_qty'];
                $tradingsymbol = $each_order['token_strike'];
                // $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($tradingsymbol, $product, $qty, $o_type, $api_key, $access_token);
                $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($api_key, $access_token, $tradingsymbol, $exchange, $qty);
                $status_code = $placeOrder[0]; 
                // $status_code = $placeOrder->status();  
                if($status_code == 403){          # needs to change as 200
                    $each_order['status'] = 'Custom-Squared';
                    $each_order['order_status_code'] = $status_code;
                    $each_order->save();
                }else{
                    $each_order['order_status_code'] = $status_code;
                    $each_order->save();
                }
            
            }
            elseif($o_type == 'Sell'){
                $qty = $each_order['strick_qty'];
                $tradingsymbol = $each_order['token_strike'];
                // $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($tradingsymbol, $product, $qty, $o_type, $api_key, $access_token);
                $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);

                $status_code = $placeOrder[0];  
                if($status_code == 403){             # needs to change as 200
                    $each_order['status'] = 'Squared-MIS';
                    $each_order['order_status_code'] = $status_code;
                    $each_order->save();
                }else{
                    $each_order['order_status_code'] = $status_code;
                    $each_order->save();
                }
            }     
        }
            // $status_code = $placeOrder->status();    
            
        #--------------- Zerodha integeratiokn ends here ---------------#
        $basketList = Basket::where('id', $id)->with(['orders' => function ($query) {
          $query->where('status','Active');
          }])->get();
     
      $check_list = json_decode($basketList,true);  
      if(!($check_list[0]['orders'])){
          $updateBasketStatus = Basket::where('id', $id)->first();
          $updateBasketStatus['status'] = 'Custom-Squared';
        //   $updateBasketStatus['Pnl'] = $totalBasketPnl;
          $updateBasketStatus->save();
        
      }
      
      
       if($webhook_basket_id != null){
            $webhook_data = Webhook::find($webhook_id);
            $webhook_basketdata = Webhookbasket::find($webhook_basket_id);
            
            if($webhook_data->recurring == "Yes"){
                $webhook_data->status = 'Waiting for call';
                $webhook_basketdata->status = 'Waiting for call';
                $webhook_data->update();
                $webhook_basketdata->update();
            }else{
                $webhook_data->status = 'Squared-SL';
                $webhook_basketdata->status = 'Squared-SL';
                $webhook_data->update();
                $webhook_basketdata->update();
                // $hook->status = 'Squared-SL';
                // $hook->update();
            }
            //   $webhook_id = $basketList['webhook_id'];
            //   if($webhook_id != null){
            //     $hook = Webhook::find($webhook_id);
            //     if($hook->recurring == "Yes"){
            //         $hook->status = 'Waiting for call';
            //         $hook->update();
            //     }else{
            //         $hook->status = 'Squared-SL';
            //         $hook->update();
            //     }
        }
        
        // $basket = Basket::findOrFail($id);
        // $basket -> status = 'Squared';
        // $basket->save();
        // $order = Order::where('basket_id',$id)->get();
        // foreach($order  as $ord){
        //     $ord ->status = 'Squared';
        //     $exitprice =  $ord->ltp;
        //     $ord->exit_price = $exitprice;
        //     $ord->save();
        // }
    }

    public function statusView($id){
        $basket = Basket::where('webhook_id', $id)->latest()->first();
        $webhookStatus = $basket->status;
        return response()->json(['status'=>200,'webhookStatus'=>$webhookStatus]);
    }

}
