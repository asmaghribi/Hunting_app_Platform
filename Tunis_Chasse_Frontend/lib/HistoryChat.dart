import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:tunis_chasse/Services/auth_services.dart';
import 'ChatScreen.dart';

class HistoryChat extends StatefulWidget {
  @override
  _HistoryChatState createState() => _HistoryChatState();
}

class _HistoryChatState extends State<HistoryChat> {
  List<String>? userFirstNames;
  Map<String, String> lastMessages = {};

  @override
  void initState() {
    super.initState();
    _fetchUsers();
  }

  Future<void> _fetchUsers() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? token = prefs.getString('token');
      String? loggedInUserEmail = prefs.getString('email');

      if (loggedInUserEmail != null) {
        var response = await AuthServices.getUserDetails(token);

        if (response.statusCode == 200) {
          List<dynamic> usersData = json.decode(response.body)['users'];

          setState(() {
            userFirstNames = usersData
                .where((userData) => userData['email'] != loggedInUserEmail)
                .map((userData) => userData['Firstname'])
                .cast<String>()
                .toList();

            // Charger les derniers messages pour chaque utilisateur
            _loadLastMessages();
          });
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

  Future<void> _loadLastMessages() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    if (userFirstNames != null) {
      for (var userName in userFirstNames!) {
        List<String>? messages = prefs.getStringList('messages_$userName');
        if (messages != null && messages.isNotEmpty) {
          lastMessages[userName] = messages.last;
        }
      }
      setState(() {});
    }
  }

  void _updateLastMessage(String userName, String message) {
    setState(() {
      lastMessages[userName] = message;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Chat'),
      ),
      body: Column(
        children: [
          // Horizontal Scroll View pour afficher les utilisateurs
          Container(
            height: 100,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: userFirstNames?.length ?? 0,
              itemBuilder: (BuildContext context, int index) {
                if (userFirstNames == null) {
                  return SizedBox(); // Retourne un widget vide s'il n'y a pas d'utilisateurs
                }
                return InkWell(
                  onTap: () {
                    Navigator.of(context).push(MaterialPageRoute(
                      builder: (context) => ChatScreen(
                        userName: userFirstNames![index],
                        onMessageReceived: _updateLastMessage,
                      ),
                    ));
                  },
                  child: Padding(
                    padding: EdgeInsets.all(8.0),
                    child: Column(
                      children: [
                        CircleAvatar(
                          child: Text(userFirstNames![index].substring(0, 1)),
                        ),
                        SizedBox(height: 4),
                        Text(userFirstNames![index]),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
          Expanded(
            child: Container(
              child: ListView.builder(
                itemCount: userFirstNames?.length ?? 0,
                itemBuilder: (BuildContext context, int index) {
                  if (userFirstNames == null) {
                    return SizedBox(); // Retourne un widget vide s'il n'y a pas d'utilisateurs
                  }
                  String userName = userFirstNames![index];
                  String lastMessage = lastMessages[userName] ?? 'Dernier message...';

                  return ListTile(
                    leading: CircleAvatar(
                      child: Text(userName.substring(0, 1)),
                    ),
                    title: Text(userName),
                    subtitle: Text(lastMessage),
                    onTap: () {
                      Navigator.of(context).push(MaterialPageRoute(
                        builder: (context) => ChatScreen(
                          userName: userName,
                          onMessageReceived: _updateLastMessage,
                        ),
                      ));
                    },
                  );
                },
              ),
            ),
          ),
        ],
      ),
    );
  }
}
