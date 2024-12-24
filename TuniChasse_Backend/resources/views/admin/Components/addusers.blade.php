@include('Admin.layouts.app')

<div class="content-wrapper">
    <div class="container-fluid">

    <div class="row mt-3 justify-content-center">
      <div class="col-lg-6">
         <div class="card mx-auto ">
           <div class="card-body">
           <div class="card-title">User Form</div>
           <hr>
           <form action="{{ route('admin.addusers.store') }}" method="POST">

             @csrf

           <div class="form-group">
        <label for="firstname">First Name:</label>
        <input type="text" class="form-control" id="firstname" name="firstname" >
          </div>

      <div class="form-group">
      <label for="lastname">Last Name:</label>
       <input type="text" class="form-control" id="lastname" name="lastname" >
      </div>
<div class="form-group">
    <label for="Permis">Permis:</label>
    <input type="text" class="form-control" id="Permis" name="Permis" >
</div>
<div class="form-group">
    <label for="phone">phone:</label>
    <input type="text" class="form-control" id="phone" name="phone" >
</div>
<div class="form-group">
    <label for="email">email:</label>
    <input type="text" class="form-control" id="email" name="email" >
</div>
<div class="form-group">
    <label for="adresse">adresse:</label>
    <input type="text" class="form-control" id="adresse" name="adresse" >
</div>
<div class="form-group">
    <label for="password">password:</label>
    <input type="text" class="form-control" id="password" name="password" >
</div>

           <div class="form-group">
            <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i> Add</button>
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
