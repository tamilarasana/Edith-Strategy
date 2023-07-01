@extends('layouts.master')

@section('title')
	Basket
@endsection
<br><br>
@section('content')
<br><br><br><br>
<br><br><br><br>
<div class="row">
  <div class="col-10 grid-margin">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Recent Tickets</h4>
        <div class="table-responsive">
          <form method="post"  id="dynamic_form">
            <span id="result"></span>
            <table class="table  table-condensed"  id="user_table">
              <thead onload="myfunction()">
                  <tr>
                    <th style="font-size: 15px; text-align: center;">Token Id</th>
                    <th style="font-size: 15px; text-align: center;">Token Name</th>
                    <th style="font-size: 15px; text-align: center;">Ticket Price</th>
                    <th style="font-size: 15px; text-align: center;">Qty PerExe</th>
                    <th style="font-size: 15px; text-align: center;">Total Price</th>
                    <th style="font-size: 15px; text-align: center;">SqTarget</th>
                    <th style="font-size: 15px; text-align: center;">StLoss</th>
                    <th style="font-size: 15px; text-align: center;">lossSqr</th>
                    <th style="font-size: 15px; text-align: center;">Price</th>
                    <th style="font-size: 15px; text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <br>
            <center>
             <td><button type="button" name="add" id="add" class="btn-sm btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>
                @csrf
                <input type="submit" name="save"  id="save" class="btn-sm btn-primary" value="Save" />
                <a href ="{{route('basketcat.index')}}" class="btn btn-danger float-end">Back</a>
              </td>
            </center>

          </form>
        </div>
      </div>
    </div>
  </div>
    <div class="col-2 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title; text-align:center">Summary</h4>
                <tr>
                <label style="font-size: 10px; text-align: center;">Total Investment</label>
                <td><input type="text" name="total[]" class="fa fa-inr form-control sum-total"  id="total"style="font-size: 20px; font-weight:bold; text-align: center;"  placeholder="" /></td>
                </br>
                <td>
                <!--<div class="form-group">-->
                <label style="font-size: 10px; text-align: center;">Total Margin</label>
                <input type="text" class="fa fa-inr form-control mb-2 mr-sm-2 margin_price" id ="maginprice" style="font-size: 20px; font-weight:bold; text-align: center;" placeholder="">
                </tr>
                </td>
            </div>
        </div>
    </div>
</div>
@endsection
<input type="hidden" id="baasket_data" name="basket_data" value="{{  ($basket) }}">
<input type="hidden" id="basket_id" value="{{ $basket_id }}">
@section('scripts')

<script>
    $(document).ready(function(){
        var verify_data =  JSON.parse($("#baasket_data").val());
        if(verify_data == ''){
            var count = 1;
            dynamic_field(count);
        } else {
            var count = verify_data.length;
            console.log(verify_data);
            $.each(verify_data,function(key, value){
                updatedynamic_field(key+1,value);
            });

        }

        function dynamic_field(number)
        {
            // console.log('what is my value - '+data);
            html = '<tr>';
                html+='<input type="hidden" name="data['+number+'][basket_id]" value="'+<?php echo $basket_id ?>+'">';
                html += '<td><input type="text" name="data['+number+'][tokenId]" class="form-control "   placeholder="Token Id"required title="please enter value"/></td>';
                html += '<td><input type="text" name="data['+number+'][tokenName]" class="form-control"    placeholder="Name" required/></td>';
                html += '<td><input type="text" name="data['+number+'][ticket_price]" class="form-control " id = "quantity'+number+'"    placeholder="Price" required/></td>';
                html += '<td><input type="text" name="data['+number+'][qtyPerExe]" oninput="myFunction('+number+')" id = "unitprice'+number+'" class="form-control total-qty"   placeholder="Quantity"required /></td>';
                html += '<td><input type="text" name="data['+number+'][ttl_tk_price]"  class="form-control lunch-money total-price" id="total'+number+'"    placeholder="Price"required /></td>';
                html += '<td><input type="text" name="data['+number+'][SqTarget]"  class="form-control"   placeholder="Target"required /></td>';
                html += '<td><input type="text" name="data['+number+'][StLoss]" class="form-control"   placeholder="Loss"required /></td>';
                html += '<td><input type="text" name="data['+number+'][lossSqr]" class="form-control"  placeholder="Loss"required /></td>';
                html += '<td><input type="text" name="data['+number+'][margin_price]" class="form-control sum-cal marginprice" id="cal'+number+'"    placeholder="Price"required /></td>';
            if(number > 1)
            {
                html += '<td><button type="button" name="remove" id="" class="btn-sm btn-danger remove" onclick="deleteBtnFunction('+number+')"><i class="fa fa-trash"></i></button></td></tr>';
                $('tbody').append(html);
            }
            else
            {
                html += '<td></td></tr>';
                $('tbody').html(html);
            }
        }


        function updatedynamic_field(number, data = '')
        {
            calTotalAndQuantity(data)
            html = '<tr>';
                html+='<input type="hidden" id="dataid" name="id" value="'+data.id+'">';
                html+='<input type="hidden" name="data['+number+'][basket_id]" value="'+<?php echo $basket_id ?>+'">';
                html += '<td><input type="text" name="data['+number+'][tokenId]" class="form-control " value = " '+data.tokenId+'"  placeholder="Token Id"required title="please enter value"/></td>';
                html += '<td><input type="text" name="data['+number+'][tokenName]" class="form-control"  value = " '+data.tokenName+'"  placeholder="Name" required/></td>';
                html += '<td><input type="text" name="data['+number+'][ticket_price]" class="form-control" id = "quantity'+number+'"  value = " '+data.ticket_price+'"  placeholder="Price" required/></td>';
                html += '<td><input type="text" name="data['+number+'][qtyPerExe]" oninput="myFunction('+number+')" id = "unitprice'+number+'" class="form-control total-qty"  value = " '+data.qtyPerExe+'"  placeholder="Quantity"required /></td>';
                html += '<td><input type="text" name="data['+number+'][ttl_tk_price]"  class="form-control lunch-money total-price" id="total'+number+'"  value = " '+data.ttl_tk_price+'"  placeholder="Price"required /></td>';
                html += '<td><input type="text" name="data['+number+'][SqTarget]"  class="form-control"   value = " '+data.SqTarget+'"  placeholder="Target"required /></td>';
                html += '<td><input type="text" name="data['+number+'][StLoss]" class="form-control"  value = " '+data.StLoss+'"  placeholder="Loss"required /></td>';
                html += '<td><input type="text" name="data['+number+'][lossSqr]" class="form-control"  value = " '+data.lossSqr+'"  placeholder="Loss"required /></td>';
                html += '<td><input type="text" name="data['+number+'][margin_price]" class="form-control sum-cal marginprice" id="cal'+number+'"   value = " '+data.margin_price+'"  placeholder="Price"required /></td>';
                html += '<td><button type="button" id="" class="btn-sm btn-danger edit_delete_data" data-id="'+data.id+'" ><i class="fa fa-trash"></i></button></td>';

            if(number > 1)
            {
                 html += '<td></td></tr>';
                $('tbody').append(html);
            }
            else
            {
                html += '<td></td></tr>';
                $('tbody').html(html);
            }
        }

            $(document).on('click', '#add', function(){
                count++;
                dynamic_field(count);
            });

            $(document).on('click', '.remove', function(){

              count--;
              $(this).closest("tr").remove();
            });

            $('#dynamic_form').on('submit', function(event){
                    event.preventDefault();
                    var id=$("#basket_id").val();
                    // alert(id);

                    // var id = $(this).attr("data-id");
                    console.log(id);
                    $.ajax({
                        url:'{{ route("baskets.store") }}',
                        method:'post',
                        data:$(this).serialize()+'&id='+id,
                        dataType:'json',
                        beforeSend:function(){
                            $('#save').attr('disabled','disabled');
                        },
                        success:function(data)
                        {
                            if(data.error)
                            {
                                var error_html = '';
                                for(var count = 0; count < data.error.length; count++)
                                {
                                    error_html += '<p>'+data.error[count]+'</p>';
                                }
                                $('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
                            }
                            else
                            {
                                dynamic_field(1);
                                window.location.href = '{{ route("basketcat.index") }}';

                                $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
                            }
                            $('#save').attr('disabled', false);
                        }
                    })
            });

            $("body").on("click",'.edit_delete_data',function(){
                    var id = $(this).attr("data-id");
                    console.log(id);
                    let url = '{{route('baskets.delete')}}'
                    console.log(url);
                 if(confirm("Are You sure want to delete !")){
                    $.ajax({
                           url: url + '/' + id,
                           type: 'DELETE',
                            dataType: "JSON",
                            data:{
                                "id":id,
                                "_token": "{{ csrf_token() }}"},

                            success: function (data)
                            {
                                location.reload();
                                $('#result').html('<div class="alert alert-success">'+data.success+'</div>');

                            }
                    });
                }

            });
    });
    function deleteBtnFunction (number) {
        let qty = $("#quantity"+number+"").val()*$("#unitprice"+number+"").val()
        document.getElementById('total').value = Number(document.getElementById('total').value) - qty;
        document.getElementById('maginprice').value = Number(document.getElementById('maginprice').value) - $("#cal"+number+"").val()
    }
    function calTotalAndQuantity(data) {
        let qty =  data.ticket_price*data.qtyPerExe
        var cal = data.margin_price
        document.getElementById('total').value = Number(document.getElementById('total').value) + qty;
        document.getElementById('maginprice').value = Number(document.getElementById('maginprice').value) + cal
    }

        function myFunction(number){
          var quantity = $("#quantity"+number+"").val();
          var unitprice = $("#unitprice"+number+"").val();
          var total = (quantity*unitprice);
            $('#total'+number+'').val(total);
              var sum = 0;
                $(".lunch-money").each(function(){
                sum += +$(this).val();
              });

              //divivde  by total value in 25%
            $(".sum-total").val(sum);
              var cal = (total*25)/100;
              $('#cal'+number+'').val(cal);

            //calculate  all quentity
            var quantity = 0;
            $(".total-qty").each(function(){
              quantity += +$(this).val();
            });
            $(".totalqty").val(quantity);

            //calculate the all margin_price
             var margin_price = 0;
            $(".marginprice").each(function(){
              margin_price += +$(this).val();
            });
            $(".margin_price").val(margin_price);

        }

    </script>


@endsection

