import 'dart:async';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:tunis_chasse/Services/auth_services.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dart:typed_data';
import 'package:crypto/crypto.dart';
import 'package:flutter_localization/flutter_localization.dart';
import 'locales.dart';
import 'package:flag/flag.dart';

import 'Splash.dart';

class SettingsScreen extends StatefulWidget {
  late final  String _currentLocale;

  @override
  _SettingsScreenState createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen> {
  TextEditingController _firstNameController = TextEditingController();
  TextEditingController _lastNameController = TextEditingController();
  TextEditingController _emailController = TextEditingController();
  TextEditingController _phoneController = TextEditingController();
  TextEditingController _addressController = TextEditingController();
  String? _token;
  // Add controllers for change password form
  TextEditingController _currentPasswordController = TextEditingController();
  TextEditingController _newPasswordController = TextEditingController();
  TextEditingController _confirmPasswordController = TextEditingController();
   late FlutterLocalization _flutterLocalization;
  late String _currentLocale;



  @override
  void initState() {
    super.initState();
    _savePassword();
    _getUserDetails();
    _currentLocale = 'fr';

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
              _firstNameController.text = userData?['Firstname'] ?? '';
              _lastNameController.text = userData?['Lastname'] ?? '';
              _emailController.text = userData?['email'] ?? '';
              _phoneController.text = userData?['phone'] ?? '';
              _addressController.text = userData?['adresse'] ?? '';
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

  Future<void> handleLogout(BuildContext context) async {
    print('Logout function called');

    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? authToken = prefs.getString('authToken');

    if (authToken != null) {
      print('Auth token found');

      try {
        final apiUrll = dotenv.env['Api_url'];
        final response = await http.post(
          Uri.parse('$apiUrll/api/logout'),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $authToken',
          },
        );

        print('Response status code: ${response.statusCode}');

        if (response.statusCode == 200) {
          print('Logout successful');

          await prefs.clear();
          // Vous aurez besoin d'un contexte ici pour naviguer vers SplashScreen
          Navigator.of(context).pushReplacement(
            MaterialPageRoute(builder: (context) => SplashScreen()),
          );
        } else {
          print('Logout failed');
          throw Exception('Failed to logout');
        }
      } catch (e) {
        print('Error during logout: $e');
        // Vous aurez besoin d'un contexte ici pour afficher le SnackBar
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Failed to logout: $e')),
        );
      }
    } else {
      print('Auth token not found');
    }
  }





  Future<void> _savePassword() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? storedPassword = prefs.getString('password');
    String? loggedInUserEmail = prefs.getString('email');

    String currentPassword = _currentPasswordController.text;
    String newPassword = _newPasswordController.text;
    String confirmPassword = _confirmPasswordController.text;

    // Hash the current password

    // Hash the new password
    String hashedNewPassword = sha256.convert(utf8.encode(newPassword)).toString();

    // Compare stored password with hashed current password entered by user
    if (storedPassword != currentPassword) {
      // Handle incorrect current password
    print('mot de passe actuelle incorrecte');
      return;
    }

    // Check if new password matches confirmation
    if (newPassword != confirmPassword) {
      // Handle password mismatch
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text("Les mots de passe ne sont pas identiques"),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    if (currentPassword.isEmpty || newPassword.isEmpty || confirmPassword.isEmpty) {
      // Handle empty fields
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text("Veuillez remplir tous les champs"),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    String? authToken = prefs.getString('authToken');
    final apiUrll = dotenv.env['Api_url'];
    try {
      String apiUrl = '$apiUrll/api/update-password';

      Map<String, String> userData = {
        'email': _emailController.text,
        'password': currentPassword, // Send the hashed current password
        'newpassword' : newPassword // Send the hashed new password
      };

      // Add log to display data before sending
      print('Data to be sent: $userData');

      var response = await http.post(
        Uri.parse(apiUrl),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
        },
        body: jsonEncode(userData),
      );

      if (response.statusCode == 200) {
        // Profile updated successfully
        var responseData = json.decode(response.body);
        print(responseData['message']);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Mot de passe modifié avec succès"),
            backgroundColor: Colors.green,
          ),
        );
        // You may perform additional actions here if needed
      } else {
        // Failed to update profile
        print('Failed to update password: ${response.statusCode}');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Échec de la modification du mot de passe"),
            backgroundColor: Colors.red,
          ),
        );
        // Handle error accordingly
      }
    } catch (e) {
      print('Error updating password: $e');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text("Erreur lors de la modification du mot de passe"),
          backgroundColor: Colors.red,
        ),
      );
      // Handle error accordingly
    }
  }



  Future<void> _saveChanges() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();

    String? authToken = prefs.getString('authToken');
    String? loggedInUserEmail = prefs.getString('email');
    print(loggedInUserEmail);
    final apiUrll = dotenv.env['Api_url'];
    try {
      String apiUrl = '$apiUrll/api/update-profile';

      Map<String, String> userData = {
        'Firstname': _firstNameController.text,
        'Lastname': _lastNameController.text,
        'email': _emailController.text,
        'phone': _phoneController.text,
        'adresse': _addressController.text,
      };

      var response = await http.post(
        Uri.parse(apiUrl),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
        },
        body: jsonEncode(userData),
      );

      if (response.statusCode == 200) {
        // Profile updated successfully
        var responseData = json.decode(response.body);
        print(responseData['message']);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Modifié avec succès'),
            backgroundColor: Colors.green,
          ),
        );
        // You may perform additional actions here if needed
      } else {

        // Failed to update profile
        print('Failed to update profile: ${response.statusCode}');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Erreur lors de la modification'),
            backgroundColor: Colors.red,
          ),
        );
        // Handle error accordingly
      }
    } catch (e) {
      print('Error updating profile: $e');
      // Handle error accordingly
    }
  }
  bool _validateInputs() {
    if (_firstNameController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Veuillez remplir le champ du prénom'),
          backgroundColor: Colors.red,
        ),
      );
      return false;
    }

    if (_lastNameController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Veuillez remplir le champ du nom'),
          backgroundColor: Colors.red,
        ),
      );
      return false;
    }

    if (_emailController.text.isEmpty || !_emailController.text.contains('@')) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Veuillez entrer une adresse e-mail valide'),
          backgroundColor: Colors.red,
        ),
      );
      return false;
    }

    if (_phoneController.text.isEmpty || _phoneController.text.length < 8) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Veuillez entrer un numéro de téléphone valide'),
          backgroundColor: Colors.red,
        ),
      );
      return false;
    }

    if (_addressController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Veuillez entrer une adresse valide'),
          backgroundColor: Colors.red,
        ),
      );
      return false;
    }

    return true;
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2, // Number of tabs
      child: Scaffold(
        appBar: AppBar(
          title: Text( LocaleData.title.getString(context),),
          backgroundColor: Colors.teal,
          elevation: 0,
            actions: [
              DropdownButton<String>(
                value: _currentLocale,
                items: <DropdownMenuItem<String>>[
                DropdownMenuItem<String>(
                  value: "fr",
                  child: Row(
                    children: <Widget>[
                      Flag.fromString('fr', height: 10, width: 10),
                      SizedBox(width: 8),
                      Text("Français"),
                    ],
                  ),
                ),
                DropdownMenuItem<String>(
                  value: "ar",
                  child: Row(
                    children: <Widget>[
                      Flag.fromString('tn', height: 10, width: 10), // Utilisez le code du pays approprié pour l'arabe
                      SizedBox(width: 8),
                      Text("Arabe"),
                    ],
                  ),
                ),
              ],
                onChanged: (value) {
                  _setLocale(value as String?);
                },
              )
            ],
          bottom: TabBar(
            tabs: [
              Tab(text: LocaleData.Profile.getString(context),), // First tab without icon
              Tab(text: LocaleData.changepassword.getString(context),), // Second tab without icon
              // Third tab without icon
            ],
          ),
        ),
        body: TabBarView(
          children: [
            // Profile Tab
            SingleChildScrollView(
              child: Padding(
                padding: EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Card(
                      child: TextFormField(
                        controller: _firstNameController,
                        decoration: InputDecoration(
                          labelText: LocaleData.fname.getString(context),
                          suffixIcon: IconButton(
                            icon: Icon(Icons.edit),
                            onPressed: () {
                              // Mettre en œuvre la logique pour modifier le nom
                            },
                          ),
                        ),
                      ),
                    ),
                    Card(
                      child: TextFormField(
                        controller: _lastNameController,
                        decoration: InputDecoration(
                          labelText: LocaleData.lname.getString(context),
                          suffixIcon: IconButton(
                            icon: Icon(Icons.edit),
                            onPressed: () {
                              // Mettre en œuvre la logique pour modifier le nom
                            },
                          ),
                        ),
                      ),
                    ),
                    Card(
                      child: TextFormField(
                        controller: _emailController,
                        decoration: InputDecoration(
                          labelText: LocaleData.email.getString(context),
                          suffixIcon: IconButton(
                            icon: Icon(Icons.edit),
                            onPressed: () {
                              // Mettre en œuvre la logique pour modifier le nom
                            },
                          ),
                        ),
                      ),
                    ),
                    Card(
                      child: TextFormField(
                        controller: _phoneController,
                        decoration: InputDecoration(
                          labelText: LocaleData.phone.getString(context),
                          suffixIcon: IconButton(
                            icon: Icon(Icons.edit),
                            onPressed: () {
                              // Mettre en œuvre la logique pour modifier le nom
                            },
                          ),
                        ),
                      ),
                    ),
                    Card(
                      child: TextFormField(
                        controller: _addressController,
                        decoration: InputDecoration(
                          labelText: LocaleData.adr.getString(context),
                          suffixIcon: IconButton(
                            icon: Icon(Icons.edit),
                            onPressed: () {
                              // Mettre en œuvre la logique pour modifier le nom
                            },
                          ),
                        ),
                      ),
                    ),
                    SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: () {
                        if (_validateInputs()) {
                          _saveChanges();
                        }
                      }, // Appeler la méthode _saveChanges lors du clic sur le bouton
                      child: Text(LocaleData.savechanges.getString(context)),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.green,
                      ),
                    ),
                    SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: () {
                        handleLogout(context);
                      },
                      child: Text(LocaleData.logout.getString(context)),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.red,
                      ),
                    ),
                  ],
                ),
              ),
            ),
            // Change Password Tab
            SingleChildScrollView(
              child: Padding(
                padding: EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    TextFormField(
                      controller: _currentPasswordController,
                      obscureText: true,
                      decoration: InputDecoration(
                        labelText: LocaleData.passwordactuel.getString(context),
                      ),
                    ),
                    TextFormField(
                      controller: _newPasswordController,
                      obscureText: true,
                      decoration: InputDecoration(
                        labelText: LocaleData.newpass.getString(context),
                      ),
                    ),
                    TextFormField(
                      controller: _confirmPasswordController,
                      obscureText: true,
                      decoration: InputDecoration(
                        labelText: LocaleData.cpass.getString(context),
                      ),
                    ),
                    SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: () {

                        _savePassword();
                      },
                      child: Text(LocaleData.save.getString(context)),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.green,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    _flutterLocalization = FlutterLocalization.instance;
  }
  void _setLocale(String? value) {
    if (value == null) return;
    if (value == "fr") {
      _flutterLocalization.translate("fr");
    } else if (value == "ar") {
      _flutterLocalization.translate("ar");
    }  else {
      return;
    }
    setState(() {
      _currentLocale = value;
    });
  }
}

