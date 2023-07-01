<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>
	@yield('title')
	</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<!-- <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}"> -->  
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}" />  --}}
	<!--<link rel="stylesheet" href="../assets1/css/new.css">-->
	<!--<link rel="stylesheet" href="../assets1/css/style.css">-->
	
	
    <link rel="stylesheet" href="{{asset('assets1/css/new.css')}}">
	<link rel="stylesheet" href="{{asset('assets1/css/style.css')}}">

	<link rel="stylesheet" href="{{asset('assets/css/azzara.min.css')}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>     
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>  --}}

    


</head>
<body>
    <div class="container-fluid"> 
    <nav class="navbar navbar-expand-sm navbar-light ">
        <a class="navbar-brand" style="cursor: pointer; font-size:25px" href="/home"><b>E.D.I.T.H</b></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav navbar-nav-right ml-auto">
                 <li class="nav-item ">
                    <a class="font-weight-medium btn-sm" href="{{route('history.index')}}" >
                        <b>Dashboard</b>
                    </a>            
                </li>
                <li class="nav-item ">
                    <a class="font-weight-medium btn-sm" href="{{route('basket.index')}}" >

                        <b>Basket</b>
                    </a>            
                </li>
                <li class="nav-item ">
                    <a class="font-weight-medium btn-sm" href="{{route('webhook.index')}}" >
                        <b>Web Hook</b>
                    </a>            
                </li>
                <!-- <li class="nav-item ">-->
                <!--    <a class="font-weight-medium btn-sm" href="{{route('basket.index')}}" >-->
                <!--        <b>Live Market</b>-->
                <!--    </a>            -->
                <!--</li>-->
                <!-- <li class="nav-item ">-->
                <!--    <a class="font-weight-medium btn-sm" href="{{route('basket.index')}}" >-->
                <!--        <b>History</b>-->
                <!--    </a>            -->
                <!--</li>-->
                <li class="nav-item ">
                    <a class="font-weight-medium btn-sm" href="{{route('scheduled.index')}}" >
                        <b>Scheduled Basket</b>
                    </a>            
                </li>
                 <li class="nav-item ">
                    <a class="font-weight-medium btn-sm" href="{{route('basket.index')}}" >
                        <b>About Us</b>
                    </a>            
                </li>
                 <li class="nav-item ">
                    <a class="font-weight-medium btn-sm" href="{{route('basket.index')}}" >
                        <b>Faq</b>
                    </a>            
                </li>

                {{-- <li class="nav-item active">
                    <a class=" font-weight-medium btn-sm" href="{{route('orders.index')}}" >
                    orders
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a class="  font-weight-medium btn-sm" href="{{ route('holdings.index') }}" >
                    Holdings
                    </a>
                </li>    --}}
                <li class="nav-item dropdown hidden-caret">
                    <a id="navbarDropdown" class="font-weight-medium btn-sm" href="#" role="button" data-toggle="dropdown" 			aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        {{-- <div class="card shadow">                           --}}
                            <div class="text-center">
                                <a href="{{ route('gateway.create') }}" class="btn btn-success mb-2" >Gatway</a>
                                {{-- <a href="#" class="btn btn-success mb-2">Gatway</a> --}}
                              </div>
                            <div class="text-center">
                                <a href="{{ route('registers') }}"  class="btn btn-primary mb-2">Register</a>
                              </div>
                              <div class="text-center">
                                <a href="{{ route('logout') }}"  class="btn btn-danger mb-2">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form> 
                            </div>
                             
     
                          {{-- </div> --}}
                    </div>
                </li>    
          </ul>          
        </div>
      </nav>
    </div>
         <div class="content">
            @yield('content')
        </div>
    <script src="{{asset('assets/js/core/jquery.3.2.1.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>

<!-- jQuery UI -->
<script src="{{asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>

<!-- jQuery Scrollbar -->
<script src="{{asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

<script src="{{asset('assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

<!-- Sweet Alert -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!-- Azzara JS -->
<script src="{{asset('assets/js/ready.min.js')}}"></script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">

    @yield('scripts')

    <!-- End custom js for this page -->
  </body>
</html>