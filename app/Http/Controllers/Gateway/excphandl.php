<?php

namespace App\Http\Controllers\Gateway;

use Exception;
use GuzzleHttp\Client;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Http::get('https://kalyanimotorsapi.kalyanicrm.com/api/allmodellist');
        dd($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buy($tradingsymbol, $product, $qty, $o_type, $api_key, $access_token)
    {
 
     try{   
    $client = New Client();
    $r = $client->post('https://api.kite.trade/orders/regular',[
                            'form_params' => [
                            'api_key' => $api_key,
                            'access_token' => $access_token,
                            'tradingsymbol' => $tradingsymbol,
                            'transaction_type' => "BUY",
                            'order_type' => "MARKET",
                            'exchange' => "NFO",
                            'quantity' => $qty,
                            'product' => $product,
                            'validity' => "DAY",
                            ]
                        ]);
                    }
                    catch (\GuzzleHttp\Exception\ClientException $e) {
                        // $error['error'] = $e->getMessage();
                        // $error['request'] = $e->getRequest();
                        $error_msg = $e->getMessage();
                        $error_status_code = $e->getResponse()->getStatusCode();

                        if($e->hasResponse()){

                            return response()->json(['client_exp'=>$error_msg], $error_status_code);
                       
                            // you can pass a specific status code to catch a particular error here I have catched 400 Bad Request. 
                         
                        //   if ($e->getResponse()->getStatusCode() == '403'){
                        //      $error['response'] = $e->getResponse(); 
                        //      return response()->json(['client_exp'=>$error], 403);
                        //   }
                        }
                        return response()->json(['client_exp'=>$error_msg],$error_status_code);
                    }
    }

    public function sell($tradingsymbol, $product, $qty, $o_type, $api_key, $access_token)
    {
    
        try{  

    $client = New Client();
    $r = $client->post('https://api.kite.trade/orders/regular',[
                        'form_params' => [
                            'api_key' => $api_key,
                            'access_token' => $access_token,
                            'tradingsymbol' => $tradingsymbol,
                            'transaction_type' => "SELL",
                            'order_type' => "MARKET",
                            'exchange' => "NFO",
                            'quantity' => $qty, 
                            'product' => $product,
                            'validity' => "DAY",
                            ]
                        ]);
                    }
                    catch (\GuzzleHttp\Exception\ClientException $e) {
                        // $error['error'] = $e->getMessage();
                        // $error['request'] = $e->getRequest();
                        $error_msg = $e->getMessage();
                        $error_status_code = $e->getResponse()->getStatusCode();

                        if($e->hasResponse()){

                            return response()->json(['client_exp'=>$error_msg], $error_status_code);
                       
                            // you can pass a specific status code to catch a particular error here I have catched 400 Bad Request. 
                         
                        //   if ($e->getResponse()->getStatusCode() == '403'){
                        //      $error['response'] = $e->getResponse(); 
                        //      return response()->json(['client_exp'=>$error], 403);
                        //   }
                        }
                        return response()->json(['client_exp'=>$error_msg],$error_status_code);
                    } catch(\GuzzleHttp\Exception\RequestException $se){
                        // $error['error'] = $e->getMessage();
                        // $error['request'] = $e->getRequest();
                        // $error['statusco'] = $e->getStatusCode();
                        $error_msg = $e->getMessage();
                        $error_status_code = $e->getResponse()->getStatusCode();
                        if($e->hasResponse()){

                            return response()->json(['client_exp'=>$error_msg], $error_status_code);

                        return response()->json(['req_exp'=>$error_msg], $error_status_code);
                    } 
                    return response()->json(['stats'=>$error_status_code]);
                    
    }

    }
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
        $gateway = New Gateway;
        $gateway->user_id = $request->user_id;
        $gateway->zero_token = $request->zero_token;
        $gateway->api_key = $request->api_key;
        $gateway->access_token = $request->access_token;
        $gateway->user_name = $request->user_name;
        $gateway->password = $request->password;
        $gateway->t_otp = $request->t_otp;
        $gateway->type = $request->type;
        $gateway->remarks = $request->remarks;
        $gateway->status = $request->status; 
        $gateway->save();

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
