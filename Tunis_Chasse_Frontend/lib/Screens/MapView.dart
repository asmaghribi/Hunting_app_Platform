import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart' show Uint8List, rootBundle;
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:location/location.dart' as location_pkg;
import 'package:http/http.dart' as http;
import 'package:awesome_notifications/awesome_notifications.dart';
import 'package:geolocator/geolocator.dart' as Geolocator;

import 'dart:ui' as ui;
class MapView extends StatefulWidget {
  final Position? currentPosition;

  final GoogleMapController? controller;

  const MapView({Key? key, this.currentPosition, this.controller}) : super(key: key);

  @override
  _MapViewState createState() => _MapViewState();
}

class _MapViewState extends State<MapView> {
   GoogleMapController? controller;
  Map<String, String> polygonTitles = {};
  MapType _currentMapType = MapType.hybrid;
  List<Map<String, dynamic>> polygonData = [];
  List<Polygon> polygons = [];
  LatLng? currentLocation;
  Set<Marker> _markers = {};
  StreamSubscription<Position>? _positionSubscription;
  @override
  void initState() {

    super.initState();


    getLocation();

    _positionSubscription = Geolocator.Geolocator.getPositionStream().listen((Position position) {
      setState(() {
        currentLocation = LatLng(position.latitude, position.longitude);
        _addLocationMarker(currentLocation!);
      });

    });

  }
  void _onMapCreated(GoogleMapController controller) {
    setState(() {
      controller = controller;
    });
    if (currentLocation != null) {
      _addLocationMarker(currentLocation!);

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
      currentLocation = LatLng(_locationData.latitude!, _locationData.longitude!);
    });

  }

  @override
  Widget build(BuildContext context) {
    if (currentLocation != null) {
      return GoogleMap(
        onMapCreated: _onMapCreated,
        mapType: _currentMapType,
        initialCameraPosition: CameraPosition(
          target: currentLocation!,
          zoom: 12.0,
        ),
        markers: _markers,
        polygons: Set<Polygon>.of(polygons),
      );
    } else {
      return Center(
        child: CircularProgressIndicator(),
      );
    }
  }

}
