<?php

namespace App\Http\Controllers\Webhook;

use App\Models\Order;
use App\Models\Basket;
use App\Models\Webhook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class WebhookController extends Controller
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
        // $user = Auth::User();
        // $user->id;
        // $webhook = Basket ::where('status','=','Pending')->where('user_id',$user->id)->get();
        // dd($webhook);
        return view('webhook.index');
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
        $basket->status = "Pending"; # Status as per the Scheduled Basket..

        $basket->save();
        $webhook = new Webhook;
        $webhook->user_id = Auth::User()->id;
        $webhook->basket_id = $basket->id;
        $webhook->hook_name = $request->basket_name;
        $webhook->qty = $request->qty;
        $webhook->recurring = $request->recorring;
        $webhook->status = "wating for call"; 
        $test = array_values($request->data);
        $data = json_encode($test);
        $webhook->order_details = $data;
        $webhook->save();

        $webhook_update = Webhook::findOrFail($webhook->id);
        $webhook_update ->post_api = "https://edith.kalyaniaura.com/api/webhook/".$webhook->id;
        $webhook_update ->update();
        return redirect('webhook');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hookCall($id)
    {
        $data = Webhook::find($id);
        dd($data);
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
