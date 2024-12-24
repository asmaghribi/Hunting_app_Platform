@include('Admin.layouts.app')

<div class="content-wrapper">
    <div class="container-fluid">

    <div class="row mt-3 justify-content-center">
      <div class="col-lg-6">
         <div class="card mx-auto ">
           <div class="card-body">
           <div class="card-title">Proie Form</div>
           <hr>
           <form action="{{ route('admin.addproies.store') }}" method="POST" enctype="multipart/form-data">

             @csrf

           <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" >
          </div>

      <div class="form-group">
      <label for="image">image:</label>
      <input type="file" class="form-control" id="image" name="image" >

      </div>
<div class="form-group">
    <label for="species">Espéce:</label>
    <input type="text" class="form-control" id="species" name="species" >
</div>
<div class="form-group">
    <label for="type">type:</label>
    <input type="text" class="form-control" id="type" name="type" >
</div>

           <div class="form-group">
            <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i> Add Proi</button>
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
