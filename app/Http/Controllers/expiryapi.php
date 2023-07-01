<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Instrument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class InstrumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category_data = Http::get('https://api.kite.trade/instruments');
        $test = $category_data->body();
       
        $lines = preg_split('/[\n\r]/', $test);
        $ap = [];

        $strike_data = "";
        $stike_name = "";
        $tradingSymbol = "";

        $expiry_symbol = "";

        foreach($lines as $k => $data){
            if ($k < 1){
                continue;
            } 
            else{    
            try {
            $each_data = explode(',',$data);
            $data1['instrument_token'] = $each_data[0];
            $data1['exchange_token'] = $each_data[1];
            $data1['tradingsymbol'] = $each_data[2];
            $data1['name'] = $each_data[3];
            $data1['last_price'] = $each_data[4];
            $data1['expiry'] = $each_data[5];
            $data1['strike'] = $each_data[6];
            $data1['tick_size'] = $each_data[7];
            $data1['lot_size'] = $each_data[8];
            $data1['instrument_type'] = $each_data[9];
            $data1['segment'] = $each_data[10];
            $data1['exchange'] = $each_data[11];

            $strike_data = $each_data[6].$each_data[9];
            $stike_name = $each_data[3];
            
            $tradingSymbol = $each_data[2];

            $expiry_symbol = str_replace($strike_data, '', $tradingSymbol);
            
            
            $stike_name1 = str_replace('"', '', $stike_name);

            $expiry_symbol = str_replace($stike_name1, '', $expiry_symbol);
            
            $data1['strike_expiry'] = $expiry_symbol;

                Instrument::create($data1);
            } catch (\Exception $e) {
                array_push($ap, $e);
              continue;
            }
        
        }
    }
        return response()->json(['status'=>200, 'message'=>'data added successfully !', 'errors' => $ap]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        
        
        $search = $request['query'];
         
        //  dd($search);
         
        // $order = Instrument::where("tradingsymbol", 'like', '%'.$search.'%')->get();
        
        $order = Instrument::query()->whereLike('tradingsymbol', $search)->get();
       
        
        return response()->json($order); 
        
    }
    public function expiry()
    {
        
        $data = Instrument::where('segment', 'NFO-OPT')->select('strike_expiry')->groupBy('strike_expiry')->get();
        return response()->json($data);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function expiryApi()
    {
        date_default_timezone_set('Asia/kolkata');   
        $today = date('d-m-Y');
        $currentDay = date("d",strtotime($today));

        $weeks_per_year = Carbon::WEEKS_PER_YEAR;
        $first_thursday = Carbon::parse("first thursday of this year");

        $thursdays = [$first_thursday->toDateString()];

        for($i=1;$i<$weeks_per_year;$i++)
        {
            $latest_day =  $first_thursday->addWeeks(1)->toDateString();
            $checkDay = date("d",strtotime($latest_day));

            if($checkDay > $currentDay){

                array_push( $thursdays, $first_thursday->addWeeks(1)->toDateString() );
            }
        }

        dd($thursdays);
        $data = Instrument::select('segment')->groupBy('segment')->get();
        return response()->json($data);
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


public function expiryApi()
{
    
    // $data = Instrument::where('segment', 'NFO-OPT')->select('strike_expiry')->groupBy('strike_expiry')->get();
    // return response()->json($data);

    date_default_timezone_set('Asia/kolkata');   
    $today = date('d-m-Y');
    $currentDay = date("d",strtotime($today));

    $weeks_per_year = Carbon::WEEKS_PER_YEAR;
    $first_thursday = Carbon::parse("first thursday of this year");

    // $thursdays = [$first_thursday->toDateString()];
    $thursdays = [];

    for($i=1;$i<$weeks_per_year;$i++)
    {

        $latest_day =  $first_thursday->addWeeks(1)->toDateString();
        $dash_rem = str_replace("-", "", $latest_day);
        $checking_date = substr($dash_rem, 6, 2);

        $checkDay = date("d",strtotime($latest_day));

        if($checking_date > 23){
            $latest_day =  $first_thursday->addWeeks(1)->toDateString();
                $dash_rem = str_replace("-", "", $latest_day);
                $check_month = substr($dash_rem, 4, 5); 
                $year_edit = substr($dash_rem, 2);
                $thu = substr($year_edit, 0, -4);
        
                $monthNum  = substr($dash_rem, 4, 2);
                $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
                $name_post= substr($monthName, 0, 3);

                $final_thursday = $thu.$name_post;
                array_push($thursdays, $final_thursday);
        }else{
            $latest_day =  $first_thursday->addWeeks(1)->toDateString();
            $dash_rem = str_replace("-", "", $latest_day);
            $year_edit = substr($dash_rem, 2);
            array_push($thursdays, $year_edit);
        }
    }
}


public function expiryApi()
    {
        date_default_timezone_set('Asia/kolkata');   
        $today = date('d-m-Y');
        $currentDay = date("d",strtotime($today));

        $weeks_per_year = Carbon::WEEKS_PER_YEAR;
        $first_thursday = Carbon::parse("first thursday of this year");

        $thursdays = [$first_thursday->toDateString()];

        for($i=1;$i<$weeks_per_year;$i++)
        {
            $latest_day =  $first_thursday->addWeeks(1)->toDateString();
            $checkDay = date("d",strtotime($latest_day));

            if($checkDay > $currentDay){

                array_push( $thursdays, $first_thursday->addWeeks(1)->toDateString() );
            }
        }

        dd($thursdays);
        $data = Instrument::select('segment')->groupBy('segment')->get();
        return response()->json($data);
    }