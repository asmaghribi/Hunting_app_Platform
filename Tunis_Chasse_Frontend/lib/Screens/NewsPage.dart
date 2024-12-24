import 'package:flutter/material.dart';
class NewsPage extends StatelessWidget {
  final News news;

  NewsPage({required this.news});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(news.title),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.asset(news.imagePath),
            SizedBox(height: 16),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Text(
                news.description,
                style: TextStyle(fontSize: 16),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class News {
  final String imagePath;
  final String title;
  final String description;

  News({
    required this.imagePath,
    required this.title,
    required this.description,
  });
}

