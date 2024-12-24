import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:geolocator/geolocator.dart';
import 'package:geolocator/geolocator.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:http/http.dart' as http;
import 'package:latlong2/latlong.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import 'MapView.dart';

import '../BottomNavBar.dart';
import '../MapScreen.dart';
import '../Services/auth_services.dart';


class HomeScreen extends StatefulWidget {
  final Position? currentPosition;
  final String? userName;
  final GoogleMapController? controller;

  HomeScreen(
      {Key? key, required this.currentPosition, this.userName, this.controller})
      : super(key: key);

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final Position? _currentPosition = Position(
      longitude: 9.5375,
      latitude: 33.8869,
      timestamp: DateTime.now(),
      accuracy: 0,
      altitude: 0,
      altitudeAccuracy: 0,
      heading: 0,
      headingAccuracy: 0,
      speed: 0,
      speedAccuracy: 0);
  GoogleMapController? controller;

  late String userName = '';

  @override
  void initState() {
    super.initState();
    _getUserDetails();
    fetchProies();
    // late final Position? currentPosition;
  }
  Future<List<Proie>> fetchProies() async {
    final apiUrl = dotenv.env['Api_url'];
    final response = await http.get(
      Uri.parse('$apiUrl/api/getproies'),
      headers: {
        'Access-Control-Allow-Origin': '*',
      },
    );

    if (response.statusCode == 200) {
      final Map<String, dynamic> jsonData = json.decode(response.body);
      final List<dynamic> proiesData = jsonData['proi'];

      return proiesData.map((json) {
        return Proie(
          nom: json['name'],
          image: '$apiUrl/images/${json['image']}', // Concaténer l'URL de base avec le chemin de l'image
        );
      }).toList();
    } else {
      throw Exception('Failed to load proies');
    }
  }


  Future<void> _getUserDetails() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? token = prefs.getString('token');
      String? loggedInUserEmail = prefs.getString('email');

      if (loggedInUserEmail != null) {
        var response = await AuthServices.getUserDetails(token);

        if (response.statusCode == 200) {
          List<dynamic> usersData = json.decode(response.body)['users'];

          Map<String, dynamic>? userData;
          for (var user in usersData) {
            if (user['email'] == loggedInUserEmail) {
              userData = user;
              break;
            }
          }

          if (userData != null) {
            setState(() {
              userName = userData?['Firstname'] ?? '';
            });
          } else {
            print('Logged in user data not found');
          }
        } else {
          print('Failed to get user details: ${response.statusCode}');
        }
      } else {
        print('Logged in user email not found');
      }
    } catch (e) {
      print('Error getting user details: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    //final Future<List<Proie>> _proiesFuture = fetchProies();
    return Scaffold(
      appBar: AppBar(
        title: Text("home"),

      ),
    body:SingleChildScrollView(
        child: Container(
          color: Colors.white,
          padding: EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  CircleAvatar(
                    backgroundImage: AssetImage('assets/avatar.jpg'),
                    radius: 30,
                  ),
                  SizedBox(width: 10),
                  Text(
                    'Bonjour $userName', // Utilisation du nom d'utilisateur récupéré
                    style: TextStyle(
                      fontSize: 25,
                      color: Colors.black,
                    ),
                  ),

                ],
              ),
              SizedBox(height: 30),
              Text(
                'Votre Position',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: Colors.black,
                ),
              ),
              SizedBox(height: 20),
              Stack(children: [
                Container(
                  width: MediaQuery.of(context).size.width,
                  height: 200,
                  child: MapView(
                      currentPosition: _currentPosition,
                      controller: controller),
                )
              ]),
              SizedBox(height: 20),
              CarouselSlider(
                items: imgList.map<Widget>(_buildCarouselItem).toList(),                options: CarouselOptions(
                  height: 200,
                  enlargeCenterPage: true,
                  autoPlay: true,
                  aspectRatio: 16 / 9,
                  autoPlayCurve: Curves.fastOutSlowIn,
                  enableInfiniteScroll: true,
                  autoPlayAnimationDuration: Duration(milliseconds: 800),
                  viewportFraction: 0.8,
                ),
              ),
              SizedBox(height: 20),
              Text(
                'Type De Proie Autorisé ',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: Colors.black,
                ),
              ),
              SizedBox(height: 20),
              FutureBuilder<List<Proie>>(
                future: fetchProies(),
                builder: (BuildContext context, AsyncSnapshot<List<Proie>> snapshot) {
                  if (snapshot.hasError) {
                    return Text('Error: ${snapshot.error}');
                  } else if (!snapshot.hasData) {
                    return CircularProgressIndicator();
                  } else {
                    List<Proie> proies = snapshot.data!;
                    return SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      child: Row(
                        children: proies.map((proie) {
                          return _buildItemContainer(proie.image, proie.nom, '');
                        }).toList(),
                      ),
                    );
                  }
                },
              ),

             
            ],
          ),
        ),

    ),
    );
  }

  Widget _buildItemContainer(String imagePath, String title, [String subtitle = '']) {
    return Container(
      width: 120,
      height: 200, // Définir une hauteur fixe pour la boîte
      margin: EdgeInsets.only(right: 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center, // Aligner les éléments verticalement au centre
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(20), // Définir le rayon de bordure pour que l'image soit arrondie
            child: Image.network(
              imagePath,
              fit: BoxFit.cover, // Définir la propriété BoxFit pour que l'image remplisse la boîte
              errorBuilder: (context, error, stackTrace) {
                return Icon(Icons.error);
              },
              height: 120, // Définir une hauteur fixe pour l'image
              width: 120,
            ),
          ),
          SizedBox(height: 10), // Ajouter un espace entre l'image et le titre
          Container(
            width: 100, // Définir une largeur fixe pour le conteneur du titre
            child: Text(
              title,
              textAlign: TextAlign.center, // Aligner le texte horizontalement au centre
              style: TextStyle(fontSize: 12, color: Colors.black),
            ),
          ),
          SizedBox(height: 5), // Ajouter un espace entre le titre et le sous-titre
          Container(
            width: 100, // Définir une largeur fixe pour le conteneur du sous-titre
            child: Text(
              subtitle,
              textAlign: TextAlign.center, // Aligner le texte horizontalement au centre
              style: TextStyle(fontSize: 14, color: Colors.blueGrey),
            ),
          ),
        ],
      ),
    );
  }
  Widget _buildCarouselItem(String e) {
    return Stack(
      children: [
        Image.asset(
          e,
          fit: BoxFit.cover,
          width: double.infinity,
          height: 200,
        ),

      ],
    );
  }


}


final List<String> imgList = [
  'assets/sayd.png',
  'assets/sayd1.jpg',
  'assets/sayd2.jpg',
  'assets/sayd3.jpg',
];

final List<String> textList = [
  'Texte sur l\'image 1',
  'Texte sur l\'image 2',
  'Texte sur l\'image 3',
  'Texte sur l\'image 4',
];
class Proie {
  final String nom;
  final String image;

  Proie({required this.nom, required this.image});
}
