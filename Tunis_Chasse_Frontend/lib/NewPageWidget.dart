

import 'package:awesome_notifications/awesome_notifications.dart';
import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:flutter/services.dart';
import 'package:geolocator/geolocator.dart';
import 'package:geolocator/geolocator.dart';

import 'package:http/http.dart' as http;

import 'package:tunis_chasse/Services/auth_services.dart';
import 'package:latlong2/latlong.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:tunis_chasse/SettingsScreen.dart';
import 'package:tunis_chasse/MapScreen.dart';

import 'lois.dart';




class NewPageWidget extends StatefulWidget {
  const NewPageWidget({Key? key, this.currentPosition}) : super(key: key);
  final Position? currentPosition;
  @override
  _NewPageWidgetState createState() => _NewPageWidgetState();
}

class _NewPageWidgetState extends State<NewPageWidget> {
  late TextEditingController _emailController;
  late TextEditingController _passwordController;
  late bool _passwordVisible;
  String _email = '';
  String _password = '';
  String? _polygontitle;


  String foundPolygonTitle = '';


  Position? _currentPosition= Position( longitude: 9.5375,
      latitude: 33.8869,
      timestamp: DateTime.now(),
      accuracy: 0,
      altitude: 0,
      altitudeAccuracy: 0,
      heading: 0,
      headingAccuracy: 0,
      speed: 0,
      speedAccuracy: 0) ;
  @override
  void initState() {
    late final Position? currentPosition;
    super.initState();
    _emailController = TextEditingController();
    _passwordController = TextEditingController();
    _passwordVisible = false;
    //fetchNotifications();
    _getCurrentLocation();
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



  }

  Future<void> _getCurrentLocation() async {
    final position = await Geolocator.getCurrentPosition(
      desiredAccuracy: LocationAccuracy.high,
    );
    setState(() {
      _currentPosition = position;
    });
  }





  void loginPressed() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? token = prefs.getString('authToken');

    if (token != null) {
      // L'utilisateur est déjà connecté, naviguez vers MapScreen
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => MapScreen()),
      );
      return;
    }

    String email = _emailController.text.trim();
    String password = _passwordController.text.trim();

    // Vérifier si l'email est valide
    if (!RegExp(r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+").hasMatch(email)) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Email invalide'),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    try {
      var response = await AuthServices.login(email, password);
      if (response['token'] != null) {
        String token = response['token'];
        // Ne stockez pas l'email et le mot de passe pour des raisons de sécurité
        // prefs.setString('email', email);
        // prefs.setString('password', password);
        await prefs.setString('authToken', token);

        // Si la connexion réussit, naviguez vers MapScreen
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => MapScreen()),
        );
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Connection successful'),
            backgroundColor: Colors.green,
          ),
        );
      } else if (response['message'] == 'Invalid email') {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Email incorrect'),
            backgroundColor: Colors.red,
          ),
        );
      } else if (response['message'] == 'Invalid password') {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Mot de passe incorrect'),
            backgroundColor: Colors.red,
          ),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Email et mot de passe incorrects'),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      // Gérer les erreurs ici
      print('Error: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('An error occurred. Please try again.'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }


  bool _isInsidePolygon(LatLng point, List<LatLng> polygon) {
    bool inside = false;
    for (int i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
      if ((polygon[i].latitude > point.latitude != polygon[j].latitude > point.latitude) &&
          (point.longitude < (polygon[j].longitude - polygon[i].longitude) * (point.latitude - polygon[i].latitude) / (polygon[j].latitude - polygon[i].latitude) + polygon[i].longitude)) {
        inside = !inside;
      }
    }
    return inside;
  }


  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    double screenWidth = MediaQuery.of(context).size.width;

    return Scaffold(
      backgroundColor: Color(0xFFF1F4F8),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Image.asset(
                'assets/sayed.png',
                width: screenWidth,
                height: screenWidth * 240 / 360, // Ratio 240:360
                fit: BoxFit.cover,
              ),
              Padding(
                padding: EdgeInsets.all(screenWidth * 0.08), // 8% of screen width
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Sign In',
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.black,
                      ),
                    ),
                    const SizedBox(height: 24),
                    TextFormField(
                      controller: _emailController,
                      decoration: const InputDecoration(
                        labelText: 'email',
                        hintText: 'Enter your email',
                      ),
                      onChanged: (value) {
                        _email = value;
                      },
                    ),
                    SizedBox(height: screenWidth * 0.04), // 4% of screen width
                    TextFormField(
                      controller: _passwordController,
                      obscureText: !_passwordVisible,
                      decoration: InputDecoration(
                        labelText: 'password',
                        hintText: 'Enter your password',
                        suffixIcon: IconButton(
                          icon: Icon(_passwordVisible
                              ? Icons.visibility
                              : Icons.visibility_off),
                          onPressed: () {
                            setState(() {
                              _passwordVisible = !_passwordVisible;
                            });
                          },
                        ),
                      ),
                      onChanged: (value) {
                        _password = value;
                      },
                    ),
                    SizedBox(height: screenWidth * 0.04), // 4% of screen width
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [

                        ElevatedButton(
                          onPressed: () {
                            loginPressed();
                          },
                          child: const Text('Log in'),
                        ),
                      ],
                    ),

                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}