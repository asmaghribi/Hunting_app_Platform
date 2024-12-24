<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

  <title>{{ config('app.name') }} | Connexion Admin</title>
  <!-- loader-->
  <link href="{{ asset('css/app-style.css') }}" rel="stylesheet"/>
  <link href="{{ asset('css/pace.min.css') }}" rel="stylesheet"/>
  <script src="{{ asset('js/pace.min.js') }}"></script>
  <!--favicon-->
  <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
  <!-- Bootstrap core CSS-->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css"/>
  <!-- Custom Style-->


</head>

<body class="bg-theme bg-theme3">

<!-- start loader -->
   <div id="pageloader-overlay" class="visible incoming"><div class="loader-wrapper-outer"><div class="loader-wrapper-inner" ><div class="loader"></div></div></div></div>
   <!-- end loader -->

<!-- Start wrapper-->
 <div id="wrapper">

 <div class="loader-wrapper"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
 <div class="card card-authentication1 mx-auto my-5">
		<div class="card-body">
		 <div class="card-content p-2">
		 	<div class="text-center">
		 		<img src="images/logo-icon.png" alt="logo icon">
		 	</div>
		  <div class="card-title text-uppercase text-center py-3">Sign In</div>
           <form class="mb-3" action="{{ route('admin.login.submit') }}" method="POST">
                        @csrf

			  <div class="form-group">
			  <label for="email" class="sr-only">Email</label>
			   <div class="position-relative has-icon-right">
				  <input type="text" id="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Entrer votre email" autofocus value="{{ old('email') }}" placeholder="Enter Email">
				  @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                    <div class="form-control-position">
					  <i class="icon-user"></i>
				  </div>

			   </div>
			  </div>
			  <div class="form-group">
			  <label for="password" class="sr-only">Password</label>
			   <div class="position-relative has-icon-right">
				  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password">
                   @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                    @enderror
                  <div class="form-control-position">
					  <i class="icon-lock"></i>
				  </div>
			   </div>
			  </div>
			
			 <button type="submit" class="btn btn-light btn-block">Sign In</button>




			 </form>
		   </div>
		  </div>

	     </div>

    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->

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

    </div><!--wrapper-->

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/popper.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>

  <!-- sidebar-menu js -->
  <script src="{{ asset('js/sidebar-menu.js') }}"></script>

  <!-- Custom scripts -->
  <script src="{{ asset('js/app-script.js') }}"></script>

</body>
</html>
