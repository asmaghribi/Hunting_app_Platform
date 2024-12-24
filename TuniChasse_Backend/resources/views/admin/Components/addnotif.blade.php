@include('Admin.layouts.app')

<div class="content-wrapper">
    <div class="container-fluid">

    <div class="row mt-3 justify-content-center">
      <div class="col-lg-6">
         <div class="card mx-auto ">
           <div class="card-body">
           <div class="card-title">Notification Form</div>
           <hr>
           <form action="{{ route('admin.addnotif.store') }}" method="POST">

             @csrf

           <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" >
          </div>


<div class="form-group">
    <label for="content">content:</label>
    <textarea type="text" class="form-control" id="content" name="content" ></textarea>
</div>

           <div class="form-group">
            <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i> Add Notification</button>
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
