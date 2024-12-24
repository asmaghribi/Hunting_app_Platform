
@include('Admin.layouts.app')




<div class="content-wrapper">
    <div class="container-fluid">

  <!--Start Dashboard Content-->
  <script>
    var newVisitorsCount = {{ $newVisitorsCount }};
    var oldVisitorsCount = {{ $oldVisitorsCount }};
    var proiTypesCount = JSON.parse('{!! json_encode($proiTypesCount) !!}');
    var proiTypesPercentages = JSON.parse('{!! json_encode($proiTypesPercentages) !!}');

</script>
	<div class="card mt-3">
    <div class="card-content">
        <div class="row row-group m-0">
            <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <h5 class="text-white mb-0">{{ $users->count() }}<span class="float-right"><i class="fa fa-users"></i></span></h5>
                    <div class="progress my-3" style="height:3px;">
                       <div class="progress-bar" style="width:55%"></div>
                    </div>
                  <p class="mb-0 text-white small-font">Total Users <span class="float-right"></i></span></p>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <h5 class="text-white mb-0">{{ $alerts->count() }}<span class="float-right"><i class="fa fa-exclamation-triangle"></i></span></h5>
                    <div class="progress my-3" style="height:3px;">
                       <div class="progress-bar" style="width:55%"></div>
                    </div>
                  <p class="mb-0 text-white small-font">Total Alerts <span class="float-right"></i></span></p>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <h5 class="text-white mb-0">{{ $events->count() }} <span class="float-right"><i class="fa fa-calendar"></i></span></h5>
                    <div class="progress my-3" style="height:3px;">
                       <div class="progress-bar" style="width:55%"></div>
                    </div>
                  <p class="mb-0 text-white small-font">Total Events <span class="float-right"></i></span></p>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3 border-light">
                <div class="card-body">
                  <h5 class="text-white mb-0">{{ $prois->count() }}<span class="float-right"><i class="fa fa-paw"></i></span></h5>
                    <div class="progress my-3" style="height:3px;">
                       <div class="progress-bar" style="width:55%"></div>
                    </div>
                  <p class="mb-0 text-white small-font">Total Prois <span class="float-right"></i></span></p>
                </div>
            </div>
        </div>
    </div>
 </div>

	<div class="row">
     <div class="col-12 col-lg-8 col-xl-8">
     <div class="card">
    <div class="card-body">
        <ul class="list-inline">
            <li class="list-inline-item"><i class="fa fa-circle mr-2 text-white"></i>New Visitor</li>
            <li class="list-inline-item"><i class="fa fa-circle mr-2 text-light"></i>Old Visitor</li>
        </ul>
        <div class="chart-container-1">
            <canvas id="chart1"></canvas>
        </div>
    </div>

    <div class="row m-0 row-group text-center border-top border-light-3">
        <div class="col-md-6">
            <h5>New Visitors</h5>
            <ul>

           <li>{{ $newVisitorsCount }}</li>

            </ul>
        </div>
        <div class="col-md-6">
            <h5>Old Visitors</h5>
            <ul>

            <li>{{ $oldVisitorsCount }}</li>

            </ul>
        </div>
    </div>
</div>

	 </div>

     <div class="col-12 col-lg-4 col-xl-4">
        <div class="card">

           <div class="card-body">
		     <div class="chart-container-2">
               <canvas id="chart2"></canvas>
			  </div>
           </div>
           <div class="table-responsive">
    <table class="table align-items-center">
        <tbody>
            @foreach ($proiTypesCount as $type => $count)
                <tr>
                    <td><i class="fa fa-circle text-white mr-2"></i> {{ $type }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ $proiTypesPercentages[$type] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

         </div>
     </div>
	</div><!--End Row-->

      <!--End Dashboard Content-->

	<!--start overlay-->
		  <div class="overlay toggle-menu"></div>
		<!--end overlay-->

    </div>
    <!-- End container-fluid-->

</div><!--End content-wrapper-->

