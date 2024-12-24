import 'package:awesome_notifications/awesome_notifications.dart';
import 'package:flutter/material.dart';

import 'package:video_player/video_player.dart';
import 'package:tunis_chasse/videoplayerwithoverlay.dart';

class SplashScreen extends StatefulWidget {

  const SplashScreen({Key? key}) : super(key: key);
  @override
  _SplashScreenState createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  late VideoPlayerController _controller;

  @override
  void initState() {
    
    super.initState();
    _controller = VideoPlayerController.asset('assets/hunting.mp4');
    _controller.addListener(() {
      if (_controller.value.isInitialized) {
        setState(() {});
      }
    });
    _controller.setLooping(true);
    _controller.setVolume(0.0);
    _controller.initialize().then((_) {
      setState(() {});
    });
    _controller.play();
  }

  @override
  Widget build(BuildContext context) {
    final size = MediaQuery.of(context).size;
    final videoRatio = _controller.value.size != null ? _controller.value.size!.width / _controller.value.size!.height : 0;
    final videoHeight = size.height;
    final videoWidth = videoHeight * videoRatio;

    return Container(
      width: size.width,
      height: size.height,
      child: _controller.value.isInitialized
          ? VideoPlayerWithOverlay(
        videoPlayerController: _controller,
      )
          : Container(),
    );



  }

  @override
  void dispose() {
    super.dispose();
    _controller.dispose();

  }

}
