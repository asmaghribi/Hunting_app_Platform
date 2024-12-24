import 'package:flutter/material.dart';
class NewsCard extends StatelessWidget {
  final String imagePath;
  final String title;
  final String description;
  final Function()? onTap;

  NewsCard({
    required this.imagePath,
    required this.title,
    required this.description,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      child: Card(
        elevation: 4,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset(imagePath),
            SizedBox(height: 5),
            Text(
              title,
              style: TextStyle(fontSize: 18, color: Colors.black),
            ),
            Text(
              description,
              style: TextStyle(fontSize: 14, color: Colors.blueGrey),
            ),
          ],
        ),
      ),
    );
  }
}
