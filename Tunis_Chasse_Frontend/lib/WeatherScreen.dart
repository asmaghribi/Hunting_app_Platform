import 'dart:convert';
import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:latlong2/latlong.dart';
import 'envdata.dart';
import 'weather_forecast_item.dart';
import 'additionalinfoitem.dart';
import 'package:geolocator/geolocator.dart';
import 'package:location/location.dart' as location_pkg;

class WeatherScreen extends StatefulWidget {
  const WeatherScreen({super.key});

  @override
  State<WeatherScreen> createState() => _WeatherScreenState();
}

class _WeatherScreenState extends State<WeatherScreen> {
  Future<Map<String, dynamic>>? _weatherData;
  LatLng? currentLocation;
  double temp = 0;
  String locationName = '';
  String cityName = '';

  @override
  void initState() {
    super.initState();
    getCurrentLocation().then((value) => getLocationName(value)).then((value) => setState(() {
      cityName = value;
    }));
    _weatherData = getWeatherData();
  }

  Future<Position> getCurrentLocation() async {
    bool serviceEnabled;
    LocationPermission permission;

    serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      throw 'Le service de localisation est désactivé.';
    }

    permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        throw 'Les permissions de localisation sont refusées.';
      }
    }

    return await Geolocator.getCurrentPosition();
  }

  Future<String> getLocationName(Position currentLocation) async {
    final res = await http.get(
      Uri.parse(
        'https://api.opencagedata.com/geocode/v1/json?q=${currentLocation.latitude}+${currentLocation.longitude}&key=5ac3ddefb97f44db8e71f27449bd5214',
      ),
    );

    final data = jsonDecode(res.body);

    if (data['status']['code'] != 200) {
      throw 'An error occurred: ${data['status']['message']}';
    }

    if (data['results'].isEmpty) {
      throw 'No results found';
    }

    final Map<String, dynamic> components = data['results'][0]['components'];

    String locationName = '';

    if (components.containsKey('city')) {
      locationName = components['city'];
    } else if (components.containsKey('town')) {
      locationName = components['town'];
    } else if (components.containsKey('village')) {
      locationName = components['village'];
    } else if (components.containsKey('county')) {
      locationName = components['county'];
    } else if (components.containsKey('state')) {
      locationName = components['state'];
    } else {
      throw 'No location name found';
    }

    return locationName;
  }




  Future<Map<String, dynamic>> getWeatherData() async {
    Position currentLocation;
    String locationName;

    try {
      currentLocation = await getCurrentLocation();
      locationName = await getLocationName(currentLocation);

      setState(() {
        this.currentLocation = LatLng(currentLocation.latitude, currentLocation.longitude);
        this.cityName = locationName;
      });

      final res = await http.get(
        Uri.parse(
          'http://api.weatherapi.com/v1/forecast.json?key=137a4bb281cd4e31b4b150450242706&q=$locationName&days=7',
        ),
      );

      final data = jsonDecode(res.body);

      if (data['error'] != null) {
        throw 'An error occurred: ${data['error']['message']}';
      }

      dynamic tempData = data['forecast']['forecastday'][0]['day']['avgtemp_c'];
      if (tempData is int) {
        temp = tempData.toDouble();
      } else if (tempData is double) {
        temp = tempData;
      } else {
        throw 'Unexpected temperature data type';
      }

      return data;
    } catch (e) {
      throw e.toString();
    }
  }


  IconData getWeatherIcon(String condition) {
    switch (condition.toLowerCase()) {
      case 'clear':
      case 'sunny':
        return Icons.wb_sunny;
      case 'cloudy':
      case 'overcast':
        return Icons.cloud;
      case 'rain':
      case 'drizzle':
        return Icons.beach_access;
      case 'snow':
      case 'sleet':
        return Icons.ac_unit;
      case 'thunderstorm':
        return Icons.thunderstorm;
      case 'mist':
      case 'fog':
        return Icons.visibility_off;
      default:
        return Icons.wb_twilight;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [Color(0xFF215f00), Color(0xFFe4e4d9)],
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
        ),
      ),
      child: Scaffold(
        backgroundColor: Colors.transparent,
        appBar: AppBar(
          backgroundColor: Colors.transparent,
          title: Text(
            cityName.isEmpty ? 'Météo' : 'Météo - $cityName',
            style: TextStyle(
              fontWeight: FontWeight.bold,
            ),
          ),
          centerTitle: true,
          actions: [
            IconButton(
              onPressed: () {
                setState(() {
                  getWeatherData();
                });
              },
              icon: const Icon(Icons.refresh),
              tooltip: 'Refresh',
            ),
          ],
        ),
        body: FutureBuilder(
          future: _weatherData,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(
                child: CircularProgressIndicator(),
              );
            }
            if (snapshot.hasError) {
              return Center(
                child: Text(snapshot.error.toString()),
              );
            }

            final Map<String, dynamic> data = snapshot.data as Map<String, dynamic>;
            final currentWeatherData = data['current'];
            final currentTemp = currentWeatherData['temp_c'];
            final currentSky = currentWeatherData['condition']['text'];
            final pressure = currentWeatherData['pressure_mb'];
            final windspeed = currentWeatherData['wind_kph'];
            final humidity = currentWeatherData['humidity'];

            return Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                children: [
                  SizedBox(
                    width: double.infinity,
                    child: Card(
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16),
                      ),
                      elevation: 20,
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(16),
                        child: BackdropFilter(
                          filter: ImageFilter.blur(
                            sigmaX: 10,
                            sigmaY: 10,
                          ),
                          child: Padding(
                            padding: const EdgeInsets.only(top: 16, bottom: 16),
                            child: Column(
                              children: [
                                Text(
                                  '$currentTemp° C',
                                  style: const TextStyle(
                                    fontSize: 32,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                const SizedBox(
                                  height: 16,
                                ),
                                Icon(
                                  getWeatherIcon(currentSky),
                                  size: 64,
                                  color: Colors.blue.withOpacity(0.5),
                                ),
                                const SizedBox(
                                  height: 16,
                                ),
                                Text(
                                  '$currentSky',
                                  style: const TextStyle(
                                    fontSize: 20,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(
                    height: 20,
                  ),
                  const Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'la prévision météo',
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                  const SizedBox(
                    height: 12,
                  ),
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        for (int i = 0; i < 7; i++)
                          HourlyForecast(
                            time: data['forecast']['forecastday'][i]['date'],
                            icon: getWeatherIcon(data['forecast']['forecastday'][i]['day']['condition']['text']),
                            temp: data['forecast']['forecastday'][i]['day']['avgtemp_c'].toStringAsFixed(2),
                          ),
                      ],
                    ),
                  ),
                  const SizedBox(
                    height: 16,
                  ),
                  const Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'autres Informations',
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                  const SizedBox(
                    height: 16,
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      AdditionalnfoItem(
                        icon: Icons.water_drop,
                        label: 'Humidité',
                        value: humidity.toString(),
                      ),
                      AdditionalnfoItem(
                        icon: Icons.air_rounded,
                        label: 'Windspeed',
                        value: windspeed.toString(),
                      ),
                      AdditionalnfoItem(
                        icon: Icons.beach_access,
                        label: 'Pression',
                        value: pressure.toString(),
                      ),
                    ],
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}
