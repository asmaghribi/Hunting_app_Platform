@include('Admin.layouts.app')


<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="card mx-auto ">
        <div class="card-body">
          <h5 class="card-title">Liste des Proies</h5>
          @if($proi->count() > 0)
            <div class="table-responsive-xl">
              <table class="table table-striped custom-table text-center">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">nom</th>
                    <th scope="col">image</th>
                    <th scope="col">espéce</th>
                    <th scope="col">Type</th>

                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($proi as $pr)
                    <tr>
                      <td>{{ $pr->id }}</td>
                      <td>{{ $pr->name }}</td>
                      <td><img src="{{ asset('images/' . $pr->image) }}" alt="Proi image" width="100" height="100"></td>

                      <td>{{ $pr->species }}</td>
                      <td>{{ $pr->type }}</td>


                      <td>
                      <div class="btn-group">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      <i class="fa fa-edit"></i>
                      </button>
                      <form action="{{ route('admin.listproies.destroy', $pr->id) }}" method="POST">
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
                  {{ $proi->links('pagination::bootstrap-4') }}
                </caption>
              </table>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Proi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{ route('admin.listproies.update', $pr->id) }}" method="POST">

    @csrf


    <input type="hidden" name="proi_id" value="{{ $pr->id }}">

    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $pr->name) }}">
    </div>

    <div class="form-group">
        <label for="image">Image:</label>
        <input type="text" class="form-control" id="image" name="image" value="{{ old('image', $pr->image) }}">
    </div>
    <div class="form-group">
        <label for="species">Espéce:</label>
        <input type="text" class="form-control" id="species" name="species" value="{{ old('species', $pr->species) }}">
    </div>
    <div class="form-group">
        <label for="type">Type:</label>
        <input type="text" class="form-control" id="type" name="type" value="{{ old('type', $pr->type) }}">
    </div>

    </div>


    <!-- Ajoutez ici d'autres champs pour les informations de l'utilisateur -->

    <button type="submit" class="btn btn-primary">Update Proi</button>
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

