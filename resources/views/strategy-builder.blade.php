@extends('layouts.master')
@section('title')
strategy
@endsection

@section('content')     
<br><br>
{{-- <div class="container-fluid"> 
	<div class="row">
		<div class="col-md-12 ">  
			<div class="card"> 
				<div class="card-body">
					<p class="card-description">Kalyani The Strategist</p>
					<div class="row">
						<div class="col-md-4 mb-3">
								<input id="inputFloatingLabel"  name ="title" type="text" class="form-control input-border-bottom"  placeholder = "Stock" required>
						</div>
						<div class="col-md-4 mb-3">
								<input id="inputFloatingLabel"  name ="title" type="date"  class="form-control input-border-bottom"  placeholder = "yyyy-mm-dd" required>
						</div>	
							<div class="col-md-4 mb-3">
									<input id="inputFloatingLabel"  name ="title" type="text" class="form-control input-border-bottom"  placeholder = "Segment" required>
							</div>
							<div class="col-md-4 mb-3">
									<input id="inputFloatingLabel"  name ="title" type="text" class="form-control input-border-bottom"  placeholder = "Expiry" required>
							</div>			
						
							<div class="col-md-6 mb-3">
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" class="custom-control-input" id="defaultInline2" name="inlineDefaultRadiosExample">
									<label class="custom-control-label" for="defaultInline2">Buy</label>
								</div>

								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" class="custom-control-input" id="defaultInline3" name="inlineDefaultRadiosExample">
									<label class="custom-control-label" for="defaultInline3">Sell</label>
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<div class="form-group mb-3 row">
								<label  class="col-xl-3 col-sm-4 mb-0 p-3 "><h5>Number of Pockets:</h5></label>
									<fieldset class="qty-box col-xl-9 col-xl-8 col-sm-7 pl-0">
											<div class="input-group bootstrap-touchspin col-sm-6">
												<div class="input-group-prepend">
													<button class=" quantity-left-minus btn btn-primary btn-square bootstrap-touchspin-down" type="button">-</button>
												</div>
												<div class="input-group-prepend">
														<span class="input-group-text bootstrap-touchspin-prefix" style="display: none;"></span>
												</div>
												<input class="touchspin form-control text-center" id="quantity" type="text" name="pocket_count" value="1" style="display: block;">
													<div class="input-group-append">
														<span class="input-group-text bootstrap-touchspin-postfix" style="display: none;"></span>
													</div>
													<div class="input-group-append ml-0">
														<button class=" quantity-right-plus btn btn-primary btn-square bootstrap-touchspin-up" type="button">+</button>
													</div>
												</div>
										</fieldset>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> --}}

<div class="container-fluid"> 
	<div class="row">
		<div class="col-md-12 ">  
			<div class="card"> 
				<div class="card-body">
					<p class="card-description">Kalyani The Strategist</p>
					<form action="{{ route('orders.store') }}" method = "post"  enctype="multipart/form-data" >
						{{csrf_field()}}
						<div class="row ">
							<div class="col-md-4 mb-3">
									<label class="mt-3 mb-3"><b>Basket </b></label>
									<select  class="form-control input-border-bottom" name ="basket_id" required>
											<option value="">Choose Our Basket</option>
											@foreach($basket as $key => $data)
												<option value="{{$data->id}}">{{$data->basket_name}}</option>
											@endforeach
									</select>
							</div>
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Token Name </b></label>
								{{-- <div class="col-sm-6"> --}}
									<input id="inputFloatingLabel"  name ="token_name" type="text" class="form-control input-border-bottom"  placeholder = "Token Name" required>
								{{-- </div> --}}
							</div>			
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Token Id </b></label>
								{{-- <div class="col-sm-6"> --}}
									<input id="inputFloatingLabel"  name ="token_id" type="text" class="form-control input-border-bottom"  placeholder = "Token Id" required>
								{{-- </div> --}}
							</div>			
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Leg Type </b></label>
								{{-- <div class="col-sm-6"> --}}
									<input id="inputFloatingLabel"  name ="leg_type" type="text" class="form-control input-border-bottom"  placeholder = "Leg Type" required>
								{{-- </div> --}}
							</div>			
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Quantity </b></label>
								{{-- <div class="col-sm-6"> --}}
									<input id="inputFloatingLabel"  name ="qty" type="number" class="form-control input-border-bottom"  placeholder = "Quantity" required>
								{{-- </div> --}}
							</div>			
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Status </b></label>
								{{-- <div class="col-sm-6"> --}}							
										<select  class="form-control input-border-bottom" name ="status" required>
											<option value= "" >Status</option>
											<option value="Active">Active</option>
											<option value="Deactive">Deactive</option>
										</select>
								{{-- </div> --}}
							</div>			
							{{-- <div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Delta</b></label >
									<input id="inputFloatingLabel"  name ="delta" type="number" class="form-control input-border-bottom"  placeholder = "Delta" required disabled>
							</div>			
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Theta </b></label>
									<input id="inputFloatingLabel"  name ="theta" type="number" class="form-control input-border-bottom"  placeholder = "Theta" required disabled>
							</div>			
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Vega </b></label>
									<input id="inputFloatingLabel"  name ="vega" type="number" class="form-control input-border-bottom"  placeholder = "Vega" required disabled>
							</div>			
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Gamma </b></label>
									<input id="inputFloatingLabel"  name ="gamma" type="number" class="form-control input-border-bottom"  placeholder = "Gamma" required disabled>
							</div>		 --}}
							<div class="col-md-4 mb-3">
								<label class="mt-3 mb-3"><b>Order Type </b></label>
								{{-- <div class="col-sm-6"> --}}							
										<select  class="form-control input-border-bottom" name ="order_type" required>
											<option value= "" >Type</option>
											<option value="Buy">Buy</option>
											<option value="Sell">Sell</option>
										</select>
								{{-- </div> --}}
							</div>		
						</div>
						<div class="col-md-3 mb-3">
							<button class="btn btn-secondary" type="submit">Submit</button>
						</div>					
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script>
	$(document).ready(function(){

var quantitiy=0;
   $('.quantity-right-plus').click(function(e){
        e.preventDefault();
        var quantity = parseInt($('#quantity').val());
		console.log(quantity);
            $('#quantity').val(quantity + 1);
    });

     $('.quantity-left-minus').click(function(e){
        e.preventDefault();
        var quantity = parseInt($('#quantity').val());
            if(quantity>0){
            $('#quantity').val(quantity - 1);
            }
    });
    
});
</script>

@endsection
