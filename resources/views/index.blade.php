@extends('layouts.master')
@section('title')
strategy
@endsection

@section('content')

<br><br>
<div class="container"> 
	<div class="row">
		<div class="col-md-12 well">
			<div class="view" style="background-image: url({{asset('assets/img/banner.jpg')}}); background-repeat: no-repeat; background-size: cover; background-position: center center;padding: 64px 0px 0px; height:450px">
				<h1 class="p-4 display-2 text-xs-center text-center">E.D.I.T.H</h1>
				<h2 class="p-1 text-xs-center text-center">Even Dead I am the Hero</h2>
				<h4 style="font-size:20px" class="p-3 text-xs-center text-center">Real stock trading environment with virtual money Market Analysis with Entry and Exit Points
Practice as much as you can to become a smart and profitable trader</h4>
				<br>
				<br>
				<div class="col-md-12 text-center">
					<a href="{{ route('history.index') }}" class="btn btn-xl btn btn-warning btn-center">DASHBOARD</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


@section('scripts')
@endsection
