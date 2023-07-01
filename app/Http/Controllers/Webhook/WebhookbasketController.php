<?php

namespace App\Http\Controllers\Webhook;

use App\Models\Basket;
use App\Models\Webhook;
use App\Models\Webhookbasket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WebhookbasketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $user = Auth::User();
        $user_id = $user->id;
        $data = Webhookbasket::where('user_id',$user_id)->orderBy('updated_at', 'DESC')->with('webhook')->get();
        return view('webhook.index',['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('webhook.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $webhook = new Webhook;
        $webhook->user_id = Auth::User()->id;
        $webhook->hook_name = $request->basket_name;
        $webhook->qty = $request->qty;
        $webhook->recurring = $request->recorring;
        
        if($request->has('sq_signal')){
            $webhook->sq_signal = $request->sq_signal;
        }
        $webhook->status = "wating for call"; 
        
        $basketData = $request->data;
        
        if($basketData != null){
        $test = array_values($request->data);
        $data = json_encode($test);
        $webhook->order_details = $data;
        }
       
       
        $webhook->save();
        
        $webbookbasket = New Webhookbasket;
        $webbookbasket->user_id = Auth::User()->id;
        $webbookbasket->webhook_id = $webhook->id;
        $webbookbasket->basket_name = $request->basket_name;
        
        if(!($request->has('sq_signal'))){
        $webbookbasket->target_strike = $request->target_strike;
        $webbookbasket->init_target = $request->init_target;
        $webbookbasket->stop_loss = $request->stop_loss;
        }else {
            $webbookbasket->sq_signal = $request->sq_signal;
        }

        $webbookbasket->scheduled_exec = $request->scheduled_exec;
        $webbookbasket->scheduled_sqoff = $request->scheduled_sqoff;
        $webbookbasket->recorring = $request->recorring;
        $webbookbasket->weekDays = $request->weekDays;
        $webbookbasket->strategy = $request->strategy;
        $webbookbasket->qty = $request->qty;
        $webbookbasket->intra_mis = $request->ORDER_TYPE;
        $webbookbasket->status = "Pending"; # Status as per the Scheduled webbookbasket..

        if($request->segment == "NFO"){
            $webbookbasket->indices = $request->indices;
        }

        $webbookbasket->segments = $request->segment;

        $webbookbasket->save();
             
        $webhook_update = Webhook::findOrFail($webhook->id);
        $webhook_update ->post_api = "https://edith.kalyaniaura.com/api/webhook/".$webhook->id;
        $webhook_update -> webhookbasket_id =  $webbookbasket->id; 
        $webhook_update ->update();
        return redirect('webhook');

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
        $editdata = Webhookbasket::findOrFail($id);
        // dd($editdata);
        return view('webhook.editdata',compact('editdata'));
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
        // dd($request->all());
         $webbookbasket = Webhookbasket::findOrFail($id);
            $webbookbasket->basket_name = $request->basket_name;

            $webbookbasket->scheduled_exec = $request->scheduled_exec;
            $webbookbasket->scheduled_sqoff = $request->scheduled_sqoff;
            $webbookbasket->recorring = $request->recorring;
            $webbookbasket->weekDays = $request->weekDays;
            $webbookbasket->strategy = $request->strategy;

            if(!($request->has('sq_signal'))){
                $webbookbasket->target_strike = $request->target_strike;
                $webbookbasket->init_target = $request->init_target;
                $webbookbasket->stop_loss = $request->stop_loss;
            }else {
                $webbookbasket->sq_signal = $request->sq_signal;
            }

            $webbookbasket->qty = $request->qty;
            $webbookbasket->status = "Pending";
              $webbookbasket ->update();

           $webhook = $webbookbasket->webhook_id;
            $webhook =  Webhook::find( $webhook);
            $webhook->hook_name = $request->basket_name;
            $webhook->qty = $request->qty;
            $webhook->recurring = $request->recorring;
            
            if($request->has('sq_signal')){
                $webhook->sq_signal = $request->sq_signal;
            }
            $webhook->status = "wating for call";
            $basketData = $request->data;
                if($basketData != null){
                    $test = array_values($request->data);
                    $data = json_encode($test);
                    $webhook->order_details = $data;
                } 
             $webhook->update();
        // $test = array_values($request->data);
            // $data = json_encode($test);
            // $webhook->order_details = $data;
            return redirect('webhook'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editwebookData($id)
    {
        $editWebhook = Webhook::findOrFail($id);

        $data =  $editWebhook->order_details;
        // dd($data);
        $webwookdata = json_decode($data, true);

        return response()->json($webwookdata);
        // dd($area);
            // foreach($area as $i => $v)
            // {
            //     dd($v['token_id']);
            // }

    }
}