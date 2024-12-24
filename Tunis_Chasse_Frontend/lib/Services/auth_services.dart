import 'dart:convert';

import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

import '../Splash.dart';

class AuthServices {
  static Future<Map<String, dynamic>> login(
      String email, String password) async {
    // URL de votre endpoint de connexion Laravel
    final apiUrl = dotenv.env['Api_url'];
    Uri url = Uri.parse(
        '$apiUrl/api/login'); // Mettez votre propre URL

    // Construire le corps de la requête
    Map<String, String> data = {
      'email': email,
      'password': password,
    };

    try {
      var response = await http.post(
        url,
        body: data,
      );

      if (response.statusCode == 200) {
        return json.decode(response.body);
      } else {
        // La demande a échoué avec un code de statut autre que 200
        throw Exception('Failed with status code: ${response.statusCode}');
      }
    } catch (e) {
      // Une erreur s'est produite lors de la tentative de connexion
      throw Exception('Error: $e');
    }
  }

  static Future<http.Response> getUserDetails(String? token) async {
    final apiUrl = dotenv.env['Api_url'];
    Uri url = Uri.parse('$apiUrl/api/user');

    try {
      var response = await http.get(
        url,
        headers: {
          'Authorization': 'Bearer $token',
        },
      );
      return response;
    } catch (e) {
      throw Exception('Error: $e');
    }
  }




}
