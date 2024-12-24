@include('Admin.layouts.app')


<div class="content-wrapper">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="card mx-auto ">
        <div class="card-body">
          <h5 class="card-title">Liste des alertes</h5>
          <div class="row justify-content-tight mb-3"> <!-- Ajout de la classe 'mb-3' pour la marge inférieure -->
      <div class="col-md-4"> <!-- Utilisation d'une colonne de taille moyenne (col-md-4) pour aligner le champ de recherche au centre -->
        <input type="text" id="searchInput" class="form-control w-100" style="width: 10%;" placeholder="Rechercher...">
      </div>
    </div>
          @if($alerts->count() > 0)
            <div class="table-responsive-xl">
              <table id="userTable" class="table table-striped custom-table text-center">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">message</th>
                    <th scope="col">position</th>

                    <th scope="col">created_at</th>



                  </tr>
                </thead>
                <tbody>
                  @foreach($alerts as $alert)
                    <tr>
                      <td>{{ $alert->id }}</td>
                      <td>{{ $alert->message }}</td>
                      <td>{{ $alert->position['coordinates'][0][0] }}, {{ $alert->position['coordinates'][0][1] }}</td>


                      <td>{{ $alert->created_at }}</td>



                       </tr>
                  @endforeach
                </tbody>
                <caption class="ms-4">
                  {{ $alerts->links('pagination::bootstrap-4') }}
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
