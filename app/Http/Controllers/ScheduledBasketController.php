<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Tick;
use App\Models\Order;
use App\Models\Basket;
use App\Models\Indice;
use App\Models\Instrument;
use Illuminate\Http\Request;
use App\Models\ScheduledBasket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScheduledBasketController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(['auth']);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::User();
        $schedulebasket = ScheduledBasket ::where('user_id',$user->id)->get();
        return view('scheduled.index', compact('schedulebasket'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function testresponse()
    {
        $a = 4;
        $b = 5;
        $c = $a * $b;
        echo 'this';
    }
    public function create()
    {
        return view('scheduled.create');
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
       
        $scheduled = new ScheduledBasket;
        $scheduled->user_id = Auth::User()->id;
        $scheduled->scbasket_name = $request->scbasket_name;
        $scheduled->init_target = $request->init_target;
        $scheduled->target_strike = $request->target_strike;
        $scheduled->stop_loss = $request->stop_loss;
        $scheduled->indices = $request->order_type;
        $scheduled->isSchedule = $request->isScheduled;
        if($request->isRecurring != null){
            $scheduled->recurring = $request->isRecurring;
        }else{
            $scheduled->recurring = 'off';
        }
        
        $scheduled->segments = $request->segments;
        $scheduled->indices = $request->indices;
        $scheduled->scheduled_exec = $request->scheduled_exec;
        $scheduled->scheduled_sqoff = $request->scheduled_sqoff;
        $scheduled->status = "Scheduled"; 
        $scheduledData = $request->data;
            if($scheduledData != null){
                $test = array_values($request->data);
                $data = json_encode($test);
                $scheduled->orders = $data;
            }
            $scheduled->save();
            return redirect('scheduled');          
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
    public function scheduledData($id)
    {
        $schedulebasket = ScheduledBasket::findOrfail($id);
        return response()->json($schedulebasket);
      }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scheduledUdate(Request $request, $id)
    {
        // dd($request->all());

        $schedulebasket = ScheduledBasket::findOrfail($id);
        $req_data = $request->data;
        unset($req_data[0]);
        $arr_check = array_chunk($req_data,5);
        $arr = [];
        $as = ['type','strick_qty','strick_type','order_type','strike_expiry'];
        foreach ($arr_check as $key => $value) {
            foreach($value  as $data){
                array_push($arr, $data['value']);
            }          
        }
        $arr_check = array_chunk($arr,5);
        $merge_data = [];
       foreach($arr_check as $check){
        $merge = array_combine($as, $check);
        array_push($merge_data, $merge);

       }
        $data = json_encode($merge_data);

        $schedulebasket->orders = $data;
        $schedulebasket->update();
        return response()->json(['status'=>200, 'message'=>' Updated Successfully !']);

        // return redirect('scheduled');          
        return  redirect()->back();

        // $type = '';
        // $strike_type = '';
        // $qty = '';
        // $o_type = '';
        // $test = array();
        
        // $data = $request->data;
        // foreach($data as $deser){
        //     if($deser['name'] == 'type'){
        //         $type = $deser['value'];
        //     }
        //     if($deser['name'] == 'strick_qty'){
        //         $qty = $deser['value'];
        //     }
        //     if($deser['name'] == 'strick_type'){
        //         $strick_type = $deser['value'];
        //     }
        //     if($deser['name'] == 'order_type'){
        //         $o_type = $deser['value'];
        //     }  
        //     array_push($test, array($type, $strike_type, $qty, $o_type));
        // }
        // // dd($test);
        // print_r($test);
        

        // $test = array_values($request->data);
        // dd($test);
        // $data = json_encode($request->data);
        $scheduled->orders = $data;
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


    // Scheduled Bsket --------------------------------------------------->$_COOKIE


    public function runSchedule()
    {

        
        $isScheduledBasket = ScheduledBasket::all();
        date_default_timezone_set('Asia/kolkata');   
        $currentTime = strtotime(date("H:i"));
        foreach($isScheduledBasket as $schBasket){
        
           
           $startTime = $schBasket['scheduled_exec'];
           $stopTime = $schBasket['scheduled_sqoff'];
           $isRecurring = $schBasket['recurring'];
           $status = $schBasket['status'];
           $user_id = $schBasket['user_id'];
           $s_basket_segments = $schBasket['segments'];
           $recurring_status = $schBasket['recurring_status'];
           $baskStart = strtotime($startTime);
           $baskStop = strtotime($stopTime);

           // This is to Execute Scheduled Non Recurring Orders..
           if(($isRecurring == 'off') and ($status != "Active") and ($status == 'Scheduled') and ($currentTime > $baskStart) and ($currentTime < $baskStop)){
        
            $basket = new Basket;
                    $basket->user_id = $user_id;
                    $basket->basket_name = $schBasket->scbasket_name;
                    $basket->target_strike = $schBasket->target_strike;
                    $basket->scheduled_basket_id = $schBasket['id'];
                    $basket->init_target = $schBasket->init_target;
                    $basket->stop_loss = $schBasket->stop_loss;
                    $basket->scheduled_exec = Carbon::now();
                    $basket->recorring = $schBasket->recurring;
                    $basket->weekDays = $schBasket->weekDays;
                    $basket->strategy = $schBasket->strategy;
                    $basket->qty = $schBasket->qty;
                    $basket->status = "Active"; # Status as per the Scheduled Basket..
                    $basket->created_by = "Schedule";
                    $basket->segments = $s_basket_segments;
                    $basket->intra_mis = 'MIS';
                    $basket->save();
                    
                    $b_id = $basket->id;
                    $u_id = $user_id;
                    $orders =  $schBasket->orders;
                    $details = json_decode($orders);
                    
                    $PlaceBasket = ScheduledBasket::where('id', $schBasket['id'])->first();
                    $PlaceBasket['status'] = 'Active'; // scheduled basket status ---> 
                    $PlaceBasket->save();
                    
                    foreach($details as $det_order){
                        //To get Tick Data

                        $result = '';
                        $ticks_data = Indice::get();
                        
                        // $ticks = json_decode($ticks_data);
                        // $find_tick = $ticks[0]->properties;
                 
                                foreach($ticks_data as $find){
                                    if(($find->name == 'nifty') and ($PlaceBasket->indices == 'nifty')){
                                        
                                        if($det_order->type == 'ATM'){
                                            $ltp = $find->ltp;
                                            $round_strike = round($ltp);
                                            $test = substr($round_strike, 0, -2);
                                            $option_type = $det_order->strick_type;
                                            $hest = $test . 0 . 0 . $option_type;
                                            $tokenName = "NIFTY".$det_order->strike_expiry.$hest;
                                            $result = Instrument::where('tradingsymbol', $tokenName)->first();
                                        }
                                        else{
                                        $ltp = $find->ltp;
                                        $round_strike = round($ltp);
                                        $test = substr($round_strike, 0, -2);
                                        $add_zero = $test . 0 . 0 ;
                                        $string = $det_order->type;
                                        $flip = intval($string);
                                        $operation = $add_zero + $flip; 
                                        $option_type = $det_order->strick_type;
                                        $hest = $operation . $option_type;
                                        $tokenName = "NIFTY".$det_order->strike_expiry.$hest;
                                        $result = Instrument::where('tradingsymbol', $tokenName)->first();
                                        }
                                        $order = New Order;
                                        $order['user_id'] = $u_id;
                                        $order['basket_id'] = $b_id;
                                        $order['qty'] = $det_order->strick_qty;
                                        $order['token_id'] = $result['instrument_token'];

                                        $order['segments'] = $s_basket_segments;
                
                                        $order['token_name'] = $result['tradingsymbol'];
                                        $order['order_type'] = $det_order->order_type;
                                        $order['status'] = "Active"; # Status as per the Scheduled Orders..
                                        $order->save();
                
                                    }

                                   elseif(($find->name == 'banknifty') and ($PlaceBasket->indices == 'banknifty')){
                                    if($det_order->type == 'ATM'){
                                        $ltp = $find->ltp;
                                        $round_strike = round($ltp);
                                        $test = substr($round_strike, 0, -2);
                                        $option_type = $det_order->strick_type;
                                        $hest = $test . 0 . 0 . $option_type;
                                        $tokenName = "BANKNIFTY".$det_order->strike_expiry.$hest;
                                        $result = Instrument::where('tradingsymbol', $tokenName)->first();
                                    }
                                    else{
                                    $ltp = $find->ltp;
                                    $round_strike = round($ltp);
                                    $test = substr($round_strike, 0, -2);
                                    $add_zero = $test . 0 . 0 ;
                                    $string = $det_order->type;
                                    $flip = intval($string);
                                    $operation = $add_zero + $flip; 
                                    $option_type = $det_order->strick_type;
                                    $hest = $operation . $option_type;
                                    $tokenName = "BANKNIFTY".$det_order->strike_expiry.$hest;
                                    $result = Instrument::where('tradingsymbol', $tokenName)->first();
                                    }
                                    $order = New Order;
                                    $order['user_id'] = $u_id;
                                    $order['basket_id'] = $b_id;
                                    $order['qty'] = $det_order->strick_qty;
                                    $order['token_id'] = $result['instrument_token'];

                                    $order['segments'] = $s_basket_segments;
            
                                    $order['token_name'] = $result['tradingsymbol'];
                                    $order['order_type'] = $det_order->order_type;
                                    $order['status'] = "Active"; # Status as per the Scheduled Orders..
                                    $order->save();
            
                                    }
                                }

                    }

                }

           // This is to Execute Scheduled Recurring Orders..
           if((($status == 'Sq Re-scheduled') or ($status == 'Scheduled')) and ($status != "Active") and ($isRecurring == 'on') and ($currentTime > $baskStart) and ($currentTime < $baskStop)){
                 # Update the Status "Active"

                 $basket = new Basket;
                 $basket->user_id = $user_id;
                 $basket->basket_name = $schBasket->scbasket_name;
                 $basket->target_strike = $schBasket->target_strike;
                 $basket->scheduled_basket_id = $schBasket['id'];
                 $basket->init_target = $schBasket->init_target;
                 $basket->stop_loss = $schBasket->stop_loss;
                 $basket->scheduled_exec = Carbon::now();
                 $basket->recorring = $schBasket->recurring;
                 $basket->weekDays = $schBasket->weekDays;
                 $basket->strategy = $schBasket->strategy;
                 $basket->qty = $schBasket->qty;
                 $basket->status = "Active"; # Status as per the Scheduled Basket..
                 $basket->created_by = "Schedule";
                 $basket->segments = $s_basket_segments;
                 $basket->intra_mis = 'MIS';
                 $basket->save();
                 
                 $b_id = $basket->id;
                 $u_id = $user_id;
                 $orders =  $schBasket->orders;
                 $details = json_decode($orders);
                 
                 $PlaceBasket = ScheduledBasket::where('id', $schBasket['id'])->first();
                 $PlaceBasket['status'] = 'Active';
                 $PlaceBasket->save();
                     
                 foreach($details as $det_order){
                     $result = '';

                     //To get Tick Data
                     $result = '';
                     $ticks_data = Indice::get();
                    //  $ticks_data = Tick::get();
                    //  $ticks = json_decode($ticks_data);
                    //  $find_tick = $ticks[0]->properties;
              
                             foreach($ticks_data as $find){
                                 if(($find->name == 'nifty') and ($PlaceBasket->indices == 'nifty')){
                                      
                                     if($det_order->type == 'ATM'){
                                         $ltp = $find->ltp;
                                         $round_strike = round($ltp);
                                         $test = substr($round_strike, 0, -2);
                                         $option_type = $det_order->strick_type;
                                         $hest = $test . 0 . 0 . $option_type;
                                         $tokenName = "NIFTY".$det_order->strike_expiry.$hest;
                                         $result = Instrument::where('tradingsymbol', $tokenName)->first();
                                     }
                                     else{
                                     $ltp = $find->ltp;
                                     $round_strike = round($ltp);
                                     $test = substr($round_strike, 0, -2);
                                     $add_zero = $test . 0 . 0 ;
                                     $string = $det_order->type;
                                     $flip = intval($string);
                                     $operation = $add_zero + $flip; 
                                     $option_type = $det_order->strick_type;
                                     $hest = $operation . $option_type;
                                     $tokenName = "NIFTY".$det_order->strike_expiry.$hest;
                                     $result = Instrument::where('tradingsymbol', $tokenName)->first();
                                     }
                                     $order = New Order;
                                     $order['user_id'] = $u_id;
                                     $order['basket_id'] = $b_id;
                                     $order['qty'] = $det_order->strick_qty;
                                     $order['token_id'] = $result['instrument_token'];
                                     $order['segments'] = $s_basket_segments;
             
                                     $order['token_name'] = $result['tradingsymbol'];
                                     $order['order_type'] = $det_order->order_type;
                                     $order['status'] = "Active"; # Status as per the Scheduled Orders..
                                     $order->save();
             
                                 }
                                 
                                elseif(($find->name == 'banknifty') and ($PlaceBasket->indices == 'banknifty')){
                                 
                                    if($det_order->type == 'ATM'){
                                    
                                     $ltp = $find->ltp;
                                     $round_strike = round($ltp);
                                     $test = substr($round_strike, 0, -2);
                                     $option_type = $det_order->strick_type;
                                     $hest = $test . 0 . 0 . $option_type;
                                     $tokenName = "BANKNIFTY".$det_order->strike_expiry.$hest;
                                     $result = Instrument::where('tradingsymbol', $tokenName)->first();
                                 }
                                 else{
                                   
                                $ltp = $find->ltp;
                                 $round_strike = round($ltp);
                                 $test = substr($round_strike, 0, -2);
                                 $add_zero = $test . 0 . 0 ;
                                 $string = $det_order->type;
                                 $flip = intval($string);
                                 $operation = $add_zero + $flip; 
                                 $option_type = $det_order->strick_type;
                                 $hest = $operation . $option_type;
                                 $tokenName = "BANKNIFTY".$det_order->strike_expiry.$hest;
                                 
                                 $result = Instrument::where('tradingsymbol', $tokenName)->first();
                                 }
                                
                                 $order = New Order;
                                 $order['user_id'] = $u_id;
                                 $order['basket_id'] = $b_id;
                                 $order['qty'] = $det_order->strick_qty;
                                 $order['token_id'] = $result['instrument_token'];

                                 $order['segments'] = $s_basket_segments;
         
                                 $order['token_name'] = $result['tradingsymbol'];
                                 $order['order_type'] = $det_order->order_type;
                                 $order['status'] = "Active"; # Status as per the Scheduled Orders..
                                 $order->save();
         
                                 }
                             }
                 }
           
            }

           if(($isRecurring == 'off') and ($status == 'Active') and ($currentTime > $baskStop)){
            # Update the Status "Scheduled Squared Off"
         
            $PlaceBasket = ScheduledBasket::where('id', $schBasket['id'])->first();
            
            $updateBasketStatus = Basket::with('orders')->where('scheduled_basket_id', $PlaceBasket['id'])->where('status', 'Active')->first();
            
            if($updateBasketStatus != null){
            $updateBasketStatus['status'] = 'Scheduled Squared Off';
            $updateBasketStatus->save();

            foreach($updateBasketStatus['orders'] as $basket_orders){                   
                $updateOrderData = Order::where('id', $basket_orders['id'])->first();
                $updateOrderData['status'] = 'Scheduled Squared Off';
                $updateOrderData->save();
            }

        }

            $PlaceBasket['status'] = 'Scheduled Squared Off';
            $PlaceBasket->save();

           }
           
           // This is to Sq Off Scheduled Recurring Orders..
           if(($status == 'Active') and ($isRecurring == 'on') and ($currentTime > $baskStop)){
        
            # Update the Status "Sq Re-scheduled"
            $PlaceBasket = ScheduledBasket::where('id', $schBasket['id'])->first();
            $updateBasketStatus = Basket::with('orders')->where('scheduled_basket_id', $PlaceBasket['id'])->where('status', 'Active')->first();
            
            if($updateBasketStatus != null){
            $updateBasketStatus['status'] = 'Sq Re-scheduled';
            $updateBasketStatus->save();

            foreach($updateBasketStatus['orders'] as $basket_orders){              
                $updateOrderData = Order::where('id', $basket_orders['id'])->first();
                $updateOrderData['status'] = 'Sq Re-scheduled';
                $updateOrderData->save();
            }
        }
            $PlaceBasket['status'] = 'Sq Re-scheduled';
            $PlaceBasket->save();
               
           }

       }    
    // }
    // catch(Exception $e){
    //     echo 'something went wrong';
    // }                                                            
    }
}