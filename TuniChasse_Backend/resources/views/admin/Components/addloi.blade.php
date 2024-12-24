@include('Admin.layouts.app')

<div class="content-wrapper">
    <div class="container-fluid">

    <div class="row mt-3 justify-content-center">
      <div class="col-lg-6">
         <div class="card mx-auto ">
           <div class="card-body">
           <div class="card-title">Lois Form</div>
           <hr>
           <form action="{{ route('admin.addloi.store') }}" method="POST">

             @csrf

           <div class="form-group">
        <label for="text">Text:</label>
        <input type="text" class="form-control" id="text" name="text" >
          </div>


<div class="form-group">
    <label for="type">type:</label>
    <input type="text" class="form-control" id="type" name="type" >
</div>

           <div class="form-group">
            <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i> Add Loi</button>
          </div>
          </form>
         </div>
         </div>
      </div>

</div>
    </div><!--End Row-->

	<!--start overlay-->
		  <div class="overlay toggle-menu"></div>
		<!--end overlay-->

    </div>
    <!-- End container-fluid-->

   </div>
