<?php

namespace App\Http\Controllers\Webhook;

use stdClass;
use App\Models\Order;
use App\Models\Basket;
use App\Models\Gateway;
use App\Models\Webhook;
use App\Models\Instrument;
use Illuminate\Http\Request;
use App\Models\Webhookbasket;
use App\Http\Controllers\Controller;

class WebhookUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hookCall(Request $request, $id)
    {      
        
        $data = Webhook::find($id);

//------------------------- Square off signal implementation starts -----------------------------//

        // id($data->sq_target == 'Yes')










//------------------------- Square off signal implementation end -----------------------------//


        $data['alert_payload'] = $request->input();
        $data->update();
        
        date_default_timezone_set('Asia/kolkata');   
        $currentTime = strtotime(date("H:i"));
        $expire_time = "15:00";
        $expire_times = strtotime($expire_time);
        
        $exe_type = $request['exe_type'];
        
        if(($data['status'] != 'Running') and ($data['recurring'] == 'No') and ($currentTime < $expire_times) and ($data['recurring_status'] == 0)){
            $u_basket = Webhookbasket::where('webhook_id', $id)->first();
            $u_id = $u_basket->user_id;
            $exchange = $u_basket['segments'];
            $sq_signal = $u_basket['sq_signal'];  
            $gateway = Gateway::where('user_id', $u_id)->first();
            $api_key = $gateway['api_key'];
            $access_token = $gateway['access_token']; 
            if($u_basket['segments'] == "NFO"){
                #this code shuld run only if the segment is nfo
                $round_strike = round($request['strike']);
                $test = substr($round_strike, 0, -2);
                $option_type = $request['option_type'];
                $hest = $test . 0 . 0 . $option_type;
                $tokenName = "BANKNIFTY22JUL".$hest;
                $result = Instrument::where('tradingsymbol', $tokenName)->first();
                $hook = Webhook::find($id);
                $orderJson = new stdClass();
                $orderJson->token_id = $result['instrument_token'];
                $orderJson->token_strike = $tokenName;
                $orderJson->strick_type = $request['option_type'];
                $orderJson->order_type = $exe_type;
                $myOrderJson = json_encode(array($orderJson));
                $hook['order_details'] = $myOrderJson;
                $hook->update();                
            }

            $data = Webhook::find($id);
            $w_basket = Webhookbasket::where('webhook_id', $id)->first();
            $basket = new Basket;
            $basket->user_id = $data->user_id;
            $basket->basket_name = $w_basket->basket_name;
            $basket->target_strike = $w_basket->target_strike;
            $basket->init_target = $w_basket->init_target;
            $basket->stop_loss = $w_basket->stop_loss;
            $basket->scheduled_exec = $w_basket->scheduled_exec;
            $basket->scheduled_sqoff = $w_basket->scheduled_sqoff;
            $basket->recorring = $w_basket->recorring;
            $basket->weekDays = $w_basket->weekDays;
            $basket->strategy = $w_basket->strategy;
            $basket->qty = $w_basket->qty;
            $basket->status = "WFC"; # Status as per the Scheduled Basket..
            $basket->webhook_basket_id = $w_basket->id;
            $basket->created_by = "Hook";
            $basket->webhook_id = $id;
            $basket->intra_mis = $w_basket->intra_mis;
            $basket->sq_signal = $w_basket->sq_signal;
            $basket->segments = $w_basket->segments;
            $basket->save();
            $b_id = $basket->id;
            $u_id = $data->user_id;
            $orders =  $data->order_details;
            $details = json_decode($orders);
            
            $qty = $w_basket->qty;

            foreach($details as $det_order){
                
                $status_code = "";
                $o_type = $exe_type;
                $product =  $w_basket->intra_mis;
                
                      if($o_type == 'Buy'){
                          $tradingsymbol = $det_order->token_strike;
                          $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                          $status_code = $placeOrder[0];
                          $order = New Order;
                          
                          if($status_code == 403){      # needs to change as 200
                            $order['user_id'] = $u_id;
                            $order['basket_id'] = $b_id;
                            $order['qty'] = $data->qty;
                            $order['token_id'] = $det_order->token_id;
                            $order['token_name'] = $det_order->token_strike;
                     
                            // $order['order_id'] = $placeOrder[1]->data->order_id; //new order id from zerodha
                            $order['segments'] = $u_basket['segments'];

                            $order['order_type'] = $exe_type;
                            $order['status'] = "Active"; # Status as per the Scheduled Orders..
                            $order['order_status_code'] = $status_code;
                            $order->save();
                          }else{
                              $order['order_status_code'] = $status_code;
                              $order['user_id'] = $u_id;
                              $order['basket_id'] = $b_id;
                              $order['qty'] = $data->qty;
                              $order['token_id'] = $det_order->token_id;
                              
                              $order['token_name'] = $det_order->token_strike;

                              $order['order_id'] = 'error'; //new order id from zerodha
                            
                              $order['segments'] = $u_basket['segments'];
                            
                              $order['order_type'] = $exe_type;
                              $order['status'] = "Error";
                              $order->save();
                          }
                      
                      }
                      elseif($o_type == 'Sell'){
                          $tradingsymbol = $det_order->token_strike;
                          $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                          $status_code = $placeOrder[0];  
                          $order = New Order;
                          if($status_code == 403){ # needs to change as 200



                            $order['user_id'] = $u_id;
                            $order['basket_id'] = $b_id;
                            $order['qty'] = $data->qty;
                            $order['token_id'] = $det_order->token_id;
                            $order['token_name'] = $det_order->token_strike;
                            $order['order_type'] = $exe_type;
                            $order['status'] = "Active"; # Status as per the Scheduled Orders..

                            // $order['order_id'] = $placeOrder[1]->data->order_id; //

                            $order['segments'] = $u_basket['segments'];
                            $order['order_status_code'] = $status_code;
                            $order->save();
                          }else{
                              $order['order_status_code'] = $status_code;
                              $order['user_id'] = $u_id;
                              $order['basket_id'] = $b_id;
                              $order['qty'] = $data->qty;
                              $order['token_id'] = $det_order->token_id;
                              
                              $order['token_name'] = $det_order->token_strike;

                              $order['segments'] = $u_basket['segments'];

                              $order['order_id'] = 'error';
                              
                              $order['order_type'] = $exe_type;
                              $order['status'] = "Error";
                              $order->save();
                          }
                      } 
            }

            $web_basket = Webhookbasket::find($w_basket->id);
            $web_basket -> status = "Started";
            $web_basket->update();

            $data -> status = "Running";
            $data->recurring_status = $data->recurring_status + 1;
            $data->update();
            
            $b_id = $basket->id;
            $updatestatus = Basket::find($b_id);
            $updatestatus->status = "Active";
            $updatestatus->save();
            return $data;
            
        }elseif(($data['status'] != 'Running') and ($data['recurring'] == 'Yes') and ($currentTime < $expire_times)){
            
            $u_basket = Webhookbasket::where('webhook_id', $id)->first();
            
            $u_id = $u_basket->user_id;
                  
            $gateway = Gateway::where('user_id', $u_id)->first();
            $api_key = $gateway['api_key'];
            $access_token = $gateway['access_token']; 


            if($u_basket['segments'] == "NFO"){

                #this code shuld run only if the segment is nfo
                $round_strike = round($request['strike']);
                $test = substr($round_strike, 0, -2);
                $option_type = $request['option_type'];
                $hest = $test . 0 . 0 . $option_type;
                $tokenName = "BANKNIFTY22JUL".$hest;

                $result = Instrument::where('tradingsymbol', $tokenName)->first();
                $hook = Webhook::find($id);
                $orderJson = new stdClass();
                $orderJson->token_id = $result['instrument_token'];
                $orderJson->token_strike = $tokenName;
                $orderJson->strick_type = $request['option_type'];
                $orderJson->order_type = $exe_type;
                
                $myOrderJson = json_encode(array($orderJson));
                $hook['order_details'] = $myOrderJson;
                $hook->update();
            }

            $data = Webhook::find($id);
            $w_basket = Webhookbasket::where('webhook_id', $id)->first();
            $basket = new Basket;
            $basket->user_id = $data->user_id;
            $basket->basket_name = $w_basket->basket_name;
            $basket->target_strike = $w_basket->target_strike;
            $basket->init_target = $w_basket->init_target;
            $basket->stop_loss = $w_basket->stop_loss;
            $basket->scheduled_exec = $w_basket->scheduled_exec;
            $basket->scheduled_sqoff = $w_basket->scheduled_sqoff;
            $basket->recorring = $w_basket->recorring;
            $basket->weekDays = $w_basket->weekDays;
            $basket->strategy = $w_basket->strategy;
            $basket->qty = $w_basket->qty;
            $basket->status = "WFC"; # Status as per the Scheduled Basket..
            $basket->webhook_basket_id = $w_basket->id;
            $basket->created_by = "Hook";
            $basket->webhook_id = $id;
            $basket->intra_mis = $w_basket->intra_mis;
            $basket->sq_signal = $w_basket->sq_signal;
            $basket->segments = $w_basket->segments;
            $basket->save();
            
            $b_id = $basket->id;
            $u_id = $data->user_id;
            $orders =  $data->order_details;
            $details = json_decode($orders);
            $exchange = $u_basket['segments'];
            $qty = $w_basket->qty;
            $segments =  $w_basket->segments;
            foreach($details as $det_order){
                $o_type = $exe_type;
                $product =  $w_basket->intra_mis;
                      if($o_type == 'Buy'){
                          $tradingsymbol = $det_order->token_strike;
                          $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                          $status_code = $placeOrder[0];
                          $order = New Order;
                          if($status_code == 403){           # needs to change as 200
                            $order['user_id'] = $u_id;
                            $order['basket_id'] = $b_id;
                            $order['qty'] = $data->qty;
                            $order['token_id'] = $det_order->token_id;
                            
                            $order['token_name'] = $det_order->token_strike;

                            // $order['order_id'] = $placeOrder[1]->data->order_id;
                            $order['segments'] = $u_basket['segments'];

                            $order['order_type'] = $exe_type;
                            $order['status'] = "Active"; # Status as per the Scheduled Orders..
                            $order['order_status_code'] = $status_code;
                            $order->save();

                          }else{
                            
                              $order['order_status_code'] = $status_code;
                              $order['user_id'] = $u_id;
                              $order['basket_id'] = $b_id;
                              $order['qty'] = $data->qty;
                              $order['token_id'] = $det_order->token_id;
                              $order['token_name'] = $det_order->token_strike;
                              $order['order_id'] = 'error';
                              $order['segments'] = $u_basket['segments'];

                              $order['order_type'] = $exe_type;
                              $order['status'] = "Error";
                              $order->save();
                          }
                      
                      }
                      elseif($o_type == 'Sell'){
                        //   $qty = $det_order->strick_qty;
                          $tradingsymbol = $det_order->token_strike;
                          $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                          
                          $status_code = $placeOrder[0]; 
                          $order = New Order;
                          if($status_code == 403){ # needs to change as 200
                            $order['user_id'] = $u_id;
                            $order['basket_id'] = $b_id;
                            $order['qty'] = $data->qty;
                            $order['token_id'] = $det_order->token_id;
                            
                            $order['token_name'] = $det_order->token_strike;
                           
                            // $order['order_id'] = $placeOrder[1]->data->order_id;

                            $order['segments'] = $u_basket['segments'];

                            $order['order_type'] = $exe_type;
                            $order['order_status_code'] = $status_code;
                            $order['status'] = "Active"; # Status as per the Scheduled Orders..
                            $order->save();
                          }else{
                              $order['order_status_code'] = $status_code;
                              $order['user_id'] = $u_id;
                              $order['basket_id'] = $b_id;
                              $order['qty'] = $data->qty;
                              $order['token_id'] = $det_order->token_id;
                              
                              $order['token_name'] = $det_order->token_strike; 
                              $order['segments'] = $u_basket['segments'];

                              $order['order_id'] = 'error';
                              
                              $order['order_type'] = $exe_type;
                              $order['status'] = "Error";
                              $order->save();
                          }
                      } 
            }

            $web_basket = Webhookbasket::find($w_basket->id);
            $web_basket -> status = "Started";
            $web_basket->update();

            $data -> status = "Running";
            $data->recurring_status = $data->recurring_status + 1;
            $data->update();

            $b_id = $basket->id;
            $updatestatus = Basket::find($b_id);
            $updatestatus->status = "Active";
            $updatestatus->save();

            return $data;

        }else{
            return response()->json('already running');
        }
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
        //Model::latest()->first();
    }
}