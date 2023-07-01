@extends('layouts.master')
@section('title')
strategy
@endsection

@section('content')     
<br><br>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 "> 
			<div class="card"> 
				<div class="card-body">
					<div class="d-flex justify-content-between">
						<div class="col-xs-6 col-sm-4 col-md-4">
							<label  ><b style="color: rgb(155, 155, 155)">Investment</b></label>
							<div class="mt-2" style="color: rgb(0, 0, 0)" ><b>Rs. &nbsp;</b><span style="color: rgb(0, 0, 0); font-weight:700" id="total"></span></div>
						</div>
						<div class="col-xs-6 col-sm-4 col-md-4">
							<label ><b style="color: rgb(155, 155, 155)">Current</b></label>
							<div class="mt-2" style="color: rgb(0, 0, 0)" ><b>Rs. &nbsp;</b><span style="color: rgb(0, 0, 0); font-weight:700" id="current"></span></div>
						</div>
					</div>
					<hr>
						<div class="d-flex justify-content-between">
							<div class="col-md-6  ">
								<label ><b style="font-size: 15px"> TOTAL P&L</b></label>
							</div>
							<div class="col-md-6  ">
								<h5><span id="totalPnl" style="font-size: 18px; color:rgb(72, 189, 18); font-weight:700"></span>&nbsp<span id="pnlPers" style="font-size: 15px; color:rgb(72, 189, 18)"></span></h5>
							</div>
					
					  </div>
					  <div>
						<hr>
						 <div id="qty"><div>
						
					  					 
				</div>
					
				</div>	
					
			</div>
		</div>

	</div>
		{{-- <div class="col-md-12 ">  
			<div class="card"> 
				<div class="card-body"> --}}
</div>

@endsection
@section('scripts')
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
	function realTime() {
		$.ajax({
			type:'get',
			url:'{{ route( 'holdingslist.data' ) }}',
			data:{
				'id':id,
			},
			success: function (result) {
				$('#qty').html('');
				$('#total').html('');
				var totalinv = 0;
				var current = 0;
				var totalPnl = 0;
				var pnlPers = 0;
				var orderPnlPers = 0;
				var strikePnl = 0;
				// Create our number formatter.
				var formatter = new Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: 'INR',

				// These options are needed to round to whole numbers if that's what you want.
				minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
				//maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
				});
				$.each(result.order, function (key, value){
				// 	console.log(value);
				totalinv += value.total_inv
				totalPnl += value.pnl
				pnlPers = totalPnl / totalinv 
				orderPnlPers = value.pnl / value.total_inv - 1
				
				if(value.order_type == "Buy"){
				    strikePnl = value.ltp / value.order_avg_price - 1
				}else{
				    strikePnl = value.order_avg_price / value.ltp - 1
				}
				
				if(totalPnl < 0){
				    $('#totalPnl').css({'color':'red'});
				}else{
				    $('#totalPnl').css({'color':'rgb(72, 189, 18)'}); 
				}
				
				if(pnlPers < 0){
				    $('#pnlPers').css({'color':'red'});
				}else{
				    $('#pnlPers').css({'color':'rgb(72, 189, 18)'}); 
				}
                
				$('#qty').append('<div class="d-flex justify-content-between" ><div class="col-md-4 col-xl-12"><span >'+value.qty+' Qty</span><label>.</label><label>&nbsp; Avg Price '+value.order_avg_price.toFixed(2)+'</label><label class="float-right"><b id="strikePnl" style="font-size: 13px; color:rgb(72, 189, 18)">'+strikePnl.toFixed(2)+'%</b></label></div></div><br><div class="d-flex justify-content-between"><div class="col-md-4 col-xl-12"><label><b style="color: black; font-size:13px">'+value.basket_name +' | ' +value.token_name+' - '+value.order_type+'</b></label><label class="float-right"><b id="pnl" style="font-size: 13px; color:rgb(72, 189, 18)">'+formatter.format(value.pnl)+'</b></label></div></div><br><div class="d-flex justify-content-between"><div class="col-md-4 col-xl-12"><label>Invested : <span style="color: rgb(0, 0, 0)"><b>'+value.total_inv+'</b></span></label><label class="float-right">LTP : <span style="color: rgb(0, 0, 0)"><b>'+value.ltp.toFixed(2)+'</b></span></label></div></div></div><hr>')
				})
				
				$('#total').html(formatter.format(totalinv));	
				$('#current').html(formatter.format(totalPnl + totalinv));
				$('#totalPnl').html(formatter.format(totalPnl));
				$('#pnlPers').html(pnlPers.toFixed(3)+'%');
				// $("#strikePnl").style.color = 'blue';


			},
			error: function () {
				console.log('Error');
			}
		});
	}
});
</script>

<script>
	$(document).on("click", "#deleteRecord", function(){
		var id = $(this).attr("data-id");
		let url = '{{route('oreder.exitprice')}}'
		console.log(url);
		$.ajax({
			url: url + '/' + id,
			type: 'Post',
			dataType: "JSON",
			data:{
				"id":id,
				"_token": "{{ csrf_token() }}"},
			success: function (data)
			{
				alert(data);
				// location.reload();
				// $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
			}
        });           
	});
</script>
@endsection