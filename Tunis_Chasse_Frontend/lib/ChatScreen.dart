import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:web_socket_channel/web_socket_channel.dart';
import 'package:web_socket_channel/status.dart' as status;

class ChatScreen extends StatefulWidget {
  final String userName;
  final Function(String, String) onMessageReceived;

  ChatScreen({required this.userName, required this.onMessageReceived});

  @override
  _ChatScreenState createState() => _ChatScreenState();
}

class _ChatScreenState extends State<ChatScreen> {
  final TextEditingController _messageController = TextEditingController();
  final List<String> _messages = [];
  late WebSocketChannel _channel;

  @override
  void initState() {
    super.initState();
    _loadMessages();
    // Connectez-vous au serveur WebSocket
    _channel = WebSocketChannel.connect(
      Uri.parse('ws://192.168.100.167:8000/chat'),
    );

    // Écoutez les messages entrants
    _channel.stream.listen((message) {
      setState(() {
        // Ajoutez le message reçu à la liste des messages
        _messages.add(' $message');
        widget.onMessageReceived(widget.userName, message);
      });
      _saveMessages();
    });
  }

  Future<void> _loadMessages() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    List<String>? loadedMessages = prefs.getStringList('messages_${widget.userName}');
    if (loadedMessages != null) {
      setState(() {
        _messages.addAll(loadedMessages);
      });
    }
  }

  Future<void> _saveMessages() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.setStringList('messages_${widget.userName}', _messages);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.userName),
      ),
      body: Column(
        children: [
          // Partie des messages
          Expanded(
            child: ListView.builder(
              itemCount: _messages.length,
              itemBuilder: (context, index) {
                final message = _messages[index];
                final isSentMessage = message.startsWith('Envoyé:');
                final cardColor = isSentMessage ? Colors.blue : Colors.grey;
                final alignment = isSentMessage ? Alignment.centerLeft : Alignment.centerRight;
                final borderRadius = BorderRadius.only(
                  topLeft: Radius.circular(20),
                  topRight: Radius.circular(20),
                  bottomLeft: isSentMessage ? Radius.circular(20) : Radius.circular(0),
                  bottomRight: isSentMessage ? Radius.circular(0) : Radius.circular(20),
                );

                return Align(
                  alignment: alignment,
                  child: Padding(
                    padding: EdgeInsets.symmetric(horizontal: 8.0, vertical: 4.0),
                    child: Card(
                      color: cardColor,
                      shape: RoundedRectangleBorder(borderRadius: borderRadius),
                      child: Padding(
                        padding: EdgeInsets.all(8.0),
                        child: Text(
                          message,
                          style: TextStyle(color: Colors.white),
                        ),
                      ),
                    ),
                  ),
                );
              },
            ),
          ),
          // Partie pour taper et envoyer le message
          Padding(
            padding: EdgeInsets.all(8.0),
            child: Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _messageController,
                    decoration: InputDecoration(
                      hintText: 'Tapez votre message...',
                    ),
                  ),
                ),
                IconButton(
                  icon: Icon(Icons.send),
                  onPressed: () {
                    if (_messageController.text.isNotEmpty) {
                      // Ajoutez le message envoyé à la liste des messages
                      setState(() {
                        _messages.add(' ${_messageController.text}');
                        widget.onMessageReceived(widget.userName, _messageController.text);
                      });

                      // Envoyez le message via WebSocket
                      _channel.sink.add(_messageController.text);
                      _messageController.clear();
                      _saveMessages();
                    }
                  },
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  @override
  void dispose() {
    _channel.sink.close(status.goingAway);
    _messageController.dispose();
    super.dispose();
  }
}
