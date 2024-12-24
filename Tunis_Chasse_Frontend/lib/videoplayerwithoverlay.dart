import 'package:awesome_notifications/awesome_notifications.dart';
import 'package:flutter/material.dart';
import 'package:video_player/video_player.dart';
import 'dart:async';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:tunis_chasse/NewPageWidget.dart';

class VideoPlayerWithOverlay extends StatefulWidget {
  final VideoPlayerController videoPlayerController;

  const VideoPlayerWithOverlay({
    Key? key,
    required this.videoPlayerController,
  }) : super(key: key);

  @override
  _VideoPlayerWithOverlayState createState() => _VideoPlayerWithOverlayState();
}

class _VideoPlayerWithOverlayState extends State<VideoPlayerWithOverlay> {


  @override
  void initState() {
    super.initState();
    AwesomeNotifications().isNotificationAllowed().then((isAllowed) {
      if (!isAllowed) {
        AwesomeNotifications().requestPermissionToSendNotifications();
      }
    });

  }



  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        SizedBox.expand(
          child: FittedBox(
            fit: BoxFit.cover,
            child: SizedBox(
              width: widget.videoPlayerController.value.size?.width ?? 0,
              height: widget.videoPlayerController.value.size?.height ?? 0,
              child: VideoPlayer(widget.videoPlayerController),
            ),
          ),
        ),
        Positioned(
          top: 0,
          left: 0,
          right: 0,
          child: Padding(
            padding: const EdgeInsets.all(8.0),
            child: Image.asset(
              'assets/longshoremen.png',
              width: 260,
              height: 250,
            ),
          ),
        ),
        Positioned(
          bottom: MediaQuery.of(context).size.height * 0.10,
          left: 0,
          right: 0,
          child: Align(
            alignment: Alignment.bottomCenter,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                SizedBox(
                  width: MediaQuery.of(context).size.width * 0.9,
                  height: MediaQuery.of(context).size.height * 0.05,
                  child: ElevatedButton(
                    onPressed: () {


                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (BuildContext context) =>
                              const NewPageWidget(),
                        ),
                      );
                    },
                    style: ButtonStyle(
                      backgroundColor:
                          MaterialStateProperty.all<Color>(Color(0xFF6c7e39)),
                    ),
                    child: const Text(
                      "Log in",
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 25,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }
}
