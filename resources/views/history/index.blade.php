@extends('layouts.master')
@section('title')
Dashboard
@endsection
 <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="./historyAsset/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./historyAsset/img/favicon.png">

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="./historyAsset/css/nucleo-icons.css" rel="stylesheet" />
  <link href="./historyAsset/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js"></script>
  <link href="./historyAsset/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="./historyAsset/css/argon-dashboard.css?v=2.0.2" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@section('content')

<br><br>

<div class="container-fluid">

    
    <div class="row p-3 no-gutters">
        <div class="col-sm-2 ml-1">
            <div>
                <label>From Date</label>
                <input type="date" id="from_date" id="from_date" name="from_date">
            </div>
        </div>
         <div class="col-sm-2 ml-1">
            <div>
            <label>To Date</label>
            <input type="date" id="to_date" id="to_date" name="from_date">
            </div>
        </div>
        
        <div class="col-sm-3 ml-1">
            <div>
            <label>Select Basket</label>&nbsp;
            <select  name="segment" id="basketNames" required>
                 <option value="" selected disabled hidden>Choose here</option>
            </select>
            </div>
        </div>
    </div>
    
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Active Baskets</p>
                    <h1 style="margin-top:10px;color:green" id="active_profit" class="font-weight-bolder">...</h1>
                    <p class="mb-0">
                      <!--<div ></div>-->
                      <span id="active_count" class="text-sm font-weight-bolder">...</span>
                       Active Count
                    </p>
                    <spane><span>Max Trend</span>
                      &nbsp<span id="cur_max" style="color:rgb(72, 189, 18)">...</span>&nbsp<span>Avg</span>
                      &nbsp<span id="cur_avg" style="color:rgb(72, 189, 18)">...</span></spane>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Net Profit</p>
                    <h1 style="margin-top:10px;color:black" id="net_profit" class="font-weight-bolder">...</h1>
                    <p class="mb-0">
                        <span id="total_count" class="text-success text-sm font-weight-bolder">...</span>
                      <!--<div ></div>-->
                      Net Profit</span>&nbsp<span id="net_profit_pers" style="font-size: 15px; color:red">...</span>
                    </p>
                    <span><span>Max Trend</span>
                      &nbsp<span id="net_max" style="color:rgb(72, 189, 18)">...</span>&nbsp<span>Avg</span>
                      <span id="net_avg" style="color:rgb(72, 189, 18)">...</span></span>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Profit Squared</p>
                    <h1 style="margin-top:10px;color:green" id="squared_profit" class="font-weight-bolder">....</h1>
                    <p class="mb-0">
                      <!--<div ></div>-->
                      <span id="squared_count" class="text-success text-sm font-weight-bolder">...</span>
                       Profit Squared
                      </span>&nbsp<span id="totalProfit_pers" style="font-size: 15px; color:rgb(72, 189, 18)">...</span>
                      </span>
                    </p>
                    <span><span>Max Trend</span>
                      &nbsp<span id="profit_max" style="color:rgb(72, 189, 18)">...</span>&nbsp<span>Avg</span>
                      <span id="profit_avg" style="color:rgb(72, 189, 18)">...</span></span>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Squared Stop Loss</p>
                    <h1 style="margin-top:10px;color:red" id="squared_sl" class="font-weight-bolder">...</h1>
                    <p class="mb-0">
                      <!--<div ></div>-->
                      <span id="squared_sl_count" class="text-danger text-sm font-weight-bolder" >...</span>
                      Squared-SL</span>&nbsp<span id="totalLoss_pers" style="font-size: 15px; color:red">...</span>
                    </p>
                    <span><span>Max Trend</span>
                      &nbsp<span id="sl_max" style="color:green">...</span>&nbsp<span>Avg</span>
                      <span id="sl_avg" style="color:red">...</span></span>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        
        <div class="col-xl-6 col-xl-5 mb-xl-0 mb-4">
          <div class="card">
            <div id="myStatusChart_id" class="card-body p-3">
                
            </div>
          </div>
        </div>
        
        <div class="col-xl-6 col-xl-5 mb-xl-0 mb-4">
          <div class="card">
            <div id="pnlTrend_id" class="card-body p-3">
                <!--<canvas id="pnlTrend"></canvas>-->
            </div>
          </div>
        </div>
        
        <div class="col-xl-6 col-xl-5 mb-xl-0 mb-4">
          <div class="card">
            <div id="maxTrendChart_id" class="card-body p-3">
                <!--<canvas id="maxTrendChart"></canvas>-->
            </div>
          </div>
        </div>
        
        <div class="col-xl-6 col-xl-5 mb-xl-0 mb-4">
          <div class="card">
            <div id="allbasketChart_id" class="card-body p-3">
                
            </div>
          </div>
        </div>

    </div>

@endsection

@section('scripts')
  <!--   Core JS Files   -->
  <script src="./historyAsset/js/core/popper.min.js"></script>
  <script src="./historyAsset/js/core/bootstrap.min.js"></script>
  <script src="./historyAsset/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="./historyAsset/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="./historyAsset/js/plugins/chartjs.min.js"></script>

<script>
	$(document).ready(function(){
	    
		$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	
	var from_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
	var to_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
	var basketNames = 'All';
	
	$('#from_date').on('change',function(){
        
        from_date = $(this).val();
        if(from_date === ""){
            from_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
        }
        getApi();
      
	   });
	   
	   $('#to_date').on('change',function(){
        
        to_date = $(this).val();
        
        if(to_date === ""){
            to_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
        }
        getApi();
        
	   });
	   
        $('#basketNames').on('change',function(){
            basketNames = $(this).val();
            getApi();
        });        
        
	    console.log(from_date +' - '+to_date);
	getApi();	
	function getApi() {
	    
		$.ajax({
			type:'get',
			url:'{{ route( 'dashboard.data' ) }}',
			data:{
			    'from_date':from_date,
			    'to_date':to_date,
			    'basketNames':basketNames,
				'_token':"{{ csrf_token() }}",
			},
			success: function (result) {
			  
			  $('#allbasketChart_id').html('');
			  $('#allbasketChart_id').append('<canvas id="allbasketChart"></canvas>')
			  
			  const allbasket = document.getElementById("allbasketChart").getContext("2d");
              allbasket.height = 200;

			  basket_pnl = result.basket_pnl;
              basket_names = [];
              basket_value = [];
              basket_pnl.forEach((element) => {
                basket_names.push(element.basket_name);
                basket_value.push(element.value);
              });
            
              const datas = {
                labels: basket_names,
                datasets: [
                  {
                    label: "My First Dataset",
                    data: basket_value,
                    backgroundColor: [
                      "rgb(255, 99, 132)",
                      "rgb(75, 192, 192)",
                      "rgb(255, 205, 86)",
                      "rgb(201, 203, 207)",
                      "rgb(54, 162, 235)",
                    ],
                  },
                ],
              };
            
              new Chart(allbasket, {
                type: "polarArea",
                data: datas,
                options: {
                  plugins: {
                    legend: {
                      display: false,
                    },
                  },
                },
              });
              
              
              $('#myStatusChart_id').html('');
			  $('#myStatusChart_id').append('<canvas id="myStatusChart"></canvas>')
              
              let myChart = document.getElementById("myStatusChart").getContext("2d");
              
              datasets_status = result.basket_status;
              status_dataLabels = [];
              status_dataValues = [];
              status_barColors = [];
              datasets_status.forEach((element) => {
                status_dataLabels.push(element.status);
                status_dataValues.push(element.value);
                
                if(element.status === 'Squared'){
                    status_barColors.push("rgb(41, 255, 151)");
                }else if(element.status === 'Squared-SL'){
                    status_barColors.push("rgb(255, 51, 0)");
                }else if(element.status === 'Squared-MIS'){
                    status_barColors.push("rgb(0, 204, 255)");
                }else if(element.status === 'Squared-MIS' && element.value < 0){
                    status_barColors.push("rgb(255, 0, 102)");
                }else{
                    status_barColors.push("rgb(41, 255, 151)");
                }
                
                // if (element.value < 0) {
                //   status_barColors.push("rgb(255, 112, 41)");
                // } else {
                //   status_barColors.push("rgb(41, 255, 151)");
                // }
                
              });
            
              new Chart(myChart, {
                type: "bar",
                data: {
                  labels: status_dataLabels,
                  datasets: [
                    {
                      label: "Profit & Loss",
                      data: status_dataValues,
                      backgroundColor: status_barColors,
                    },
                  ],
                },
              });
			    
			  $('#maxTrendChart_id').html('');
			  $('#maxTrendChart_id').append('<canvas id="maxTrendChart"></canvas>')  
			  
			  const maxTrendChart = document.getElementById("maxTrendChart").getContext("2d");
			  
			  trend_datasets = result.mean_pnl;
              trend_basket_names = [];
              trend_avg_pnl = [];
              trend_avg_max = [];
              trend_datasets.forEach((element) => {
                trend_basket_names.push(element.basket_name);
                trend_avg_pnl.push(element.avg_pnl);
                trend_avg_max.push(element.avg_trend);
              });
              
              // <block:setup:1>
              const trend_data = {
                labels: trend_basket_names,
                datasets: [
                  {
                    label: "Maximum Trend",
                    data: trend_avg_max,
                    fill: true,
                    backgroundColor: "rgba(255, 99, 132, 0.2)",
                    borderColor: "rgb(255, 99, 132)",
                    pointBackgroundColor: "rgb(255, 99, 132)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgb(255, 99, 132)",
                  },
                  {
                    label: "Actual Profit & Loss",
                    data: trend_avg_pnl,
                    fill: true,
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    borderColor: "rgb(54, 162, 235)",
                    pointBackgroundColor: "rgb(54, 162, 235)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgb(54, 162, 235)",
                  },
                ],
              };
            
              new Chart(maxTrendChart, {
                type: "radar",
                data: trend_data,
                options: {
                  elements: {
                    line: {
                      borderWidth: 3,
                    },
                  },
                },
              });
              
              $('#pnlTrend_id').html('');
			  $('#pnlTrend_id').append('<canvas id="pnlTrend"></canvas>')  
			  let pnlTrend = document.getElementById("pnlTrend").getContext("2d");
			  
			  date_basket_pnl = result.date_wise;
              basket_date = [];
              date_basket_value = [];
              date_basket_pnl.forEach((element) => {
                basket_date.push(element.date);
                date_basket_value.push(element.value);
              });
            
              pnlTrendChart = new Chart(pnlTrend, {
                type: "line",
                data: {
                  labels: basket_date,
                  datasets: [
                    {
                      label: "Net Profit & Loss",
                      data: date_basket_value,
                    },
                  ],
                },
              });
			  
			},
			error: function () {
				console.log('Error');
			}
		});
	}
});
</script>
  
//   <script>
//     var win = navigator.platform.indexOf('Win') > -1;
//     if (win && document.querySelector('#sidenav-scrollbar')) {
//       var options = {
//         damping: '0.5'
//       }
//       Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
//     }
//   </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="./historyAsset/js/argon-dashboard.min.js?v=2.0.2"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js"></script>
    
  <script>
	$(document).ready(function(){
	    
		$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	setInterval(function(){
		realTime();	
	},1000);
	
	console.log(moment.utc(new Date()).format('YYYY-MM-DD hh:mm'))
	
	var from_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
	var to_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
	var basketNames = 'All';
	console.log(from_date +' - '+to_date);
	function realTime() {
	    
	   $('#from_date').on('change',function(){
        
        from_date = $(this).val();
        
        if(from_date === ""){
            from_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
        }
      
	   });
	   
	   $('#to_date').on('change',function(){
        
        to_date = $(this).val();
        
        if(to_date === ""){
            to_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
        }
        
	   });
	   
        
        $('#basketNames').on('change',function(){
            basketNames = $(this).val();
        });        
        
	    console.log(from_date +' - '+to_date);
	    
		$.ajax({
			type:'get',
			url:'{{ route( 'dashboard.data' ) }}',
			data:{
			    'from_date':from_date,
			    'to_date':to_date,
			    'basketNames':basketNames,
				'_token':"{{ csrf_token() }}",
			},
			success: function (result) {
			     
                var squared_count = 0;
                var squared_profit = 0;
                var active_count = 0;
                var active_profit = 0;
                var total_count = 0;
                var stop_loss = 0;
                var squared_sl = 0;
                var squared_sl_count = 0;
                
                var totalProfit_pers = 0;
                var totalLoss_pers = 0;
                
                var net_profit = 0;
                var net_profit_pers = 0;
                
                var net_max = 0;
                var cur_max = 0;
                var profit_max = 0;
                var sl_max = 0;
                
                var cur_avg = 0;
                var net_avg = 0;
                var profit_avg = 0;
                var sl_avg = 0;
                
                
                $('#squared_profit').html('');
                $('#squared_count').html('');
                
                
                $('#active_profit').html('');
                $('#active_count').html('');
                
                
                $('#squared_sl').html('');
                $('#squared_sl_count').html('');
                
                $('#totalProfit_pers').html('');
                $('#totalLoss_pers').html('');
                
                $('#net_profit').html('');
                $('#net_profit_pers').html('');
                
                $('#net_max').html('');
                $('#cur_max').html('');
                $('#profit_max').html('');
                $('#sl_max').html('');
                
                $('#net_avg').html('');
                $('#cur_avg').html('');
                $('#profit_avg').html('');
                $('#sl_avg').html('');
                
                $('#total_count').html('');
                
                totalInvestment = 0;
                
                $.each(result.data, function(key,value){
                    
                    // if(value.status === 'Squared'){
                    //     squared_count = squared_count + 1;
                    //     squared_profit += value.Pnl;
                    //     profit_avg = (squared_profit / squared_count).toFixed(0);
                    //     if(profit_max < value.max_target_achived){
                    //         profit_max = value.max_target_achived;
                    //     }
                    // };
                    
                    // if(value.status === 'Active'){
                    //     active_count = active_count + 1;
                    //     active_profit += value.Pnl; 
                    //     cur_avg = (active_profit / active_count).toFixed(0);
                    //     if(cur_max < value.max_target_achived){
                    //         cur_max = value.max_target_achived;
                    //     }
                    // };
                    
                    // if(value.status === 'Squared-SL'){
                    //     squared_sl_count = squared_sl_count + 1;
                    //     squared_sl += value.Pnl;
                    //     sl_avg = (squared_sl / squared_sl_count).toFixed(0);
                    //     if(sl_max < value.max_target_achived){
                    //         sl_max = value.max_target_achived;
                    //     }
                        
                    // };
                    
                    if(value.Pnl > 0){
                        squared_count = squared_count + 1;
                        squared_profit += value.Pnl;
                        profit_avg = (squared_profit / squared_count).toFixed(0);
                        if(profit_max < value.max_target_achived){
                            profit_max = value.max_target_achived;
                        }
                    };
                    
                    if(value.Pnl < 0){
                        squared_sl_count = squared_sl_count + 1;
                        squared_sl += value.Pnl;
                        sl_avg = (squared_sl / squared_sl_count).toFixed(0);
                        if(sl_max < value.max_target_achived){
                            sl_max = value.max_target_achived;
                        }
                        
                    };
                    
                    if(value.status === 'Active'){
                        active_count = active_count + 1;
                        active_profit += value.Pnl; 
                        cur_avg = (active_profit / active_count).toFixed(0);
                        if(cur_max < value.max_target_achived){
                            cur_max = value.max_target_achived;
                        }
                    };
                    
                    if(net_max < value.max_target_achived){
                        net_max = value.max_target_achived;
                    }
                    
                    net_profit += value.Pnl;
                    total_count = total_count + 1;
                    
                    $.each(value.orders, function(key,value){
                        totalInvestment += value.total_inv;
                        console.log(totalInvestment);
                    });
                    
                    net_avg = (net_profit / total_count).toFixed(0);
    
                });
                
                totalProfit_pers = ((squared_profit / totalInvestment) * 100).toFixed(2);
                totalLoss_pers = ((squared_sl / totalInvestment) * 100).toFixed(2);
                
                net_profit_pers = ((net_profit/ totalInvestment) * 100).toFixed(2);
                
                if(net_profit < 0 ){
                    $('#net_profit').css({'color':'red'});
                    $('#net_profit_pers').css({'color':'red'});
                }else{
                    $('#net_profit').css({'color':'green'});
                    $('#net_profit_pers').css({'color':'green'});
                };
                
                if(active_profit < 0 ){
                    $('#active_profit').css({'color':'red'});
                    $('#cur_avg').css({'color':'red'});
                    $('#active_count').css({'color':'red'});
                }else{
                    $('#active_profit').css({'color':'green'});
                    $('#cur_avg').css({'color':'green'});
                    $('#active_count').css({'color':'green'});
                };
                
                $('#squared_profit').append('₹ '+squared_profit.toFixed(2));
                $('#squared_count').append(squared_count);
                
                $('#active_profit').append('₹ '+active_profit.toFixed(2));
                $('#active_count').append(active_count);
                
                $('#squared_sl').append('₹ '+squared_sl.toFixed(2));
                $('#squared_sl_count').append(squared_sl_count);
                
                $('#totalProfit_pers').append(totalProfit_pers+'%');
                $('#totalLoss_pers').append(totalLoss_pers+'%');
                
                $('#net_profit').append('₹ '+net_profit.toFixed(2));
                $('#net_profit_pers').append(net_profit_pers+'%');
                
                $('#total_count').append(total_count);
                
                $('#cur_max').append(cur_max);
                $('#net_max').append(net_max);
                $('#profit_max').append(profit_max);
                $('#sl_max').append(sl_max);
                
                $('#net_avg').append(net_avg);
                $('#cur_avg').append(cur_avg);
                $('#profit_avg').append(profit_avg);
                $('#sl_avg').append(sl_avg);
                
			},
			error: function () {
				console.log('Error');
			}
		});
	}
});
</script>

<script>
	$(document).ready(function(){
	    
		$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
	realTime();	
	
	function realTime() {
	    
		$.ajax({
			type:'get',
			url:'{{ route('basketname.data') }}',
			data:{

				'_token':"{{ csrf_token() }}",
			},
			success: function (result) {
    			 var data = result.data;
    			 $('#basketNames').append('<option value="">--Select Basket--</option>')
			     $.each(data, function(key,value){
			         $('#basketNames').append('<option value="'+value.basket_name+'">'+value.basket_name+'</option>')
			     });
			},
			error: function () {
				console.log('Error');
			}
		});
	}
});
</script>


@endsection