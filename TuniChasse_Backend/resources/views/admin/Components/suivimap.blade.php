<meta name="csrf-token" content="{{ csrf_token() }}">
    <head>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery-jvectormap.min.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">



    <style>
        #map { height: 1200px; }
        /* Votre style personnalisé ici */
    </style>
</head>
@include('admin.layouts.app')
<body>
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
    var markers = [];
    var positions = {!! $positionsJson !!};


    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 36, lng: 10 },
            zoom: 10
        });

       positions.forEach(function(position) {
    if (isNaN(position.latitude) || isNaN(position.longitude)) {
        console.log("Ignorer la position invalide : ", position);
        return;
    }

    var marker = new google.maps.Marker({
        position: { lat: parseFloat(position.latitude), lng: parseFloat(position.longitude) },
        map: map,
        title: position.username
    });

    // Ajouter le marqueur au tableau pour pouvoir le supprimer plus tard
    markers.push(marker);
});

    }

    // Fonction pour supprimer tous les marqueurs de la carte
    function clearMarkers() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    }
 
  jQuery(function($) {
        initMap();
    });
    </script>

</body>
</div>
    </div>