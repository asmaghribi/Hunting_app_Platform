<meta name="csrf-token" content="{{ csrf_token() }}">
    <head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery-jvectormap.min.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">



    <style>
        #map { height: 1200px; }
        /* Votre style personnalisé ici */
    </style>
</head>
@include('admin.layouts.app')
<body>

    <!-- Modal -->

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Affect Polygon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="polygonForm" action="{{ route('admin.update-polygon') }}" method="post">
                        @csrf

                        <input type="hidden" id="polygon_id" name="polygon_id" value="">


                        <div class="form-group">
                            <label for="polygon_name">Nom de Polygon:</label>
                            <input type="text" class="form-control" id="polygon_name" name="polygon_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="polygon_color">Couleur:</label>
                            <br>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="polygon_color" id="green_color" value="green">
                                <label class="form-check-label" for="green_color">Vert</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="polygon_color" id="red_color" value="red">
                                <label class="form-check-label" for="red_color">Rouge</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour le polygone</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal store polygon-->
    <div class="modal fade" id="newPolygonModal" tabindex="-1" role="dialog" aria-labelledby="newPolygonModalLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newPolygonModalLabel">Nouveau Polygon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="newPolygonForm" action="{{ route('admin.newpoly') }}" method="post">
          @csrf
          <input type="hidden" id="newPolygonCoordinates" name="newPolygonCoordinates">

          <div class="form-group">
            <label for="newPolygonName">Nom de Polygon:</label>
            <input type="text" class="form-control" id="newPolygonName" name="newPolygonName" value="">
          </div>
          <div class="form-group">
            <label for="newPolygonColor">Couleur:</label>
            <br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="newPolygonColor" id="newGreenColor" value="green">
              <label class="form-check-label" for="newGreenColor">Vert</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="newPolygonColor" id="newRedColor" value="red">
              <label class="form-check-label" for="newRedColor">Rouge</label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
      </div>
    </div>
  </div>
</div>



    <div class="content-wrapper">
        <div class="container-fluid">
            <div id="map"></div>


    <!-- Inclure la bibliothèque jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Inclure la bibliothèque Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDdnJn9ay7gJfLC38n-u6_iLTLcoN5qsM&libraries=drawing"></script>
    <script>
    var map;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 36, lng: 10 },
            zoom: 10
        });
        // Créer un gestionnaire d'événements pour le dessin
        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['polygon']
            }
        });
        drawingManager.setMap(map);
         // Écouter l'événement de dessin de polygon
         drawingManager.addListener('polygoncomplete', function(event) {
    var polygon = event.getPath();
    drawnPolygons.push(polygon);
    if (polygon.getLength() > 2 && polygon.getAt(0).equals(polygon.getAt(polygon.getLength() - 1))) {
        // Ouvrir le nouveau modal pour saisir le titre et choisir la couleur
        $('#newPolygonModal').modal('show');
        // Récupérer les coordonnées du polygone
        var coordinates = polygon.getArray().map(function(latLng) {
            return [latLng.lng(), latLng.lat()];
        });
        // Mettre à jour la valeur du champ caché
        $('#newPolygonCoordinates').val(JSON.stringify(coordinates));
        polygon.addListener('click', function(event) {
            openNewPolygonModal(polygon);
        });
    }
});


        // Écouter l'événement de dessin
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            if (event.type == google.maps.drawing.OverlayType.POLYGON) {
                // Ouvrir le modal pour saisir le titre et choisir la couleur
                $('#newPolygonModal').modal('show');

                // Récupérer les coordonnées du polygone
                var coordinates = event.overlay.getPath().getArray().map(function(latLng) {
                    return [latLng.lng(), latLng.lat()];
                });

            }

        });
        function openNewPolygonModal(polygon) {

    $('#newPolygonModal').modal('show');
}

        var selectedPolygonId = null;
        var selectedPolygonName = '';
        var selectedPolygonColor = '';

function openPolygonModal(polygonData, polygonId) {
    selectedPolygonId = polygonId;
    $('#polygon_id').val(polygonId);
    selectedPolygonName = polygonData.title; // Stockez la valeur actuelle du nom du polygone
    selectedPolygonColor = polygonData.color; // Stockez la valeur actuelle de la couleur
    // Mettre à jour la valeur du champ polygon_id caché
    $('#polygon_name').val(polygonData.title); // Mettre à jour la valeur du champ polygon_name
    $('#polygon_color').val(polygonData.color); // Mettre à jour la valeur du champ polygon_color
    // Définir la couleur par défaut en fonction de la couleur du polygone
    $('input[name="polygon_color"][value="' + polygonData.color + '"]').prop('checked', true);

    $('#exampleModal').modal('show');
}

        $.getJSON('/poly.json', function(jsonData) {
            jsonData.forEach(function(polygonData) {
                var coordinates = JSON.parse(polygonData.coordinates);
                var latLngs = coordinates.map(function(coord) {
                    return new google.maps.LatLng(coord[1], coord[0]);
                });
                var polygon = new google.maps.Polygon({
                    paths: latLngs,
                    strokeColor: polygonData.color,
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: polygonData.color,
                    fillOpacity: 0.35,
                    editable: false,
                    draggable: false,
                    map: map
                });
              /*  var bounds = new google.maps.LatLngBounds();
        latLngs.forEach(function(latLng) {
            bounds.extend(latLng);
        });
        var center = bounds.getCenter();

        // Créer une nouvelle instance de MapLabel pour afficher le titre du polygone
        var title = polygonData.title;
        var text = new MapLabel({
            text: title,
            position: center,
            map: map,
            fontSize: 12,
            align: 'center'

        });*/

                google.maps.event.addListener(polygon, 'click', function(event) {
                    openPolygonModal(polygonData,polygonData.id);
                });
            });
        });
        $('#polygonForm').submit(function(e) {
    e.preventDefault();

    var formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('polygon_id', $('#polygon_id').val());

    // Vérifiez si la valeur du nom du polygone a changé
    var newPolygonName = $('input[name="polygon_name"]').val();
    if (newPolygonName !== selectedPolygonName) {
        formData.append('polygon_name', newPolygonName);
    }

    // Vérifiez si la valeur de la couleur a changé
    var newPolygonColor = $('input[name="polygon_color"]:checked').val();
    if (newPolygonColor !== selectedPolygonColor) {
        formData.append('polygon_color', newPolygonColor);
    } // Utilisez l'attribut name au lieu de l'identifiant
    console.log($('#polygon_id').val());
    // Envoyer une requête AJAX pour mettre à jour le polygone
    $.ajax({
        url: '{{ route('admin.update-polygon') }}',
        method: 'POST',
        data: formData, // Utilisez formData à la place de $(this).serialize()
        processData: false, // Ajoutez cette ligne
        contentType: false, // Ajoutez cette ligne
        success: function(response) {
                    // Le polygone a été mis à jour avec succès
                    $('#exampleModal').modal('hide');
                    // Recharger la page pour afficher les modifications
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Gérer les erreurs
                    console.error(error);
                }
            });
        });
    }



    //code store polygon commence ici

var drawnPolygons = [];

    // Écouter l'événement de dessin de polygon


    $('#newPolygonForm').submit(function(e) {
    e.preventDefault();

    // Récupérer les données du formulaire
    var formData = new FormData(this);

    // Récupérer les coordonnées du dernier polygone dessiné
    var lastPolygon = drawnPolygons[drawnPolygons.length - 1];
    var coordinates = lastPolygon.getArray().map(function(latLng) {
        return [latLng.lng(), latLng.lat()];
    });

    // Ajouter les coordonnées du polygone au formulaire en tant que tableau
    formData.append('newPolygonCoordinates', JSON.stringify([coordinates]));

    // Envoyer une requête AJAX pour enregistrer les données du nouveau polygone dans le fichier JSON
    $.ajax({
        url: '{{ route('admin.newpoly') }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#newPolygonModal').modal('hide');
            // Mettre à jour la couleur du dernier polygone dessiné
            lastPolygon.setOptions({
                fillColor: $('input[name="newPolygonColor"]:checked').val(),
                strokeColor: $('input[name="newPolygonColor"]:checked').val()
            });
            location.reload();
        },
        error: function(error) {
            console.error(error);
        }
    });
});

    @if(session('success'))
    <script>
    setTimeout(function(){
    $('#exampleModal').modal('hide');
    window.location.reload();
}, 1000);

    </script>

@endif
@if(session('success'))
<script>
    setTimeout(function(){
    $('#newPolygonModal').modal('hide');
    window.location.reload();
}, 1000);

    </script>
    @endif
    jQuery(function($) {
        initMap();
    });
</script>

</body>
</div>
    </div>
