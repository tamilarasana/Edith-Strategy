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
                        <span id="result"></span>
                        <table  class=" table table-bordered  "  >
                            <thead>
                                <tr style="background-color:rgb(242, 242, 242)">
                                    <th><b> Basket Name</b></th>
                                    <th><b>Created At</b></th>
                                    <th><b>Order Type</b></th>
                                    <th><b>Init Target</b></th>
									<th><b>Target Strike</b></th>
									<th><b>Stop Loss</b></th>
									<th><b>Status</b></th>
                                    <th><b> qty</b></th>
                                    <th><b>Action</b></th>
                                </tr>
                            </thead>    
                            <tbody >
                                @foreach ($data as $item)
                                <tr>
                                    <td class="hovermodel  CellWithComment" data-id="{{ $item->id}}" ><span class="CellComment"><p class="hover"></p></span>{{ $item->basket_name }}</td>
                                    <td class="hovermodel CellWithComment" data-id="{{ $item->id}}" ><span class="CellComment"><p class="hover"></p></span>{{ $item->created_at }}</td>
                                    <td class="hovermodel CellWithComment" data-id="{{ $item->id}}"><span class="CellComment"><p class="hover"></p></span>{{ $item->intra_mis }}</td>
                                    <td class="hovermodel CellWithComment" data-id="{{ $item->id}}"><span class="CellComment"><p class="hover"></p></span>{{ $item->init_target }}</td>
                                    <td class="hovermodel CellWithComment" data-id="{{ $item->id}}"><span class="CellComment"><p class="hover"></p></span>{{ $item->target_strike }}</td>
                                    <td class="hovermodel CellWithComment" data-id="{{ $item->id}}"><span class="CellComment"><p class="hover"></p></span>{{ $item->stop_loss }}</td>
                                    <td class="hovermodel CellWithComment" data-id="{{ $item->id}}"><span class="CellComment"><p class="hover"></p></span>{{ $item->status }}</td>
                                    <td class="hovermodel CellWithComment" data-id="{{ $item->id}}"><span class="CellComment"><p class="hover"></p></span>{{ $item->qty }}</td>
                                    <td>
                                        <a href="{{route('webhook.edit', $item->id)}}"  class="btn-sm btn-info">Edit Basket</a>&nbsp;
                                        <a href="javascript:void(0)" class= "btn-sm btn-success" id="webapi"  data-id="{{ $item->webhook->post_api}}"> View Webhook </a>&nbsp;
                                        {{-- <button class= "btn-sm     btn-success" id="webapi"  data-id="{{ $item->webhook->post_api}}"> View Webhook </button> --}}
                                    </td>
                                <tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>&nbsp;
        </div>
    </div>
</div>
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div style="width:550px; background-color:white" class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="modelHeading">Web Hook URL</h4>
            </div>
        <div class="modal-body">
            <div class="autocomplete" style="width:300px;">
                <div class="col "> 
                    <label><b>Web Hook URL</b></label>
                    <p style="padding: 10px; margin-top:20px; font-size:15px; width:400px; background-color:rgb(230, 230, 230);" id ="webhookapi" ></p>  
                </div>
                <div class="col "> 
                    <label><b>Trading view Message Payload</b></label>
                    <p style="padding: 10px; margin-top:20px; font-size:15px; width:400px; height:100px; background-color:rgb(230, 230, 230);" id ="messagePayload" ></p>  
                </div>
            </div>
            <br>
            <hr>
        </div>
    </div>
</div>

@endsection
@section('scripts')

{{-- <script>
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
				        console.log(value.intra_mis);
				    $("#basketList").append('<tr id="record"><td >'+value.basket_name+'</td><td>'+created_at+'</td><td>'+value.intra_mis+'</td><td>'+value.init_target+'</td><td>'+value.target_strike+'</td><td>'+value.stop_loss+'</td><td>'+value.status+'</td><td>'+value.qty+'</td><td><button class= "btn btn-warning edit_data" id="show" data-id='+value.id+'> Edit Basket </button>&nbsp;<button class= "btn btn-success" id="webapi" data-id='+value.webhook.post_api+'> View Webhook </button></td></tr>');
				    
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
</script> --}}

{{-- <script>
    $(document).ready(function(){
        $('body').on("click", ".edit_data", function(){
            var id = $(this).attr("data-id");
            var base_url = window.location.origin;
            window.location.href = base_url + "/holdings?basket_id=" + id + "";                      
        })
    });
</script> --}}

<script>
	$(document).on("click", "#webapi", function(){
		var id = $(this).attr("data-id");
        var segment_value = $(this).attr("data-segments");
        $('#ajaxModel').modal('show'); 
        $('#webhookapi').html('');
        $("#webhookapi").append(id); 
        $('#messagePayload').html('');
        $("#messagePayload").append('{"strike":"25000","option_type":"PE","segment":"fno","exe_type":"Buy"}');
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
                }
            });   
        }		        
	});

    $( ".hovermodel" ).on('mousemove',function() {
        var id = $(this).attr("data-id");
        $('.hover').show();
        let url = '{{route('statusview.status')}}'
            $.ajax({
                url: url + '/' + id,
                type: 'Get',
                dataType: "JSON",
                data:{
                    "id":id,
                    "_token": "{{ csrf_token() }}"},
                success: function (webhookStatus){
                    $(".hover").text(webhookStatus.webhookStatus); 
                }
            });   
    });

    $( ".hovermodel" ).mouseout(function() {
        $('.hover').hide();
    });
</script>

<style>   
.CellWithComment{
  position:relative;
}
.CellComment{
  display:none;
  position:absolute; 
  z-index:100;
  border:1px;
  background-color:white;
  border-style:solid;
  /* border-width:0.5px; */
  border-color:green;
  padding:2px 4px;
  color:green; 
  top:-30px; 
  left:50%;
  transform: translate(-50%);
}
.CellWithComment:hover span.CellComment{
  display:block;
}
</style>

@endsection

