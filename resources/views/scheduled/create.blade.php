@extends('layouts.master')
@section('title')
    Basket
@endsection
@section('content')
    <br><br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    <form action="{{ route('scheduled.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <h3 class="card-description p-1"><b style="color:black">Create New Basket</b></h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Basket Name</b></label>
                                    <input id="inputFloatingLabel" name="scbasket_name" type="text"
                                        class="form-control input-border-bottom" placeholder="Basket Name" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Initial Target </b></label>
                                    <input id="inputFloatingLabel" name="init_target" type="number"
                                        class="form-control input-border-bottom" placeholder="Init Target" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Target Strike </b></label>
                                    <input id="inputFloatingLabel" name="target_strike" type="number"
                                        class="form-control input-border-bottom" placeholder="Target Strike" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Stop Loss </b></label>
                                    <input id="inputFloatingLabel" name="stop_loss" type="number"
                                        class="form-control input-border-bottom" placeholder="Stop Loss" required>
                                </div>

                                <div class="col-sm-4 col-md-4 col-xs-12 mb-3"> 
                                    <label class="mt-3 mb-3"><b> Select Segment </b></label>
                                    <select  class="form-control has-error" name="segments" id="segment" required>
                                        <option value="">Select Segment</option>
                                        <option value="NSE">NSE </option>
                                        <option  id="fno" value="NFO">NFO</option>
                                    </select>
                                </div>
                                <div id="indices" class="col-sm-4 col-md-4 col-xs-12 mb-3"> 
                                    <label class="mt-3 mb-3"><b> Select Indices </b></label>
                                    <select  class="form-control has-error" name="indices" id="indicesOps" required>
                                        <option value="">Select Indices</option>
                                        <option value="banknifty">BANKNIFTY </option>
                                        <option id="fno" value="nifty">NIFTY</option>
                                    </select>
                                </div> 
                                {{-- <div id="intra_mis" class="col-sm-4 col-md-4 col-xs-12 mb-3"> 
                                        <label class="mt-3 mb-3"><b> Orders Type </b></label>
                                        <select  class="form-control has-error" name="order_type" id="intra_misOps" required>
                                            <option value="">Select Order Type</option>
                                            <option value="NRML">NRML</option>
                                            <option value="MIS">MIS</option>
                                        </select>
                                </div> --}}

                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Scheduled?</b></label>
                                    <!-- <input id="inputFloatingLabel" name="scheduled_exec" type="date"
                                        class="form-control input-border-bottom" placeholder="Scheduled Exc" required> -->

                                        <div>
                                        <label class="switch">
                                            <input id="scheduleCheck" name="isScheduled"  type="checkbox">
                                            <span class="slider round"></span>
                                            </label>
                                        </div>
                                </div>

                                <div id="recSch" class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Recurring?</b></label>
                                        <div>
                                        <label class="switch">
                                            <input id="isRecurring" name="isRecurring"  type="checkbox">
                                            <span class="slider round"></span>
                                            </label>
                                        </div>
                                </div>

                                <div id="recStart" class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Scheduled Start</b></label>
                                    <!-- <input id="inputFloatingLabel" name="scheduled_exec" type="date"
                                        class="form-control input-border-bottom" placeholder="Scheduled Exc" required> -->
                                        <input id="scheduled_exec" class="form-control input-border-bottom" name="scheduled_exec" type="time" id="schStart"/>

                                </div>

                                <div id="recStop" class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Scheduled Sq-off</b></label>
                                    <!-- <input id="inputFloatingLabel" name="scheduled_sqoff" type="date"
                                        class="form-control input-border-bottom" placeholder="Scheduled_sq Off" required> -->
                                        <input id="scheduled_sqoff" class="form-control input-border-bottom" name="scheduled_sqoff" type="time" id="schStop"/>
                                </div>
                                <!-- <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Quantity</b></label>
                                    <input id="inputFloatingLabel" name="qty" type="number"
                                        class="form-control input-border-bottom" placeholder="qty" required>
                                </div> -->
                            </div>

                            <div class=" mb-3">
                                <label style="font-size:15px; color:black; font-weight:800">SELECT THE OPTIONS STRATEGY OR
                                    NSE</label>
                                &nbsp;&nbsp;
                                <!-- Button trigger modal -->
                                <a class="btn btn-success" href="javascript:void(0)" id="createNewCustomer"> Create New
                                    STRATEGY</a>
                            </div>
                            <div class="modal fade" data-keyboard="false" data-backdrop="static" id="ajaxModel"
                                aria-hidden="true">
                                <div class="modal-dialog" >
                                    <div style="width:750px; background-color:white" class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="modelHeading">SELECT THE STRIKE TO EXECUTE</h4>
                                        </div>
                                        <div class="modal-body" >
                                            <select  id="mySelect" class="browser-default custom-select ">
                                                <option   selected value="ATM">ATM</option>
                                                
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="-400">-400</option>
                                                <option  value="-300">-300</option>
                                                <option  value="-200">-200</option>
                                                <option  value="-100">-100</option>
                                                <option value="ATM">ATM</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="- 100">-100</option>
                                                <option  value="-400">-400</option>
                                                <option  value="-300">-300</option>
                                                <option  value="-200">-200</option>
                                                <option  value="-100">-100</option>
                                                
                                              </select>
                                              <div class="autocomplete-items" id="results"></div>

                                            {{-- <div class="autocomplete" style="width:300px;">
                                                <input id="myInput" type="text" placeholder="Search Instrument.Ex.Reliance..">
                                                <div class="autocomplete-items" id="results"></div>
                                            </div> --}}
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
                                            <button id="clear" type="button" class="btn btn-primary">Clear</button>
                                            <button id="save" type="button" class="btn btn-success">Save</button>
                                            <span class=" float-right">*X - Represents Last Trading Price</span>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="col-md-3 mb-3 ">
                            <button class="btn btn-secondary" onclick="submitteForm()" id ="savebasket" type="submit">Submit</button>
                            <a href="{{ route('basket.index') }}" type="submit"   class="btn btn-primary me-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

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
            'name':'50'
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

        $(document).ready(function(){
            $('#mySelect').html('');
            $('#mySelect').append('<option  value="0">Please Select the Indices first</option>');
            $("#indicesOps").on('change', function(){
                var value = $(this).val();
                if(value == "banknifty"){
                $('#mySelect').html('');
                    $.each(BankNifty, function(value, dta){
                        $('#mySelect').append('<option value='+dta.value+'>'+dta.name+'</option>');
                    })
                }else if(value == "nifty"){
                    $('#mySelect').html('');
                    $.each(Nifty, function(value, dta){
                        $('#mySelect').append('<option  value='+dta.value+'>'+dta.name+'</option>');
                    })
                }
            })

        });

</script>

<script>
$(document).ready(function() {
            $('#recSch').hide();
            $('#recStop').hide();
            $('#recStart').hide();

            document.getElementById("isRecurring").required = false;
            document.getElementById("scheduled_exec").required = false;
            document.getElementById("scheduled_sqoff").required = false;

            var clicked = false;
            $('#scheduleCheck').click(function() {
                
                if(!clicked){
                    clicked = true;
                    $('#recSch').show();
                    $('#recStop').show();
                    $('#recStart').show();
                    document.getElementById("scheduled_exec").required = true;
                    document.getElementById("scheduled_sqoff").required = true;
                }else{
                    clicked = false; 
                    $('#recSch').hide();
                    $('#recStop').hide();
                    $('#recStart').hide();
                    document.getElementById("scheduled_exec").required = false;
                    document.getElementById("scheduled_sqoff").required = false;
                }

                console.log(clicked)

            });

            // var disableButton = false;
            // document.getElementById("savebasket").disabled = true;
        });

</script>

<script>
 
        // Below code sets format to the
        // datetimepicker having id as
        // datetime
        $('#schStart').datetimepicker({
            format: 'HH:mm'
        });
        $('#schStop').datetimepicker({
            format: 'HH:mm'
        });
    </script>

<script>
    $(document).ready(function() {
        $('#mySelect').on('change', function() {
            var value = $(this).val();
           $('#results').html('');
           $('#results').append(value); 
           count++;
            dynamic_field(count, value);
            $('#results').html('');         
});

    });
</script>
    {{-- <script>
        $(document).ready(function() {
            $('#myInput').keyup(function() {
                var query = $(this).val();
                if ((query != '') && (query.length > 4)) {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('autocomplete') }}",
                        method: "POST",
                        data: {
                            query: query,
                            _token: _token
                        },
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);
                            var len = response.length;
                            $('#results').html('');
                            for (var i = 0; i < len; i++) {
                                var id = response[i]['instrument_token'];
                                var names = response[i]['tradingsymbol'];
                                $('#results').append('<div onClick="on_click(\'' + id +
                                    '\',\'' + names + '\')">' + names + '</div>');
                            }

                        }
                    });
                }
            });

        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $('#createNewCustomer').click(function() {
                $('#ajaxModel').modal('show');
            });
            var disableButton = false;
            document.getElementById("savebasket").disabled = true;
        });

        // function on_click(click_id, token_name) {
        //     var strike_type = token_name.slice(-2);
        //     count++;
        //     dynamic_field(count, click_id, token_name, strike_type);
        //     $('#results').html('');
        // }

        var count = 1;
        function dynamic_field(number, value) {
            html = '<tr>';
            html +='<td><label ><b>X</b></label></td>';
            html +='<td><label class="mt-1"><b>Token Strike</b></label><div class="mt-1"> <input type="text" style="height:30px; width:180px" name="data['+number+'][type]"  class="form-control input-border-bottom"  value="'+value+'" required/></div></td>';
            html +='<td><label class="mt-1"><b>Qty</b></label><div class="mt-1"><input type="text" style="height:30px; width:80px" id="qyt" name="data['+number+'][strick_qty]"></input></div></td>';
            html +='<td><label class="mt-1"><b>Strike Type</b></label><div class="mt-1"><select  style="height:30px; width:80px" class="selectpicker"  name="data['+number+'][strick_type]"><option value="emptyData">-Select-</option><option value="CE">CE</option><option value="PE">PE</option></select></div></td>';
            // html +='<td><label class="mt-1 "><b>Type</b></label><div class="mt-1"><select style="height:30px; width:80px" class="selectpicker" id="test"  name="data['+number+'][type]" ><option value="empty">-Choose Options-</option><option value="atm">ATM</option><option  value="- 100">- 100</option><option  value="+ 100">+ 100</option><option  value="- 200">- 200</option><option  value="+ 200">+ 200</option><option  value="-300">- 300</option><option  value="+ 300">+300</option></select></div></td>';
            html +='<td><label class="mt-1 "><b>Order Type</b></label><div class="mt-1"><select style="height:30px; width:80px" class="selectpicker" id="test"  name="data['+number+'][order_type]" ><option value="empty">-Select-</option><option value="Buy">Buy</option><option  value="Sell">Sell</option></select></div></td>';
            html +='<td><label class="mt-1 "><b>Strike Expiry</b></label><div class="mt-1"><select style="height:30px; width:80px" onclick="strikEexpiry()"  class="strikeexpiry strikeexp" name="data['+number+'][strike_expiry]" ><option value="strikeexpempty">--Select--</option></select></div></td>';
            if (number > 1) {
                html +=
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-sm remove">X</button></td></tr>';
                $('#s_body').append(html);
            } else {

                html += '<td></td></tr>';
                $('#s_body').html(html);
            }
        }


        $(document).on('click', '.remove', function() {
            count--;
            $(this).closest("tr").remove();
        });



        $('#cancel').click(function() {
            var token_items = decodeURIComponent($('form').serialize());      
            $('#ajaxModel').modal('hide');
            console.log($('tbody tr'));
        });

        $('#clear').click(function() {
            $('#s_body').html('');
            document.getElementById("savebasket").disabled = true;
        });

        $('#save').click(function() {
           var token_items = decodeURIComponent($('form').serialize());
           console.log(token_items);
           if(token_items.search("empty") > 0 || (token_items.search("strikeexpempty") > 0)){
            alert("Please Fill the All Details..!!");
            // alert("Please select the Order Type..!!");
           }
        //    else if(token_items.search("token_id") < 0){
        //        alert("Stock Instrument Cannot be empty..!!");
        //    }
           else{
            $('#ajaxModel').modal('hide');
            document.getElementById("savebasket").disabled = false;
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

{{-- 
<script>
    function myFunction() {
    var select = document.getElementById('segment');
    var value = select.options[select.selectedIndex].value;
    var x = document.getElementById("indices");
    var y = document.getElementById("equityStrategy");
    x.style.display = "none";
    y.style.display = "none";
    console.log(value);
    if(value == 1){
        x.style.display = "none";
        y.style.display = "none";
        document.getElementById("indicesOps").required = false;
        document.getElementById("savebasket").disabled = true;
    }else if(value == 2){
        x.style.display = "block";
        y.style.display = "block";
        document.getElementById("indicesOps").required = true;
        document.getElementById("savebasket").disabled = false;
    }
    if(value == 1){
        y.style.display = "block";
    }else if(value == 2){
        y.style.display = "none";
    }

    }
    </script> --}}
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font: 16px Arial;
        }

        .autocomplete {
            position: relative;
            display: inline-block;
        }

        input {
            border: 1px solid transparent;
            background-color: #f1f1f1;
            padding: 10px;
            font-size: 16px;
        }

        input[type=text] {
            background-color: #f1f1f1;
            width: 100%;
        }

        input[type=submit] {
            background-color: DodgerBlue;
            color: #fff;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /position the autocomplete items to be the same width as the container:/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            /when hovering an item:/
            background-color: #e9e9e9;
        }

        .autocomplete-active {
            /when navigating through the items using the arrow keys:/
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
        
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

    </style>
@endsection