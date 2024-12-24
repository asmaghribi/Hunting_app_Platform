@include('Admin.layouts.app')


<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="card mx-auto ">
        <div class="card-body">
          <h5 class="card-title">Liste des Notifications</h5>
          <div class="row justify-content-tight mb-3"> <!-- Ajout de la classe 'mb-3' pour la marge inférieure -->
      <div class="col-md-4"> <!-- Utilisation d'une colonne de taille moyenne (col-md-4) pour aligner le champ de recherche au centre -->
        <input type="text" id="searchInput" class="form-control w-100" style="width: 10%;" placeholder="Rechercher...">
      </div>
    </div>
          @if($notif->count() > 0)
            <div class="table-responsive-xl">
              <table id="userTable" class="table table-striped custom-table text-center">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">title</th>
                    <th scope="col">Content</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($notif as $notf)
                    <tr>
                      <td>{{ $notf->id }}</td>
                      <td>{{ $notf->title }}</td>
                      <td>{{ $notf->content }}</td>



                      <td>
                      <div class="btn-group">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      <i class="fa fa-edit"></i>
                      </button>
                      <form action="{{ route('admin.listnotif.destroy', $notf->id) }}" method="POST">
                           @csrf
                           @method('DELETE')
                          <button type="submit" class="btn btn-danger " onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet notification ?')"><i class="fa fa-trash"></i>
                          </button>
                        </form>
                      </div>
                       </td>
                       </tr>
                  @endforeach
                </tbody>
                <caption class="ms-4">
                  {{ $notif->links('pagination::bootstrap-4') }}
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{ route('admin.listnotif.update', $notf->id) }}" method="POST">

    @csrf


    <input type="hidden" name="notif_id" value="{{ $notf->id }}">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $notf->title) }}">
          </div>


<div class="form-group">
    <label for="content">content:</label>
    <textarea type="text" class="form-control" id="content" name="content" value="{{ old('content', $notf->content) }}" ></textarea>
</div>



    </div>


    <!-- Ajoutez ici d'autres champs pour les informations de l'utilisateur -->

    <button type="submit" class="btn btn-primary">Update Notification</button>
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

