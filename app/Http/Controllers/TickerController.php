<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tick;
use App\Models\Order;
use App\Models\Basket;
use App\Models\Indice;
use App\Models\Gateway;
use App\Models\Webhook;
use Illuminate\Http\Request;
use KiteConnect\KiteConnect;

class TickerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exist = Tick::get();
        return response()->json($exist);
         /*$kite = new KiteConnect("t8e53owre5vhtme0", "eoyEtgl10m9U50wa4P2QRkvcawuZrqVi");
     
        try {
            $user = $kite->generateSession("t8e53owre5vhtme0", "eoyEtgl10m9U50wa4P2QRkvcawuZrqVi");
            echo "Authentication successful. \n";
            print_r($user);
            $kite->setAccessToken($user->access_token);
        } catch(Exception $e) {
            echo "Authentication failed: ".$e->getMessage();
            throw $e;
        }
    
        echo $user->user_id." has logged in";*/

   /*     $data = [{'tradable': True, 'mode': 'full', 
            'instrument_token': 10603522, 'last_price': 273.1, 'last_traded_quantity': 50, 'average_traded_price': 286.71,
             'volume_traded': 241000, 'total_buy_quantity': 79150, 'total_sell_quantity': 56650,
             'ohlc': {'open': 262.45, 'high': 317.5, 'low': 262.2, 'close': 388.15}, 
             'change': -29.640602859719174, 'last_trade_time': datetime.datetime(2022, 4, 6, 9, 29, 50), 
             'oi': 1494450, 'oi_day_high': 1502600, 'oi_day_low': 1494450, 'exchange_timestamp': datetime.datetime(2022, 4, 6, 9, 29, 50),
             'depth': {
                 'buy': [
                 {'quantity': 50, 'price': 272.9, 'orders': 1}, 
                 {'quantity': 550, 'price': 272.8, 'orders': 5}, 
                 {'quantity': 50, 'price': 272.75, 'orders': 1}, 
                 {'quantity': 600, 'price': 272.7, 'orders': 2}, 
                 {'quantity': 50, 'price': 272.65, 'orders': 1}
                ], 
                 'sell': [
                     {'quantity': 300, 'price': 273.7, 'orders': 2}, 
                     {'quantity': 300, 'price': 273.75, 'orders': 5}, 
                     {'quantity': 450, 'price': 273.8, 'orders': 5}, 
                     {'quantity': 150, 'price': 273.9, 'orders': 1}, 
                     {'quantity': 550, 'price': 273.95, 'orders': 3}
                     ]
                    }
                }] */


        // $product = Product::create($request->all());
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
    
     public function getOrder()
    {
        $data = Order::where('status','Active')->pluck('token_id');
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->input();

        $deletable_data = Tick::where('status',1)->update(['properties' => $data]);
    
        foreach($data as $ind)
        {
            if($ind['instrument_token'] == 256265){
                    Indice::where('name', 'nifty')->update(['ltp' => $ind['last_price']]); 
            }
            if($ind['instrument_token'] == 260105){
                    Indice::where('name', 'banknifty')->update(['ltp' => $ind['last_price']]); 
        }
        }
    
       $basketList = Basket::where('status','Active')->with(['orders' => function ($query) {
                                $query->where('status','Active');
                    
                }])->get();
        $tickers = $request->all();
        
        
         $b_list = json_decode($basketList,true);
         
        //  dd($b_list);
        
         foreach($b_list as $basket_data){
             
            $newbasketPnl = 0;
            $prev_target = 0;
            $current_target = 0;
            $min_target = 0;
            $totalBasketPnl = 0;
            $totalBasketInv = 0;
            
            $init_target = 0;
            $stop_loss = 0;
            $target_strike = 0;

            $max_target_achived = 0;
            
            $order_type = "MIS";
            
             $init_target = $basket_data['init_target'];
             $stop_loss = $basket_data['stop_loss'];
             $target_strike = $basket_data['target_strike'];
             $prev_target = $basket_data['prev_current_target'];
             $current_target = $basket_data['current_target'];
             $max_target_achived = $basket_data['max_target_achived'];
            
             $min_target = $basket_data['min_target']; //db impl
            
             $basket_holder_id = $basket_data['user_id'];  # For Zerodha
             $basket_intra_mis = $basket_data['intra_mis'];  # For Zerodha
             $exchange_segment = $basket_data['segments'];  # For Zerodha
             
             
             $order_type = $basket_data['intra_mis'];
            
             $webhook_id = $basket_data['webhook_id'];
             
             foreach($basket_data['orders'] as $eachOrder){

                $average_price = 0;

                $average_price = 0;

                $pnl = 0;
                $orderPnl = 0;
                $orderInvestment = 0;
                $pnl_perc = 0;
                $ltp = 0;
                $qty =0;
                
                 foreach($tickers as $eachTick){
                     
    
                     if ($eachTick['instrument_token'] == $eachOrder['token_id']){
                        // #Concluding Average Price
                      $average_price = $eachOrder['order_avg_price'];
                      
                      $ltp = $eachTick['last_price'];
                      $qty = $eachOrder['qty'];
                      
                     
                     if($average_price == 0){
                             $average_price = $eachTick['last_price']; 
                             $updateOrderData = Order::where('id', $eachOrder['id'])->first();
                             $updateOrderData['order_avg_price'] = $average_price;
                             $updateOrderData->save();
                        }
                        
                        // Concluding Pnl
                        
                        if($eachOrder['order_type'] == 'Buy' && $average_price != NULL){
                            $pnl = $ltp - $average_price;
                        }else if($eachOrder['order_type'] == 'Sell' && $average_price != NULL){
                            // dd($average_price);
                            $pnl = $average_price - $ltp;
                        }else{
                            $pnl = 0;
                        }


                        $orderPnl = $pnl * $qty;
                        $orderInvestment = $qty * $average_price;
                        
                        if($orderPnl != 0 && $orderInvestment != 0){
                            $pnl_perc = $orderPnl / $orderInvestment;
                        };
                        
                        // $totalBasketPnl += $orderPnl;
                        $totalBasketInv += $orderInvestment;
                        
                        $updateOrderData = Order::where('id', $eachOrder['id'])->first();
                     
                     
                        $updateOrderData['pnl'] = $orderPnl;
                        $updateOrderData['pnl_perc'] = $pnl_perc;
                        $updateOrderData['status'] = 'Active';    
                        $updateOrderData['total_inv'] = $orderInvestment;
                        $updateOrderData['ltp'] = $ltp;
                        $updateOrderData->save();
                      
                     }
                     
                 }#Tick for loop
                
                if($orderPnl == 0){
                    $totalBasketPnl += $eachOrder['pnl'];
                }else{
                    $totalBasketPnl += $orderPnl;
                }
                
                
             }#Order for loop
             
             
             if($totalBasketPnl >= 100000){
                 $newbasketPnl = substr((string) round($totalBasketPnl),0, 3).'000';
             }elseif($totalBasketPnl >= 10000){
                 $newbasketPnl = substr((string) round($totalBasketPnl),0, 3).'00';
             }elseif($totalBasketPnl >= 1000){
                 $newbasketPnl = substr((string) round($totalBasketPnl),0, 2).'00';
             }elseif(($totalBasketPnl >= 100) && ($totalBasketPnl < 1000)){
                 $newbasketPnl = substr((string) round($totalBasketPnl),0, 1).'00';
             }else{
                 $newbasketPnl = 0;
             }
             
             #Max Target Acheived Function
             
             if(($totalBasketPnl != 0) and ($max_target_achived < $totalBasketPnl)){
                 $updateMaxPnl = Basket::where('id', $basket_data['id'])->first();
                 $updateMaxPnl['max_target_achived'] = round($totalBasketPnl);
                 $updateMaxPnl->save();
             }
             
             
             $newbasketPnl = (int) $newbasketPnl;
             
             if(($totalBasketPnl >= $init_target) and ($totalBasketPnl != 0) and ($init_target !=0)){
                 $current_target = $newbasketPnl - $target_strike;
                 
                 $updateBasketData = Basket::where('id', $basket_data['id'])->first();
                 $updateBasketData['current_target'] = $current_target;
                 $updateBasketData->save();
             }
             
             if($prev_target < $current_target){
                 
                 $updateBasketData = Basket::where('id', $basket_data['id'])->first();
                 $updateBasketData['prev_current_target'] = $current_target;
                 $updateBasketData->save();
                 
             }
             
             $min_target_m = (round($init_target * 0.70) - ($target_strike * 0.70));
             
             if($min_target == 0 && $totalBasketPnl != 0 && $min_target_m < $totalBasketPnl){
                 
                 $min_target_ach = $min_target_m;
                 $updateMin_target = Basket::where('id', $basket_data['id'])->first();
                 $updateMin_target['min_target'] = $min_target_ach;
                 $updateMin_target->save();
                 
                 $min_target = $min_target_ach;
                 
                 if($current_target == 0 && $min_target != 0 && $prev_target == 0){
                     $updateBasketData = Basket::where('id', $basket_data['id'])->first();
                     $updateBasketData['current_target'] = $min_target;
                     $updateBasketData->save();
                    }
             }
            
             #Target Square function
             
             if(($prev_target != 0) and ($totalBasketPnl != 0) and ($current_target != 0) and ($totalBasketPnl < $prev_target)){
             
                #--------------- Zerodha integeratiokn starts here ---------------#
                                                 
                $u_id = $basket_holder_id;
                $product = $basket_intra_mis;
                $exchange = $exchange_segment;
                
                $gateway = Gateway::where('user_id', $u_id)->first();
                $api_key = $gateway['api_key'];
                $access_token = $gateway['access_token']; 
                
                foreach($basket_data['orders'] as $each_order){
                    $status_code = '';
                    $find_order = Order::where('id', $each_order['id'])->first();
                    $o_type = $find_order['order_type'];
                    if($o_type == 'Buy'){
                        $qty = $find_order['qty'];
                        $tradingsymbol = $find_order['token_name'];
                        
                        // $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($tradingsymbol, $product, $qty, $o_type, $api_key, $access_token, $exchange);
                        $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                        
                        $status_code = $placeOrder[0];  
                        if($status_code == 403){          # needs to change as 200
                            $find_order['status'] = 'Squared';
                            $find_order['order_status_code'] = $status_code;
                            $find_order->save();
                        }else{
                            $find_order['order_status_code'] = $status_code;
                            $find_order->save();
                        }
                    
                    }
                    elseif($o_type == 'Sell'){
                        $qty = $find_order['qty'];
                        $tradingsymbol = $find_order['token_name'];
                         $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                        
                        $status_code = $placeOrder[0];  
                        if($status_code == 403){             # needs to change as 200
                            $find_order['status'] = 'Squared';
                            $find_order['order_status_code'] = $status_code;
                            $find_order->save();
                        }else{
                            $find_order['order_status_code'] = $status_code;
                            $find_order->save();
                        }
                    }     
                }
                    // $status_code = $placeOrder->status();    
                    
                #--------------- Zerodha integeratiokn ends here ---------------#
                $basketList = Basket::where('id', $basket_data['id'])->with(['orders' => function ($query) {
                    $query->where('status','Active');
        
                    }])->get();
               
                $check_list = json_decode($basketList,true);  
                // dd($check_list[0]['orders']);
                if(!($check_list[0]['orders'])){
                    $updateBasketStatus = Basket::where('id', $basket_data['id'])->first();
                    $updateBasketStatus['status'] = 'Squared';
                    $updateBasketStatus['Pnl'] = $totalBasketPnl;
                    $updateBasketStatus->save();
                
                
                    #Update the Webhook Status to wating for another call...
                 
                 if($webhook_id != null){
                    $hook = Webhook::find($webhook_id);
                    if($hook->recurring == "Yes"){
                        $hook->status = 'Waiting for call';
                        $hook->update();
                    }else{
                        $hook->status = 'Squared';
                        $hook->update();
                    }
                }
            }                 
        }
             
             

             #SL Square Funtion
             
             if((-abs($stop_loss) > $totalBasketPnl) and ($totalBasketPnl != 0) and ($stop_loss != 0)){
                 
                  #--------------- Zerodha integeratiokn starts here ---------------#
                                                 
                  $u_id = $basket_holder_id;
                  $product = $basket_intra_mis;
                  $exchange = $exchange_segment;
                  
                  $gateway = Gateway::where('user_id', $u_id)->first();
                  $api_key = $gateway['api_key'];
                  $access_token = $gateway['access_token']; 
                  
                  foreach($basket_data['orders'] as $each_order){
                      $find_order = Order::where('id', $each_order['id'])->first();
                      $o_type = $find_order['order_type'];
                      if($o_type == 'Buy'){
                          $qty = $find_order['qty'];
                          $tradingsymbol = $find_order['token_name'];
                        //   $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($tradingsymbol, $product, $qty, $o_type, $api_key, $access_token, $exchange);
                          $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                          $status_code = $placeOrder[0];
                        //   dd($placeOrder);
                          if($status_code == 403){          # needs to change as 200
                              $find_order['status'] = 'Squared-SL';
                              $find_order['order_status_code'] = $status_code;
                              $find_order->save();
                          }else{
                              $find_order['order_status_code'] = $status_code;
                              $find_order->save();
                          }
                      
                      }
                      elseif($o_type == 'Sell'){
                          $qty = $find_order['qty'];
                          $tradingsymbol = $find_order['token_name'];
                          $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                
                          $status_code = $placeOrder[0];  
                          if($status_code == 403){             # needs to change as 200
                              $find_order['status'] = 'Squared-SL';
                              $find_order['order_status_code'] = $status_code;
                              $find_order->save();
                          }else{
                              $find_order['order_status_code'] = $status_code;
                              $find_order->save();
                          }
                      }     
                  }
                      // $status_code = $placeOrder->status();    
                      
                  #--------------- Zerodha integeratiokn ends here ---------------#
                  $basketList = Basket::where('id', $basket_data['id'])->with(['orders' => function ($query) {
                    $query->where('status','Active');
        
                    }])->get();
               
                $check_list = json_decode($basketList,true);  
                // dd($check_list[0]['orders']);
                if(!($check_list[0]['orders'])){
                    $updateBasketStatus = Basket::where('id', $basket_data['id'])->first();
                    $updateBasketStatus['status'] = 'Squared-SL';
                    $updateBasketStatus['Pnl'] = $totalBasketPnl;
                    $updateBasketStatus->save();
                
                                 #Update the Webhook Status to wating for another call...
                 
                                 if($webhook_id != null){
                                    $hook = Webhook::find($webhook_id);
                                    if($hook->recurring == "Yes"){
                                        $hook->status = 'Waiting for call';
                                        $hook->update();
                                    }else{
                                        $hook->status = 'Squared-SL';
                                        $hook->update();
                                    }
                                }
                
                }    
                 

                 
             }
             
            #Day End Squeroff
             
            date_default_timezone_set('Asia/kolkata');   
            $currentTime = strtotime(date("H:i"));
            $expire_time = "15:15";
            $expire_times = strtotime($expire_time);
            
            if(($currentTime > $expire_times) and ($order_type == "MIS")){
                
                   #--------------- Zerodha integeration starts here ---------------#
                                                 
                   $u_id = $basket_holder_id;
                   $product = $basket_intra_mis;
                   $exchange = $exchange_segment;
                   
                   $gateway = Gateway::where('user_id', $u_id)->first();
                   $api_key = $gateway['api_key'];
                   $access_token = $gateway['access_token']; 
                   
                   foreach($basket_data['orders'] as $each_order){
                       $find_order = Order::where('id', $each_order['id'])->first();
                       $o_type = $find_order['order_type'];
                       if($o_type == 'Buy'){
                           $qty = $find_order['qty'];
                           $tradingsymbol = $find_order['token_name'];
                           $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->sell($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                           
                           $status_code = $placeOrder[0];  
                           if($status_code == 403){          # needs to change as 200
                               $find_order['status'] = 'Squared-MIS';
                               $find_order['order_status_code'] = $status_code;
                               $find_order->save();
                           }else{
                               $find_order['order_status_code'] = $status_code;
                               $find_order->save();
                           }
                       
                       }
                       elseif($o_type == 'Sell'){
                           $qty = $find_order['qty'];
                           $tradingsymbol = $find_order['token_name'];
                           $placeOrder = app('App\Http\Controllers\Gateway\GatewayController')->buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty);
                           
                           $status_code = $placeOrder[0];  
                           if($status_code == 403){             # needs to change as 200
                               $find_order['status'] = 'Squared-MIS';
                               $find_order['order_status_code'] = $status_code;
                               $find_order->save();
                           }else{
                               $find_order['order_status_code'] = $status_code;
                               $find_order->save();
                           }
                       }     
                   }
                       // $status_code = $placeOrder->status();    
                       
                   #--------------- Zerodha integeratiokn ends here ---------------#
                   $basketList = Basket::where('id', $basket_data['id'])->with(['orders' => function ($query) {
                     $query->where('status','Active');
         
                     }])->get();
                
                 $check_list = json_decode($basketList,true);  
                 if(!($check_list[0]['orders'])){
                     $updateBasketStatus = Basket::where('id', $basket_data['id'])->first();
                     $updateBasketStatus['status'] = 'Squared-MIS';
                     $updateBasketStatus['Pnl'] = $totalBasketPnl;
                     $updateBasketStatus->save();
                 
                                  #Update the Webhook Status to wating for another call...
                  
                                  if($webhook_id != null){
                                     $hook = Webhook::find($webhook_id);
                                     if($hook->recurring == "Yes"){
                                         $hook->status = 'Waiting for call';
                                         $hook->update();
                                     }else{
                                         $hook->status = 'Squared-MIS';
                                         $hook->update();
                                     }
                                 }
                 
                 }    
                   
            }
            
            if($totalBasketPnl != 0){
                 $updateBasketPnl = Basket::where('id', $basket_data['id'])->first();
                 $updateBasketPnl['Pnl'] = $totalBasketPnl;
                 $updateBasketPnl->save();
             }
             

         }#Basket for loop

        //  $getOrders = Order::where('status','Active')->where('order_avg_price', NULL)->get();
         
        //  foreach($getOrders as $order){
        //     $ZerodhaOrder = app('App\Http\Controllers\Gateway\GatewayController')->getOrder($order['user_id']);
        //     try {
        //         foreach($ZerodhaOrder as $zOrder){
        //             if($zOrder->order_id == $order['order_id']){
        //                 $order->order_avg_price =  $zOrder->average_price;
        //                 $order->update();
        //             }
    
        //         }
        //     } catch (\Throwable $th) {
        //         //throw $th;
        //     }

        //  }
                           
        
        // $data = Order::groupBy('token_name')->where('status','Active')->get(['token_name']); # for real execution  
        //   $data = Order::where('status','Active')->get(['token_name','segments']);
        $data = Order::where('status','Active')->pluck('token_id');

        return $data;
    
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
