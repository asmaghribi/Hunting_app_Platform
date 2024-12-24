@include('Admin.layouts.app')



<div class="content-wrapper">
  <div class="container-fluid">

    <div class="row justify-content-center">
      <div class="card mx-auto ">
        <div class="card-body">
          <h5 class="card-title">Liste des utilisateurs</h5>
          <div class="row justify-content-tight mb-3"> <!-- Ajout de la classe 'mb-3' pour la marge inférieure -->
      <div class="col-md-4"> <!-- Utilisation d'une colonne de taille moyenne (col-md-4) pour aligner le champ de recherche au centre -->
        <input type="text" id="searchInput" class="form-control w-100" style="width: 10%;" placeholder="Rechercher...">
      </div>
    </div>

          @if($users->count() > 0)


            <div class="table-responsive-xl">



              <table id="userTable" class="table table-striped custom-table text-center">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Nom</th>
                    <th scope="col">num° permis </th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Adresse</th>

                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                    <tr>
                      <td>{{ $user->id }}</td>
                      <td>{{ $user->Firstname }}</td>
                      <td>{{ $user->Lastname }}</td>
                      <td>{{ $user->Permis }}</td>
                      <td>{{ $user->phone }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->adresse }}</td>

                      <td>
                      <div class="btn-group">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      <i class="fa fa-edit"></i>
                      </button>
                      <form action="{{ route('admin.listusers.destroy', $user->id) }}" method="POST">
                           @csrf
                           @method('DELETE')
                          <button type="submit" class="btn btn-danger " onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"><i class="fa fa-trash"></i>
                          </button>
                        </form>
                      </div>
                       </td>
                       </tr>
                  @endforeach
                </tbody>
                <caption class="ms-4">
                  {{ $users->links('pagination::bootstrap-4') }}
                </caption>
              </table>
              <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
              <script>
$(document).ready(function(){
  $("#searchInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#userTable tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });
});
</script>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{ route('admin.listusers.update', $user->id) }}" method="POST">

    @csrf


    <input type="hidden" name="user_id" value="{{ $user->id }}">

    <div class="form-group">
        <label for="firstname">First Name:</label>
        <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname', $user->Firstname) }}">
    </div>

    <div class="form-group">
        <label for="lastname">Last Name:</label>
        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname', $user->Lastname) }}">
    </div>
    <div class="form-group">
        <label for="Permis">Permis:</label>
        <input type="text" class="form-control" id="Permis" name="Permis" value="{{ old('Permis', $user->Permis) }}">
    </div>
    <div class="form-group">
        <label for="phone">phone:</label>
        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
    </div>
    <div class="form-group">
        <label for="email">email:</label>
        <input type="text" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
    </div>
    <div class="form-group">
        <label for="adresse">adresse:</label>
        <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse', $user->adresse) }}">
    </div>


    <!-- Ajoutez ici d'autres champs pour les informations de l'utilisateur -->

    <button type="submit" class="btn btn-primary">Update User</button>
</form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>





            </div>
          @else
            <div class="card-body">
              <span> <i class='bx bx-search-alt'></i> Données non disponibles ...</span>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>

