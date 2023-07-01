@extends('layouts.master')
@section('title')
    Gateway
@endsection
@section('content')
    <br><br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card">
                    @if(!isset($gateway->id))
                        <form method="post" action="{{ route('gateway.store') }}" >
                    @else
                         <form method="post" action="{{ route('gateway.update', $gateway->id)}}">
                            @method('PUT')
                    @endif
                    {{-- <form action="{{ isset($gateway) ? @route('gateway.update', $gateway->id) : @route('gateway.store') }}"> --}}
                    {{-- <form action="{{ route('gateway.store') }}" method="post" enctype="multipart/form-data"> --}}
                        {{ csrf_field() }}
                        <div class="card-body">
                            <h3 class="card-description p-1"><b style="color:black">Create or Update Gateway</b></h3><hr>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>User Name </b></label>
                                    <input id="inputFloatingLabel" name="user_name" type="type"
                                        class="form-control input-border-bottom" placeholder="User Name"  value="{{ isset($gateway->user_name) ? $gateway->user_name : '' }}"  required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Password </b></label>
                                    <input id="inputFloatingLabel" name="password" type="password"
                                        class="form-control input-border-bottom" placeholder="Password" value="{{ isset($gateway->password) ? $gateway->password : '' }}" required>
                                </div>

                                {{-- <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Zerodha Token </b></label>
                                    <input id="inputFloatingLabel" name="zero_token" type="type"
                                        class="form-control input-border-bottom" placeholder="Zero Token"  value="{{ isset($gateway->zero_token) ? $gateway->zero_token : '' }}"  required>
                                </div> --}}

                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Api Key </b></label>
                                    <input id="inputFloatingLabel" name="api_key" type="type"
                                        class="form-control input-border-bottom" placeholder="Api Key" value="{{ isset($gateway->api_key) ? $gateway->api_key : '' }}" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Access Token </b></label>
                                    <input id="inputFloatingLabel" name="access_token" type="type"
                                        class="form-control input-border-bottom" placeholder="Access Token" value="{{ isset($gateway->access_token) ? $gateway->access_token : '' }}"  required>
                                </div>

                                {{-- <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>T Otp </b></label>
                                    <input id="inputFloatingLabel" name="t_otp" type="type"
                                        class="form-control input-border-bottom" placeholder="T OTP" value="{{ isset($gateway->t_otp) ? $gateway->t_otp : '' }}" required>
                                </div> --}}

                                {{-- <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Type </b></label>
                                    <input id="inputFloatingLabel" name="type" type="type"
                                        class="form-control input-border-bottom" placeholder="Type" value="{{ isset($gateway->type) ? $gateway->type : '' }}" required>
                                </div> --}}

                                <div class="col-md-4 mb-3">
                                    <label class="mt-3 mb-3"><b>Remarks </b></label>
                                    <input id="inputFloatingLabel" name="remarks" type="type" 
                                        class="form-control input-border-bottom" placeholder="Remarks" value="{{ isset($gateway->remarks) ? $gateway->remarks : '' }}" >
                                </div>

                                <div class="col-sm-4 col-md-4 col-xs-12 mb-3"> 
                                    <label class="mt-3 mb-3"><b>Status </b></label>
                                    <select  class="form-control has-error" name="status"  >
                                        <option value="">Select Status</option>
                                        <option value="1" @if ($gateway->status == '1') selected @endif>Active</option>
                                        <option value="2"@if ($gateway->status == '2') selected @endif >In Active</option>
                                    </select>
                                </div>        
                            </div>               
                        </div>
                        <hr>
                        <div class="col-md-3 mb-3 ">
                            <button class="btn btn-secondary"  id ="savebasket" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
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

