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
                    <a href="#" data-toggle="modal" id ="dateshow"  data-target="#modalDate"class="btn-sm btn-success float-left mr-3">Download Basket</a>
                    <a href="#" data-toggle="modal" id ="dateshow"  data-target="#modalDateOrder"class="btn-sm btn-success float-left">Download Order</a>


                    <a href="{{route('basket.create')}}" class="btn btn-primary btn-round float-right">Create Basket</i>

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
                                    <!--<th><b>Updated At</b></th>-->
                                    <th><b>Order Type</b></th>
                                    <th><b>Init Target</b></th>
									<th><b>Target Strike</b></th>
									<th><b>Stop Loss</b></th>
									<th><b>Current Target</b></th>
									<th><b>Max Trend</b></th>
									<th><b>Status</b></th>
									<th><b>Total PNL</b></th>
                                    <!--<th><b> Scheduled Exc</b></th>-->
                                    <!--<th><b> Scheduled_sq Off</b></th>-->
                                    <!--<th><b> Recorring</b></th>-->
                                    <!--<th><b> WeekDays</b></th>-->
                                    <!--<th><b> Strategy</b></th>-->
                                    <!--<th><b> qty</b></th>-->
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


{{-- Date Model --}}
<div class="modal fade" id="modalDate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLable" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Your Data?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  action ="{{route('download')}}" method="get"  id="myForm" class="pull-left" >
                {{ csrf_field() }}
            <div class="modal-body" >
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <label>From</label>
                    <input type="date" class="form-control input-sm" id="form" name="from" required/>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <label>To</label>
                    <input type="date" class="form-control input-sm" id="to" name="to" required/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id ="excel_download_id"  data-dismiss="modal">Cancel</button>
                <button type="submit"  id="excel_download_hide" class="btn btn-danger ml-2"><i class="fa fa-download"></i>Download</button>
            </div>
        </form>

        </div>
    </div>
</div>

{{-- Date Model Order --}}
<div class="modal fade" id="modalDateOrder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLable" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Your Date?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form  action ="{{route('downloadorder')}}" method="get"  id="myForm" class="pull-left" >
                {{ csrf_field() }}
            <div class="modal-body" >
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <label>From</label>
                    <input type="date" class="form-control input-sm" id="form" name="from" required/>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <label>To</label>
                    <input type="date" class="form-control input-sm" id="to" name="to" required/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id ="excel_download_id"  data-dismiss="modal">Cancel</button>
                <button type="submit"  id="order_download_hide" class="btn btn-danger ml-2"><i class="fa fa-download"></i>Download</button>
            </div>
        </form>

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
	setInterval(function(){
		realTime();	
	},1000);
	function realTime() {
		$.ajax({
			type:'get',
			url:'{{ route( 'basket.data' ) }}',
			data:{
				'_token':"{{ csrf_token() }}",
			},
			success: function (result) {
			    $('#basketList').html('');
			    
				$.each(result.data, function (key, value){
				        var created  = new Date(value.created_at);
				        created_at = created.toLocaleString("en-US")
				        var updated = new Date(value.updated_at);
				        updated_at = updated.toLocaleString("en-US");
				        var bskStatus = value.status;
				        
				    $("#basketList").append('<tr id="record" style="background-color:rgb(252, 252, 252)"><td>'+value.basket_name+'</td><td>'+created_at+'</td><td>'+value.intra_mis+'</td><td>'+value.init_target+'</td><td>'+value.target_strike+'</td><td>'+value.stop_loss+'</td><td>'+value.prev_current_target+'</td><td>'+value.max_target_achived+'</td><td>'+value.status+'</td><td>'+value.Pnl.toFixed(2)+'</td><td><button class= "btn btn-warning edit_data" id="show" data-id='+value.id+'> View </button>&nbsp;<button class= "btn btn-success" id="squareoffdata" data-id='+value.id+'> SquareOff </button></td></tr>');

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
<script>
    $(document).ready(function(){
        $("#dateshow").click(function(){
            $("#modalDate").modal('show');
        });  
        
        $("#excel_download_id").click(function(){
          $("#modalDate").modal('hide');
          $("#myForm").trigger('reset');
           //   document.getElementById("myForm").reset();
        });
        $("#excel_download_hide").click(function(){
          $("#modalDate").modal('hide');
        }); 
        
        $("#order_download_hide").click(function(){
          $("#modalDateOrder").modal('hide');
        });   
    });
    
    @if(Session::has('success'))
        swal("Success","{!!  Session::get('success') !!}","success",{
        button:"Ok",
    })
    @endif
    @if(Session::has('error'))
        swal("Error","{!!  Session::get('error') !!}","error",{
        button:"Ok",
    })
    @endif    
    
</script>



@endsection

