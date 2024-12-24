import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:provider/provider.dart';
import 'dart:async';
import 'dart:io';


import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:path_provider/path_provider.dart';
import 'package:flutter_pdfview/flutter_pdfview.dart';


import 'HistoryChat.dart';
import 'MapScreen.dart';
import 'MyCalendar.dart';
import 'Screens/home_screen.dart';
import 'SettingsScreen.dart';
import 'Splash.dart';
import 'WeatherScreen.dart';
import 'lois.dart';

class BaseScreen extends StatefulWidget {
  final Widget child;


  //Position? _currentPosition;

  final FloatingActionButton? floatingActionButton;
  BaseScreen({
    Key? key,
    required this.child,
    this.floatingActionButton,
  }) : super(key: key);

  BaseScreen.withFloatingActionButton({
    Key? key,
    required this.child,
    required FloatingActionButton floatingActionButton
  })  : this.floatingActionButton = floatingActionButton,
        super(key: key);

  @override
  _BaseScreenState createState() => _BaseScreenState();
}

class _BaseScreenState extends State<BaseScreen> {
  Widget? child;
  GoogleMapController? controller;
  String pathPDF = "";
  Position? _currentPosition = Position(
      longitude: 0,
      latitude: 0,
      timestamp: DateTime(1000),
      accuracy: 0,
      altitude: 0,
      altitudeAccuracy: 0,
      heading: 0,
      headingAccuracy: 0,
      speed: 0,
      speedAccuracy: 0);

  void initState() {
    super.initState();
    fromAsset('assets/lois.pdf', 'lois.pdf').then((f) {
      setState(() {
        pathPDF = f.path;
      });
    });

    controller = controller;
  }

  Future<File> fromAsset(String asset, String filename) async {
    // To open from assets, you can copy them to the app storage folder, and the access them "locally"
    Completer<File> completer = Completer();

    try {
      var dir = await getApplicationDocumentsDirectory();
      File file = File("${dir.path}/$filename");
      var data = await rootBundle.load(asset);
      var bytes = data.buffer.asUint8List();
      await file.writeAsBytes(bytes, flush: true);
      completer.complete(file);
    } catch (e) {
      throw Exception('Error parsing asset file!');
    }

    return completer.future;
  }

  Widget _buildBottomNavigation(BuildContext context) {
    return BottomAppBar(
      color: Colors.white,
      shape: CircularNotchedRectangle(),
      notchMargin: 5,
      child: Row(
        mainAxisSize: MainAxisSize.max,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: <Widget>[
          IconButton(
            icon: Icon(Icons.home, color: Colors.green),
            onPressed: () {
              Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (context) => HomeScreen(
                    currentPosition: _currentPosition,
                    controller: controller,
                  ),
                ),
              );
            },
          ),
         IconButton(
          icon: Icon(Icons.wb_sunny, color: Colors.green), onPressed: () {
           Navigator.of(context).push(
             MaterialPageRoute(
               builder: (context) => WeatherScreen(

               ),
             ),
           );
         },),
          IconButton(
            icon: Icon(Icons.balance, color: Colors.green),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => pathPDF != null && pathPDF.isNotEmpty
                      ? Generallois(path: pathPDF)
                      : SplashScreen(),
                ),
              );
            },
          ),
          SizedBox(width: 20),
          IconButton(
            icon: Icon(Icons.calendar_month, color: Colors.green),
            onPressed: () {
              Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (context) => MyCalendar(),
                ),
              );
            },
          ),
          IconButton(
            icon: Icon(Icons.message),
            color: Colors.green,
            onPressed: () {
              Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (context) => HistoryChat(),
              ),
              );
            },
          ),
          IconButton(
            icon: Icon(Icons.settings, color: Colors.green),
            onPressed: () {
              Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (context) => SettingsScreen(),
                ),
              );
            },
          ),
        ],
      ),
    );
  }

  Widget _buildFloatingActionButton(BuildContext context) {
    return FloatingActionButton(
      heroTag: "btn3",
      onPressed: () {

      },
      backgroundColor: Colors.green,
      foregroundColor: Colors.white,
      child: Icon(Icons.location_on),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        floatingActionButton: _buildFloatingActionButton(context),
        floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
        bottomNavigationBar: _buildBottomNavigation(context),
        body: widget.child);
  }
}
