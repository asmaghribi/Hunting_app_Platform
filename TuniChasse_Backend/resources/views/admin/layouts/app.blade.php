
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

  <title>Dashboard</title>

  <!-- loader-->


<link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
<script src='https://unpkg.com/leaflet@1.8.0/dist/leaflet.js' crossorigin=''></script>

<link href="{{ asset('css/pace.min.css') }}" rel="stylesheet"/>
<script src="{{ asset('js/pace.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!--favicon-->
<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
<!-- Vector CSS -->
<link href="{{ asset('plugins/fullcalendar/css/fullcalendar.min.css')}}" rel='stylesheet'/>
<!-- simplebar CSS-->
<link href="{{ asset('plugins/simplebar/css/simplebar.css')}}" rel="stylesheet"/>
<!-- Bootstrap core CSS-->
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
<!-- animate CSS-->
<link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css"/>
<!-- Icons CSS-->
<link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css"/>
<!-- Sidebar CSS-->
<link href="{{ asset('css/sidebar-menu.css')}}" rel="stylesheet"/>
<!-- Custom Style-->
<link href="{{ asset('css/app-style.css') }}" rel="stylesheet"/>

</head>

<body class="bg-theme bg-theme3">

<!-- Start wrapper-->
<div id="wrapper">
@include('Admin.elements.sidebar')
<!--Start topbar header-->
@include('Admin.elements.navbar')


<!--End topbar header-->

<div class="clearfix"></div>


   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->

	<!--Start footer-->
	<footer class="footer">
      <div class="container">
        <div class="text-center">
          Copyright © 2024 IMSC Dashboard Admin
        </div>
      </div>
    </footer>
	<!--End footer-->

  <!--start color switcher-->
   <div class="right-sidebar">
    <div class="switcher-icon">
      <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
    </div>
    <div class="right-sidebar-content">

      <p class="mb-0">Gaussion Texture</p>
      <hr>

      <ul class="switcher">
        <li id="theme1"></li>
        <li id="theme2"></li>
        <li id="theme3"></li>
        <li id="theme4"></li>
        <li id="theme5"></li>
        <li id="theme6"></li>
      </ul>

      <p class="mb-0">Gradient Background</p>
      <hr>

      <ul class="switcher">
        <li id="theme7"></li>
        <li id="theme8"></li>
        <li id="theme9"></li>
        <li id="theme10"></li>
        <li id="theme11"></li>
        <li id="theme12"></li>
		<li id="theme13"></li>
        <li id="theme14"></li>
        <li id="theme15"></li>
      </ul>

     </div>
   </div>
  <!--end color switcher-->


</div><!--End wrapper-->

  <!-- Bootstrap core JavaScript-->
  <script src="{{asset('js/jquery.min.js')}}"></script>
  <script src="{{asset('js/popper.min.js')}}"></script>
  <script src="{{asset('js/bootstrap.min.js')}}"></script>

 <!-- simplebar js -->
  <script src="{{asset('plugins/simplebar/js/simplebar.js')}}"></script>
  <!-- sidebar-menu js -->
  <script src="{{asset('js/sidebar-menu.js')}}"></script>
  <!-- loader scripts -->

  <!-- Custom scripts -->
  <script src="{{asset('js/app-script.js')}}"></script>
  <!-- Chart js -->
    <!-- Full Calendar -->
  <script src="{{asset('plugins/fullcalendar/js/moment.min.js')}}"></script>
  <script src="{{asset('plugins/fullcalendar/js/fullcalendar.min.js')}}"></script>
  <script src="{{asset('plugins/fullcalendar/js/fullcalendar-custom-script.js')}}"></script>
  <script src="{{asset('plugins/Chart.js/Chart.min.js')}}"></script>

  <!-- Index js -->
  <script src="{{asset('js/index.js')}}"></script>



</body>
</html>

