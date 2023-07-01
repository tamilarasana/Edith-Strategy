@extends('layouts.master')

@section('title')
Basket
@endsection

@section('content')
<br><br>
<div class="container-fluid"> 
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-header">
                    <p class="card-description">E.D.I.T.H AI Trading Software.</p>
                    <a href="{{route('webhook.create')}}" class="btn btn-primary btn-round float-right">Create Web Hook</i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <span id="result"></span>
                        <table  class=" table table-bordered  "  >
                            <thead>
                                <tr style="background-color:rgb(242, 242, 242)">
                                    <th><b> Basket Name</b></th>
                                    <th><b>Created At</b></th>
                                    <th><b>Updated At</b></th>
                                    <th><b>Init Target</b></th>
									<th><b>Target Strike</b></th>
									<th><b>Stop Loss</b></th>
									{{-- <th><b>Current Target</b></th> --}}
									{{-- <th><b>Max Trend</b></th> --}}
									<th><b>Status</b></th>
									{{-- <th><b>Total PNL</b></th> --}}
                                    <!--<th><b> Scheduled Exc</b></th>-->
                                    <!--<th><b> Scheduled_sq Off</b></th>-->
                                    <!--<th><b> Recorring</b></th>-->
                                    <!--<th><b> WeekDays</b></th>-->
                                    <!--<th><b> Strategy</b></th>-->
                                    <th><b> qty</b></th>
                                    <th><b>Action</b></th>
                                </tr>
                            </thead>    
                            <tbody id="basketList">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>&nbsp;
        </div>
    </div>
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
	
		realTime();	
	function realTime() {
		$.ajax({
			type:'get',
			url:'{{ route( 'hookdata.data' ) }}',
			data:{
				'_token':"{{ csrf_token() }}",
			},
			success: function (result) {
			    $('#basketList').html('');
			    
				$.each(result.data, function (key, value){
				        console.log(value);
				        var created  = new Date(value.created_at);
				        created_at = created.toLocaleString("en-US")
				        var updated = new Date(value.updated_at);
				        updated_at = updated.toLocaleString("en-US");
				        var bskStatus = value.status;
				        
				        
				        
				    $("#basketList").append('<tr id="record" style="background-color:rgb(252, 252, 252)"><td>'+value.basket_name+'</td><td>'+created_at+'</td><td>'+updated_at+'</td><td>'+value.init_target+'</td><td>'+value.target_strike+'</td><td>'+value.stop_loss+'</td><td>'+value.status+'</td><td>'+value.qty+'</td><td><button class= "btn btn-warning edit_data" id="show" data-id='+value.id+'> Edit Basket </button>&nbsp;<button class= "btn btn-success" id="squareoffdata" data-id='+value.id+'> View Webhook </button></td></tr>');
				    
				    if(bskStatus == "Active"){
				            $("#record").css({'background-color':'rgb(230, 255, 230)'});
				            $("#record").css({'font-weight':'700'});
				            $("#record").css({'color':'black'});
				    }
				})

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
        $('body').on("click", ".edit_data", function(){
            var id = $(this).attr("data-id");
            var base_url = window.location.origin;
            window.location.href = base_url + "/holdings?basket_id=" + id + "";                      
        })
    });
</script>

<script>
	$(document).on("click", "#squareoffdata", function(){
		var id = $(this).attr("data-id");
        let url = '{{route('oreder.exitprice')}}'
        if(confirm("Are You sure want to SquareOff !")){
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
        }		        
	});
</script>



@endsection

