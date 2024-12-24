import 'dart:async';
import 'dart:convert';
import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart' show Uint8List, rootBundle;
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:google_maps_webservice/places.dart';
import 'package:location/location.dart' as location_pkg;
import 'package:http/http.dart' as http;
import 'package:awesome_notifications/awesome_notifications.dart';
import 'package:geolocator/geolocator.dart' as Geolocator;
import 'package:flutter/painting.dart';
import 'dart:ui' as ui;

import 'package:google_maps_webservice/places.dart';
import 'package:google_maps_webservice/places.dart';


import 'BottomNavBar.dart';
enum AnimalType { rabbit, bird, pig }

class MapScreen extends StatefulWidget {
  final Position? currentPosition;
  final GoogleMapController? controller;
  final FloatingActionButton? floatingActionButtonalert;


  const MapScreen({
    Key? key,
    this.currentPosition,
    this.controller,
    this.floatingActionButtonalert,
  }) : super(key: key);

  @override
  _MapScreenState createState() => _MapScreenState();
}

class _MapScreenState extends State<MapScreen> {
  AnimalType? _selectedAnimal;
  List<AnimalType> _selectedAnimals = [];
  bool _polygonsAdded = false;
  bool _isDialogShown = false;
  List<String> _selectedIcons = [];
  late GoogleMapController controller;
  Map<String, String> polygonTitles = {};
  MapType _currentMapType = MapType.hybrid;
  List<Map<String, dynamic>> polygonData = [];
  List<Polygon> polygons = [];
  LatLng? currentLocation;
  late TextEditingController _searchController;
  FloatingActionButton? floatingActionButtonalert;
  int _currentIndex = 0;
  
  //final _places = GoogleMapsPlaces(apiKey: 'AIzaSyDDdnJn9ay7gJfLC38n-u6_iLTLcoN5qsM');
  Set<Marker> _markers = {};
  StreamSubscription<Position>? _positionSubscription;
  String? _lastZone;
  late FloatingActionButton floatingActionButton;

  final GlobalKey<ScaffoldState> _scaffoldKey = GlobalKey<ScaffoldState>();
  List<String> suggestions = [];
  bool isSuggestionsVisible = false;
  final GlobalKey _searchKey = GlobalKey();


  @override
  void initState() {
    super.initState();

    if (!_polygonsAdded) {
      loadPolygonData();
      _polygonsAdded = true;
    }
    getLocation();

    floatingActionButton = new FloatingActionButton(onPressed: () {
      Navigator.of(context).push(
        MaterialPageRoute(
          builder: (context) => MapScreen(),
        ),
      );
    });
    _sendAlert();
    _sendLocationUpdate();
    //_onCameraMove(_onCameraMove as CameraPosition);
    AwesomeNotifications().initialize(
      '', // Remplacez ceci par votre propre icône
      [
        NotificationChannel(
          channelKey: 'basic_channel',
          channelName: 'Basic notifications',
          channelDescription: 'Notification channel for basic tests',
          defaultColor: Color(0xFF9D50DD),
          ledColor: Colors.white,
        )
      ],
    );
    AwesomeNotifications().isNotificationAllowed().then((isAllowed) {
      if (!isAllowed) {
        AwesomeNotifications().requestPermissionToSendNotifications();
      }
    });
    _positionSubscription =
        Geolocator.Geolocator.getPositionStream().listen((Position position) {
          setState(() {
            currentLocation = LatLng(position.latitude, position.longitude);
            _addLocationMarker(currentLocation!);
          });
          sendNotification(position);
        });

    _sendNotificationsOnMapLoad();

    Future.delayed(Duration.zero, () {
      showDialog(
        context: context,
        barrierDismissible: false,
        // Empêcher la fermeture du dialog en cliquant en dehors
        builder: (BuildContext context) {
          return AlertDialog(
            title: Text('Choisissez un animal'),
            content: _buildDialogContent(),
            actions: <Widget>[
              TextButton(
                child: Text('Annuler'),
                onPressed: () {
                  Navigator.of(context).pop();
                  setState(() {
                    _selectedIcons.clear();
                    _isDialogShown = false;
                  });
                },
              ),
              TextButton(
                child: Text('OK'),
                onPressed: () {
                  Navigator.of(context).pop();
                  setState(() {
                    _isDialogShown = false;
                  });
                },
              ),
            ],
          );
        },
      ).then((value) {
        _isDialogShown = false;
      });

      _isDialogShown = true;
    });
  }

  Future<void> _sendLocationUpdate() async {
    if (currentLocation != null) {
      final String username = "Nom d'utilisateur"; // Remplacez par le nom de l'utilisateur actuellement connecté
      final positionData = {
        'username': username,
        'latitude': currentLocation!.latitude,
        'longitude': currentLocation!.longitude
      };
      try {
        final apiUrl = dotenv.env['Api_url'];
        final response = await http.post(
          Uri.parse('$apiUrl/api/send-location-update'),
          body: json.encode(positionData),
          headers: {'Content-Type': 'application/json'},
        );
        if (response.statusCode == 200) {
          print('Location update sent successfully');
        } else {
          print('Error sending location update: ${response.statusCode}');
        }
      } catch (error) {
        print('Error sending location update: $error');
      }
    }
  }


  /*void _onSearchChanged() {
    String searchTerm = _searchController.text.toLowerCase();
    setState(() {
      suggestions = polygonData
          .where((polygon) =>
              polygon['title'].toLowerCase().startsWith(searchTerm))
          .map<String>((polygon) => polygon['title'])
          .toList();
    });
  }*/

  void _onItemTapped(int index) {
    setState(() {
      _currentIndex = index;
    });
  }



  Future<void> loadPolygonData() async {
    /*if (polygonData.length > 0) {
      return;
    }*/
    String data = await rootBundle.loadString('lib/poly.json');
    polygonData = jsonDecode(data).cast<Map<String, dynamic>>();
    print('eeee ');
    print(polygonData.length);
    for (var dataEntry in polygonData) {
      List<dynamic> coordinates = jsonDecode(dataEntry['coordinates']);
      String colorName = dataEntry['color'];
      String title = dataEntry['title'];
      // int id = int.parse(dataEntry['id']);
      try {
        // Create a polygon
        Polygon polygon = Polygon(
          points: _parsePoints(coordinates),
          fillColor: getColorFromName(colorName).withOpacity(0.3),
          strokeWidth: 2,
          strokeColor: getColorFromName(colorName),
          polygonId: PolygonId(title),
        );

        // Add the polygon to the list
        polygons.add(polygon);

        // Add label
        _addLabelToPolygon(polygon, title);
        print(polygonData.length);
        print('Nombre de polygones : ${polygons.length}');
      } catch (e) {
        print('Erreur lors de la création du polygone $title : $e');
      }
    }

    print('finnn ');
  }

  void _addLabelToPolygon(Polygon polygon, String title) async {
    LatLng centroid = _calculateCentroid(polygon.points);
    final Uint8List markerIcon = (await _createLabelIcon(
      title,
      backgroundColor: Colors.transparent,
      textColor: Colors.white,
    ))!;

    final labelOffset = Offset(0, -20); // Adjust label offset as needed

    setState(() {
      _markers.add(
        Marker(
          markerId: MarkerId(title),
          position: centroid,
          icon: BitmapDescriptor.fromBytes(markerIcon),
          anchor: Offset(labelOffset.dx / markerIcon.length,
              labelOffset.dy / markerIcon.length),
        ),
      );
    });
  }

  LatLng _calculateCentroid(List<LatLng> points) {
    double areaSum = 0;
    double xSum = 0;
    double ySum = 0;

    for (int i = 1; i < points.length - 1; i++) {
      double area = _calculateTriangleArea(points[0], points[i], points[i + 1]);
      areaSum += area;
      LatLng centroid = _calculateTriangleCentroid(
          points[0], points[i], points[i + 1]);
      xSum += area * centroid.longitude;
      ySum += area * centroid.latitude;
    }

    return LatLng(ySum / areaSum, xSum / areaSum);
  }

  double _calculateTriangleArea(LatLng a, LatLng b, LatLng c) {
    return 0.5 * ((b.longitude - a.longitude) * (c.latitude - a.latitude) -
        (b.latitude - a.latitude) * (c.longitude - a.longitude));
  }

  LatLng _calculateTriangleCentroid(LatLng a, LatLng b, LatLng c) {
    return LatLng((a.latitude + b.latitude + c.latitude) / 3,
        (a.longitude + b.longitude + c.longitude) / 3);
  }

  Future<Uint8List?> _createLabelIcon(String label,
      {Color backgroundColor = Colors.white,
        Color textColor = Colors.black}) async {
    final PictureRecorder pictureRecorder = PictureRecorder();
    final Canvas canvas = Canvas(pictureRecorder);
    final Paint paint = Paint()
      ..color = backgroundColor;

    final TextPainter textPainter = TextPainter(
      text: TextSpan(
        text: label,
        style: TextStyle(color: textColor, fontSize: 50),
      ),
      textDirection: TextDirection.ltr,
    );

    textPainter.layout();
    final Size size = Size(textPainter.width + 20, textPainter.height + 20);

    canvas.drawRRect(
      RRect.fromRectAndRadius(
          Rect.fromLTWH(0, 0, size.width, size.height), Radius.circular(10)),
      paint,
    );

    textPainter.paint(canvas, Offset(10, 10));

    final Picture picture = pictureRecorder.endRecording();
    final img = await picture.toImage(size.width.toInt(), size.height.toInt());

    if (img != null) {
      final data = await img.toByteData(format: ui.ImageByteFormat.png);
      return data?.buffer?.asUint8List();
    } else {
      return null;
    }
  }

  LatLng _calculatePolygonCenter(List<LatLng> points) {
    double latitudeSum = 0.0;
    double longitudeSum = 0.0;

    for (LatLng point in points) {
      latitudeSum += point.latitude;
      longitudeSum += point.longitude;
    }

    double latitude = latitudeSum / points.length;
    double longitude = longitudeSum / points.length;

    return LatLng(latitude, longitude);
  }

  Future<void> getLocation() async {
    location_pkg.Location location = location_pkg.Location();
    bool _serviceEnabled;
    location_pkg.PermissionStatus _permissionGranted;
    location_pkg.LocationData _locationData;
    _serviceEnabled = await location.serviceEnabled();
    if (!_serviceEnabled) {
      _serviceEnabled = await location.requestService();
      if (!_serviceEnabled) {
        return;
      }
    }

    _permissionGranted = await location.hasPermission();
    if (_permissionGranted == location_pkg.PermissionStatus.denied) {
      _permissionGranted = await location.requestPermission();
      if (_permissionGranted != location_pkg.PermissionStatus.granted) {
        return;
      }
    }

    _locationData = await location.getLocation();
    setState(() {
      currentLocation =
          LatLng(_locationData.latitude!, _locationData.longitude!);
    });
    _sendNotificationsOnMapLoad();
  }

  Color getColorFromName(String colorName) {
    switch (colorName) {
      case 'red':
        return Colors.red;
      case 'green':
        return Colors.green;
      default:
        return Colors.black;
    }
  }

  bool _isPointInsidePolygon(Position point, List<dynamic> coordinates) {
    int verticesCount = coordinates.length;
    if (verticesCount <= 0) return false;

    bool isInside = false;
    double longitude = point.longitude;
    double latitude = point.latitude;
    int i = 0,
        j = verticesCount - 1;

    for (; i < verticesCount; j = i++) {
      double xi = coordinates[i][0];
      double yi = coordinates[i][1];
      double xj = coordinates[j][0];
      double yj = coordinates[j][1];

      bool intersect = ((yi > latitude) != (yj > latitude)) &&
          (longitude < (xj - xi) * (latitude - yi) / (yj - yi) + xi);

      if (intersect) isInside = !isInside;
    }

    return isInside;
  }

  Future<void> _sendAlert() async {
    final position = await Geolocator.Geolocator.getCurrentPosition(
      desiredAccuracy: Geolocator.LocationAccuracy.high,
    );
    final message = 'Je suis en danger!';
    final coordinates = [
      [position.longitude, position.latitude]
    ];
    final positionData = {'coordinates': coordinates};
    try {
      final apiUrl = dotenv.env['Api_url'];

      final response = await http.post(
        Uri.parse('$apiUrl/api/send-alert'),
        body: json.encode({'message': message, 'position': positionData}),
        headers: {'Content-Type': 'application/json'},
      );
      if (response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Alerte envoyée avec succès!'),
            backgroundColor: Colors.green,
          ),
        );
      } else {
      /*  ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Échec de l\'envoi de l\'alerte'),
            backgroundColor: Colors.red,
          ),
        );*/
      }
    } catch (error) {
    /*  ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Erreur lors de l\'envoi de l\'alerte: $error'),
          backgroundColor: Colors.red,
        ),
      );*/
    }
  }


  Future<void> _sendNotificationsOnMapLoad() async {
    if (currentLocation != null) {
      sendNotification(currentLocation! as Position);
    }
  }

  Future<void> sendNotification(Position position) async {
    String? newZone;
    for (var polygon in polygonData) {
      List<dynamic> coordinates = jsonDecode(polygon['coordinates']);
      String colorName = polygon['color'];
      String title = polygon['title'];
      Color color = getColorFromName(colorName);

      if (_isPointInsidePolygon(position, coordinates)) {
        newZone = title;
        if (color == Colors.red) {
          // Afficher une notification pour une zone interdite
          await AwesomeNotifications().createNotification(
            content: NotificationContent(
              id: 10,
              channelKey: 'basic_channel',
              title: 'Zone de chasse interdite',
              body: 'Vous êtes dans la zone $title',
            ),
          );
        }
        else if (color == Colors.green) {
           // Afficher une notification pour une zone autorisée
           await AwesomeNotifications().createNotification(
             content: NotificationContent(
               id: 20,
               channelKey: 'basic_channel',
               title: 'Zone de chasse autorisée',
               body: 'Vous êtes dans la zone $title',
             ),
           );
         }
        break;
      }
    }
    // Si la nouvelle zone est différente de la dernière zone, mettre à jour la dernière zone
    if (newZone != _lastZone) {
      _lastZone = newZone;
    }
  }

  void _onMapCreated(GoogleMapController controller) {
    setState(() {
      controller = controller;
    });
    if (currentLocation != null) {
      _addLocationMarker(currentLocation!);
      sendNotification(Position.fromMap({
        'latitude': currentLocation!.latitude,
        'longitude': currentLocation!.longitude,
      }));

      _sendLocationUpdate();
    }
  }

  void _addLocationMarker(LatLng position) {
    _markers.add(
      Marker(
        markerId: MarkerId('MyLocation'),
        position: position,
        infoWindow: InfoWindow(
          title: 'Votre position',
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BaseScreen.withFloatingActionButton(

        floatingActionButton: floatingActionButton,
        key: _scaffoldKey,
        child: Scaffold(
          body: Stack(
            children: [
              if (currentLocation != null)
                GoogleMap(
                  onMapCreated: _onMapCreated,
                  mapType: MapType.satellite,
                  // Remove all labels from the map

                  initialCameraPosition: CameraPosition(
                    target: currentLocation!,
                    zoom: 12.0,
                  ),
                  markers: _markers,

                  /* markers: {
                          Marker(
                            markerId: MarkerId('MyLocation'),
                            position: LatLng(currentLocation!.latitude, currentLocation!.longitude),
                            infoWindow: InfoWindow(
                              title: 'Votre position',
                            ),
                          ),
                        },*/
                  polygons: Set<Polygon>.of(polygons),

                ),
              /*Positioned(
                    top: 10.0,
                    left: 10.0,
                    right: 10.0,
                    child: Container(
                      color: Colors.white,
                      child: TextField(
                        decoration: InputDecoration(
                          hintText: 'Search for a place',
                          contentPadding: EdgeInsets.all(10.0),
                        ),
                        onSubmitted: (value) {
                        //  _searchPlace(value);
                        },
                      ),
                    ),
                  ),*/

              /*Center(
                      child: CircularProgressIndicator(),
                    ),*/

              Positioned(
                left: 16.0,
                bottom: 16.0,
                child: FloatingActionButton(
                  heroTag: "btn1",
                  onPressed: () {
                    _sendAlert();
                  },
                  backgroundColor: Colors.red,
                  foregroundColor: Colors.white,
                  child: Icon(Icons.warning),
                ),
              ),
            ],
          ),
        ));
  }

  // Fonctions de gestion du type de carte
  /*void _toggleMapType() {
    setState(() {
      _currentMapType = _currentMapType == MapType.normal
          ? MapType.hybrid
          : MapType.normal;
    });*/
  /*  Future<void> _searchPlace(String query) async {
     final apiKey = 'AIzaSyDDdnJn9ay7gJfLC38n-u6_iLTLcoN5qsM';
     final url =
         'https://maps.googleapis.com/maps/api/place/textsearch/json?query=$query&key=$apiKey';

     final response = await http.get(Uri.parse(url));

     if (response.statusCode == 200) {
       final data = jsonDecode(response.body);
       final results = data['results'] as List<dynamic>;
       if (results.isNotEmpty) {
         final location = results[0]['geometry']['location'];
         final lat = location['lat'];
         final lng = location['lng'];
         final LatLng position = LatLng(lat, lng);
         controller.animateCamera(CameraUpdate.newLatLngZoom(position, 15.0));
       }
     } else {
       throw Exception('Failed to load place');
     }
   }*/

  List<LatLng> _parsePoints(List<dynamic> points) {
    return points
        .map((point) => LatLng(point[1] as double, point[0] as double))
        .toList();
  }



  Widget _buildDialogContent() {
    return StatefulBuilder(
      builder: (BuildContext context, StateSetter setState) {
        return Row(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: <Widget>[
            _buildAnimalCard(AnimalType.rabbit, setState),
            _buildAnimalCard(AnimalType.bird, setState),
            _buildAnimalCard(AnimalType.pig, setState),
          ],
        );
      },
    );
  }

  Widget _buildAnimalCard(AnimalType animalType, StateSetter setState) {
    Color cardColor = _selectedAnimals.contains(animalType) ? Colors.green : Colors.white; // Mettez à jour la couleur de la carte
    IconData iconData = _selectedAnimals.contains(animalType)
        ? Icons.cruelty_free
        : Icons.cruelty_free_outlined; // Mettez à jour l'icône de la carte

    switch (animalType) {
      case AnimalType.bird:
        iconData = _selectedAnimals.contains(animalType)
            ? Icons.flutter_dash
            : Icons.flutter_dash_outlined;
        break;
      case AnimalType.pig:
        iconData = _selectedAnimals.contains(animalType)
            ? Icons.savings
            : Icons.savings_outlined;
        break;
    }

    return Card(
      color: cardColor,
      child: InkWell(
        onTap: () {
          setState(() {
            if (_selectedAnimals.contains(animalType)) {
              _selectedAnimals.remove(animalType);
            } else {
              _selectedAnimals.add(animalType);
            }
          });
        },
        child: Padding(
          padding: const EdgeInsets.all(8.0),
          child: Icon(iconData),
        ),
      ),
    );
  }



}
