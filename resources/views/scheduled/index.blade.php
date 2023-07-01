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
                        <a href="{{ route('scheduled.create') }}" class="btn btn-primary btn-round float-right">Create
                            Scheduled Basket</i>
                        </a>
                    </div>
                    <div class="card-body">
                        <span id="result"></span>
                        <table class=" table table-bordered ">
                            <thead>
                                <tr style="background-color:rgb(242, 242, 242)">
                                    <th><b> Basket Name</b></th>
                                    <th><b>Created At</b></th>
                                    <th><b>Start</b></th>
                                    <th><b>Stop</b></th>
                                    <th><b>Recurring</b></th>
                                    <th><b>Order Type</b></th>
                                    <th><b>Init Target</b></th>
                                    <th><b>Target Strike</b></th>
                                    <th><b>Stop Loss</b></th>
                                    <th><b>Status</b></th>
                                    <th><b>View Orders</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedulebasket as $item)
                                    <tr>
                                        <td>{{ $item->scbasket_name }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->scheduled_exec }}</td>
                                        <td>{{ $item->scheduled_sqoff }}</td>
                                        <td>{{ $item->recurring }}</td>
                                        <td>{{ $item->indices }}</td>
                                        <td>{{ $item->init_target }}</td>
                                        <td>{{ $item->target_strike }}</td>
                                        <td>{{ $item->stop_loss }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            {{-- <a href="{{route('webhook.edit', $item->id)}}"  class="btn-sm btn-info">Edit Basket</a>&nbsp;
                                        <a href="javascript:void(0)" class= "btn-sm btn-success" id="webapi"> View Webhook </a> --}}
                                            <a href="javascript:void(0)" class="btn-sm btn-success" id="webapi"
                                                data-id="{{ $item->id }}"> View basket </a>&nbsp;
                                            <a href="javascript:void(0)" class="btn-sm btn-danger" id="stop"
                                                data-id="{{ $item->id }}"> Stop </a>&nbsp;

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

    <div class="modal fade" data-keyboard="false" data-backdrop="static" id="ajaxModel"
    aria-hidden="true">
            <div class="modal-dialog">
            <form method="POST"  name= "add_name" id='myform'>
                <div style="width:710px; background-color:white" class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading">SELECT THE STRIKE TO EXECUTE</h4>
                    </div>
                    <div class="modal-body">
                        <select id="mySelect" class="browser-default custom-select ">
                            <option selected value="ATM">ATM</option>
                        </select>
                        <br>
                        <hr>
                        <div class="autocomplete-items" id="results"></div>
                    </div>
                    <br>
                    <table class="table  table-condensed" id="user_table">
                        <thead>
                            <tr>
                            </tr>
                        </thead>
                        <tbody id="s_body">
                        </tbody>
                    </table>
                    <hr>
                    <div class="col-sm float-sm-right">
                        <button id="cancel" type="button" class="btn btn-warning">Cancel</button>
                        <button id="save" type="button" class="btn btn-success">Save</button>
                        <span class=" float-right">*X - Represents Last Trading Price</span>
                    </div>
                    <hr>
                </div>
            <form>
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
				        var created  = new Date(value.created_at);
				        created_at = created.toLocaleString("en-US")
				        var updated = new Date(value.updated_at);
				        updated_at = updated.toLocaleString("en-US");
				        var bskStatus = value.status;
				    $("#basketList").append('<tr id="record"><td >'+value.basket_name+'</td><td>'+created_at+'</td><td>'+value.intra_mis+'</td><td>'+value.init_target+'</td><td>'+value.target_strike+'</td><td>'+value.stop_loss+'</td><td>'+value.status+'</td><td>'+value.qty+'</td><td><button class= "btn btn-warning edit_data" id="show" data-id='+value.id+'> Edit Basket </button>&nbsp;<button class= "btn btn-success" id="webapi" data-id='+value.webhook.post_api+'> View Webhook </button></td></tr>');
				    
				    if(bskStatus == "Active"){
				            $("#record").css({'background-color':'rgb(230, 255, 230)'});
				            $("#record").css({'font-weight':'700'});
				            $("#record").css({'color':'black'});
				    }
				})

			},
			error: function () {

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
      BankNifty = [
        {
            'id':1,
            'value':-1500,
            'name':'-1500'
        },
        {
            'id':2,
            'value':-1400,
            'name':'-1400'
        },
        {
            'id':3,
            'value':-1300,
            'name':'-1300'
        },
        {
            'id':4,
            'value':-1200,
            'name':'-1200'
        },
        {
            'id':5,
            'value':-1100,
            'name':'-1100'
        },
        {
            'id':6,
            'value':-1000,
            'name':'-1000'
        },
        {
            'id':7,
            'value':-900,
            'name':'-900'
        },
        {
            'id':8,
            'value':-800,
            'name':'-800'
        },
        {
            'id':9,
            'value':-700,
            'name':'-700'
        },
        {
            'id':10,
            'value':-600,
            'name':'-600'
        },
        {
            'id':11,
            'value':-500,
            'name':'-500'
        },
        {
            'id':12,
            'value':-400,
            'name':'-400'
        },
        {
            'id':13,
            'value':-300,
            'name':'-300'
        },
        {
            'id':14,
            'value':-200,
            'name':'-200'
        },
        {
            'id':15,
            'value':-100,
            'name':'-100'
        },
        {
            'id':16,
            'value':'ATM',
            'name':'ATM'
        },
        {
            'id':17,
            'value':100,
            'name':'100'
        },
        {
            'id':18,
            'value':200,
            'name':'200'
        },
        {
            'id':19,
            'value':300,
            'name':'300'
        },
        {
            'id':20,
            'value':400,
            'name':'400'
        },
        {
            'id':21,
            'value':500,
            'name':'500'
        },
        {
            'id':22,
            'value':600,
            'name':'600'
        },
        {
            'id':23,
            'value':700,
            'name':'700'
        },
        {
            'id':24,
            'value':800,
            'name':'800'
        },
        {
            'id':25,
            'value':900,
            'name':'900'
        },
        {
            'id':26,
            'value':1000,
            'name':'1000'
        },
        {
            'id':27,
            'value':1100,
            'name':'1100'
        },
        {
            'id':28,
            'value':1200,
            'name':'1200'
        },
        {
            'id':29,
            'value':1300,
            'name':'1300'
        },
        {
            'id':30,
            'value':1400,
            'name':'1400'
        },
        {
            'id':31,
            'value':1500,
            'name':'1500'
        }
    ]

    Nifty = [
        {
            'id':1,
            'value':-500,
            'name':'-500'
        },
        {
            'id':2,
            'value':-450,
            'name':'-450'
        },
        {
            'id':3,
            'value':-400,
            'name':'-400'
        },
        {
            'id':4,
            'value':-350,
            'name':'-350'
        },
        {
            'id':5,
            'value':-300,
            'name':'-300'
        },
        {
            'id':6,
            'value':-250,
            'name':'-250'
        },
        {
            'id':7,
            'value':-200,
            'name':'-200'
        },
        {
            'id':8,
            'value':-150,
            'name':'-150'
        },
        {
            'id':9,
            'value':-100,
            'name':'-100'
        },
        {
            'id':10,
            'value':-50,
            'name':'-50'
        },
        {
            'id':11,
            'value':'ATM',
            'name':'ATM'
        },
        {
            'id':12,
            'value':50,
            'name':'-50'
        },
        {
            'id':13,
            'value':100,
            'name':'100'
        },
        {
            'id':14,
            'value':150,
            'name':'150'
        },
        {
            'id':15,
            'value':200,
            'name':'200'
        },
        {
            'id':16,
            'value':250,
            'name':'250'
        },
        {
            'id':17,
            'value':300,
            'name':'300'
        },
        {
            'id':18,
            'value':350,
            'name':'350'
        },
        {
            'id':19,
            'value':400,
            'name':'400'
        },
        {
            'id':20,
            'value':450,
            'name':'450'
        },
        {
            'id':21,
            'value':500,
            'name':'500'
        },
       
    ]
</script>
    <script>
        var basketId = 0;
        var count = 0;
        $(document).on("click", "#webapi", function() {
            $('#ajaxModel').modal('show');
            var id = $(this).attr("data-id");
            var url = "{{ url('secheduledshow') }}";
            var dltUrl = url + "/" + id;
            $.ajax({
                url: dltUrl,
                type: "GET",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    var niftytype = data.indices;
                        $(document).ready(function(){
                            if(niftytype == "banknifty"){
                                $('#mySelect').html('');
                                $.each(BankNifty, function(value, dta){
                                    $('#mySelect').append('<option value='+dta.value+'>'+dta.name+'</option>');
                                })
                            }else if(niftytype == "nifty"){
                                 $('#mySelect').html('');
                                 $.each(Nifty, function(value, dta){
                                $('#mySelect').append('<option  value='+dta.value+'>'+dta.name+'</option>');
                              })
                             }
                         });
                    var datas = data;
                    basketId = datas.id;
                    $('#results').html('');
                        var id = datas.id;
                        var data = JSON.parse(datas.orders);
                        var orders = data;
                        $.each(orders, function(key, value){
                            count + 1;
                            var types = value.type;
                            var strick_qty = value.strick_qty;
                            var strick_type = value.strick_type;
                            var order_type = value.order_type;
                            var strike_expiry = value.strike_expiry;
                            html = '<tr>';
                            // html += '<td><div class="mt-1"><input type="hidden" style="height:30px; width:100px" name="data['+count+'][id]"  class="form-control " value="' +id+'" required /></div></td>';
                            html +='<td><label ><b>X</b></label></td>';
                            html +='<td><label class="mt-1"><b>Token Strike</b></label><div class="mt-1"><input type="text" style="height:30px; width:80px" name="type"  value="'+types+'" class="form-control input-border-bottom Strike"  readOnly ></input></div></td>';
                            html +='<td><label class="mt-1"><b>Qty</b></label><div class="mt-1"><input type="text" style="height:30px; width:80px" id="qyt" name="strick_qty" value="'+strick_qty+'"></input></div></td>';
                            html +='<td><label class="mt-1"><b>Strike Type</b></label><div class="mt-1"><select  style="height:30px; width:80px" class="selectpicker"  name="strick_type"><option value="'+strick_type+'">'+strick_type +'</option><option value="CE">CE</option><option value="PE">PE</option></select></div></td>';
                            html +='<td><label class="mt-1 "><b>Order Type</b></label><div class="mt-1"><select style="height:30px; width:80px" class="selectpicker" name="order_type" ><option value="'+order_type+'">'+order_type+'</option><option value="Buy">Buy</option><option  value="Sell">Sell</option></select></div></td>';
                            html +='<td><label class="mt-1 "><b>Strike Expiry</b></label><div class="mt-1"><select style="height:30px; width:80px" onclick="strikEexpiry()"  class="strikeexpiry strikeexp" name="strike_expiry" ><option value="'+strike_expiry+'">'+strike_expiry+'</option></select></div></td>';
                            html +='<td><button type="button" name="remove"  class="btn btn-danger btn-sm remove">X</button></td></tr>';
                            html += '</tr>';
                            $('#s_body').append(html);
                        });


                        $(document).on('click', '.remove', function() {
                            count--;
                            $(this).closest("tr").remove();
                        });
                    
                    // var orders = JSON.parse(data);
                    // console.log(orders[0].order_type)

                }
            });
        });


            $('#mySelect').on('change', function() {
                var value = $(this).val();
                count + 1;
                            html ='<tr>';
                            html +='<td><label ><b>X</b></label></td>';
                            html +='<td><label class="mt-1"><b>Token Strike</b></label><div class="mt-1"><input type="text" style="height:30px; width:80px" name="type"  value="'+value+'" class="form-control input-border-bottom"  readOnly ></input></div></td>';
                            html +='<td><label class="mt-1"><b>Qty</b></label><div class="mt-1"><input type="text" style="height:30px; width:80px" id="qyt" name="strick_qty"></input></div></td>';
                            html +='<td><label class="mt-1"><b>Strike Type</b></label><div class="mt-1"><select  style="height:30px; width:80px" class="selectpicker"  name="strick_type"><option value="emptyData">-Select-</option><option value="CE">CE</option><option value="PE">PE</option></select></div></td>';
                            html +='<td><label class="mt-1 "><b>Order Type</b></label><div class="mt-1"><select style="height:30px; width:80px" class="selectpicker"  name="order_type" ><option value="empty">-Select-</option><option value="Buy">Buy</option><option  value="Sell">Sell</option></select></div></td>';
                            html +='<td><label class="mt-1 "><b>Strike Expiry</b></label><div class="mt-1"><select style="height:30px; width:80px" onclick="strikEexpiry()"  class="strikeexpiry strikeexp" name="strike_expiry" ><option value="strikeexpempty">--Select--</option></select></div></td>';
                            html +='<td><button type="button" name="remove"   class="btn btn-danger btn-sm remove">X</button></td></tr>';
                            html += '<td></td></tr>';
                            $('#s_body').append(html);                    
            });
        
        $('#cancel').click(function() {
            var token_items = decodeURIComponent($('form').serialize());
            $('#ajaxModel').modal('hide');
            $('#s_body').html('');
        });

        $('#save').click(function() {
            var token_items = decodeURIComponent($('form').serialize()); 
            var qty = document.getElementById('qyt').value; ;
            var id  = basketId;
            var data = $('form').serializeArray();            
            console.log(data);
            var url = "{{ url('scheduledupdate') }}";
            var dltUrl = url + "/" + id;
            if(token_items.search("empty") > 0 || (token_items.search("emptyData") > 0) ||(token_items.search("strikeexpempty") > 0)|| qty == " " ){
                alert("Please Fill The All Details..!!");
            }else{
                $.ajax({
                        url: dltUrl,
                        type: "Post",
                        data: {
                            id:id,
                            data:data,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            location.reload();
                            $('#ajaxModel').modal('hide');

                        }
                    });
            }                   
        });
    </script>
    <script>  
    function strikEexpiry() {
        $.ajax({
            url: "{{ route('expiry') }}",
            method: "GET",
            data:{
                 '_token':"{{ csrf_token() }}",
            },
            success: function (result) {
            $('.strikeexp').html();
                $.each(result, function(key, value){
                    $('.strikeexp').append('<option value='+value.strike_expiry+'>'+value.strike_expiry+'</option>');
                })
            }
        });
    }
</script>
    {{-- <script>
        $(document).ready(function() {
            $('#mySelect').on('change', function() {
                var value = $(this).val();
                var count = 0;
                $('#results').html('');
                dynamic_field(count, value)
                function dynamic_field(count, value){
                    count++;
                            html ='<tr>';
                            html +='<td><label ><b>X</b></label></td>';
                            html +='<td><label class="mt-1"><b>Token Strike</b></label><div class="mt-1"><input type="text" style="height:30px; width:80px" name="data['+count +'][type]"  value="'+value+'" class="form-control input-border-bottom"  readOnly ></input></div></td>';
                            html +='<td><label class="mt-1"><b>Qty</b></label><div class="mt-1"><input type="text" style="height:30px; width:80px" id="qyt" name="data['+count +'][strick_qty]"></input></div></td>';
                            html +='<td><label class="mt-1"><b>Strike Type</b></label><div class="mt-1"><select  style="height:30px; width:80px" class="selectpicker"  name="data['+count+'][strick_type]"><option value="emptyData">-Select-</option><option value="CE">CE</option><option value="PE">PE</option></select></div></td>';
                            html +='<td><label class="mt-1 "><b>Order Type</b></label><div class="mt-1"><select style="height:30px; width:80px" class="selectpicker"  name="data['+count+'][order_type]" ><option value="empty">-Select-</option><option value="Buy">Buy</option><option  value="Sell">Sell</option></select></div></td>';
                            if (count > 0) {
                                html +=
                                    '<td><button type="button" name="remove"   class="btn btn-danger btn-sm remove">X</button></td></tr>';
                                $('#s_body').append(html);
                            } else {
                                html += '<td></td></tr>';
                                $('#s_body').html(html);
                            }
                        }                     
            });
        });
    </script> --}}

    <script>
        $(document).on("click", "#stop", function() {
            var id = $(this).attr("data-id");
            let url = '{{ route('oreder.exitprice') }}'
            if (confirm("Are You sure want to Stop !")) {
                $.ajax({
                    url: url + '/' + id,
                    type: 'Post',
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                    location.reload();
                    // $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
                }
                });
            }
        });
    </script>
@endsection


