<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gateway;
use Illuminate\Support\Facades\Auth;

class WebGatewayController extends Controller
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
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $test = Test::first(1);

        $user = Auth::User();
        $gateway = Gateway::where('user_id',$user->id)->first();       
        return view ('gateway.create',['user'=>$user, 'gateway' => $gateway]);

        // return view('gateway.create',['user'=> $user, 'gateway' => $gateway]);

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
        $api_key =  $request->api_key;
        $access_token =  $request->access_token;             
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
            'Authorization: token '.$api_key.':'.$access_token
        ),
        ));
        
        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);
        $status = $result->status;
        ////
        if($status == "success"){
            $gateway->user_id = Auth::User()->id;
            // $gateway->user_id = $request->user_id;
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
            return redirect('basket')->with('success', 'Gateway Updated Successfully ');
         } else {
            return redirect()->back()->with('error', 'Api Key Or Access Token is invalid');         
         }
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
    public function update(Request $request, $id){      
        $gateway = Gateway::findOrFail($id);           
        $api_key =  $request->api_key;
        $access_token =  $request->access_token;             
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
            'Authorization: token '.$api_key.':'.$access_token
        ),
        ));
        
        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);
        $status = $result->status;
        ////
            if($status == "success"){
                $gateway['user_id'] = Auth::User()->id;
                $gateway['zero_token'] = $request->zero_token;
                $gateway['api_key'] = $request->api_key;
                $gateway['access_token'] = $request->access_token;
                $gateway['user_name'] = $request->user_name;
                $gateway['password'] = $request->password;
                $gateway['t_otp'] = $request->t_otp;
                $gateway['type'] = $request->type;
                $gateway['remarks'] = $request->remarks;
                $gateway['status'] = $request->status;
                $data =  $gateway->update();

                return redirect('basket')->with('success', 'Gateway Updated Successfully ');
            }else {
                return redirect()->back()->with('error', 'Api Key Or Access Token is invalid');         
            }
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
