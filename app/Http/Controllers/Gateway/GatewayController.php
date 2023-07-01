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
        // $data = Http::get('https://kalyanimotorsapi.kalyanicrm.com/api/allmodellist');
        // dd($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buy($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty)
    {
        $error_code = '';
        
        try{
            
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.kite.trade/orders/regular',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'api_key='.$api_key.'&access_token='.$access_token.'&tradingsymbol='.$tradingsymbol.'&exchange='.$exchange.'&transaction_type=BUY&order_type=MARKET&quantity='.$qty.'&product='.$product.'&validity=DAY',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
      ),
    ));
    
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error_code = $httpcode;
    $order_detail = json_decode($response);
    
    curl_close($curl);
    
    
        }
        catch(Exception $e){
            $error_msg = $e->getMessage();
            $error_status_code = $e->getResponse()->getStatusCode();
            
           
        }
        return [$error_code, $order_detail];

    }
    

    public function sell($api_key, $access_token, $tradingsymbol, $exchange, $product, $qty)
    {
        $error_code = '';
        
        try{
            
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.kite.trade/orders/regular',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'api_key='.$api_key.'&access_token='.$access_token.'&tradingsymbol='.$tradingsymbol.'&exchange='.$exchange.'&transaction_type=SELL&order_type=MARKET&quantity='.$qty.'&product='.$product.'&validity=DAY',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
      ),
    ));
    
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error_code = $httpcode;
    $order_detail = json_decode($response);
    curl_close($curl);
    
    
        }
        catch(Exception $e){
            $error_msg = $e->getMessage();
            $error_status_code = $e->getResponse()->getStatusCode();
            
           
        }
        return [$error_code, $order_detail];
        
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
   public function show(Request $request, $id)
    {

        $gateway = Gateway::where('user_id', $id)->first(); 
        $gateway['access_token'] = $request->access_token;
        $data =  $gateway->update();
        
        return response()->json('updated successfully');
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


    // E4EEBvv18c4PZxjd9J9xm5LtlmnqAbn4

 public function getOrder($id)
    {

        $gateway = Gateway::where('user_id', $id)->first(); 
        $gatewayToekn = $gateway->access_token;
        $gatewayapi = $gateway->api_key;
       
       
      $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.kite.trade/orders',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'X-Kite-Version: 3',
            'Authorization: token'.$gatewayapi.':'.$gatewayToekn
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        $result = json_decode($response);
        
        $data = $result->data;
        // dd($result);
        return $data;
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
