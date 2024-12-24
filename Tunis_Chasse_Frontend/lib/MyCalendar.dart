import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

import 'package:syncfusion_flutter_calendar/calendar.dart';
import 'BottomNavBar.dart';
import 'MapScreen.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

class MyCalendar extends StatefulWidget {
  const MyCalendar({Key? key}) : super(key: key);

  @override
  _MyCalendarState createState() => _MyCalendarState();
}

class _MyCalendarState extends State<MyCalendar> {
  List<Meeting> _events = [];

  late FloatingActionButton floatingActionButton;
  final GlobalKey<ScaffoldState> _scaffoldKey = GlobalKey<ScaffoldState>();


  @override
  void initState() {
    super.initState();
    floatingActionButton = new FloatingActionButton(onPressed: () {
      Navigator.of(context).push(
        MaterialPageRoute(
          builder: (context) => MapScreen(),
        ),
      );
    });
    _fetchEvents();
  }

  Future<void> _fetchEvents() async {
    final apiUrl = dotenv.env['Api_url'];
    final response = await http.get(Uri.parse('$apiUrl/api/getcalendar'));


    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body);
      setState(() {
        _events = data.map((event) => Meeting.fromJson(event)).toList();
      });
    } else {
      throw Exception('Failed to load events');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Calendrier"),
      ),
      body: SafeArea(
        child: SfCalendar(
          view: CalendarView.month,
          dataSource: MeetingDataSource(_events),
          monthViewSettings: const MonthViewSettings(
            appointmentDisplayMode: MonthAppointmentDisplayMode.appointment,
          ),
        ),
      ),
    );
  }
}

class MeetingDataSource extends CalendarDataSource {
  MeetingDataSource(List<Meeting> source) {
    appointments = source;
  }

  @override
  DateTime getStartTime(int index) {
    return _getMeetingData(index).from;
  }

  @override
  DateTime getEndTime(int index) {
    return _getMeetingData(index).to;
  }

  @override
  String getSubject(int index) {
    return _getMeetingData(index).eventName;
  }

  @override
  Color getColor(int index) {
    return _getMeetingData(index).background;
  }

  @override
  bool isAllDay(int index) {
    return _getMeetingData(index).isAllDay;
  }

  Meeting _getMeetingData(int index) {
    final dynamic meeting = appointments![index];
    late final Meeting meetingData;
    if (meeting is Meeting) {
      meetingData = meeting;
    }

    return meetingData;
  }
}

class Meeting {
  String eventName;
  DateTime from;
  DateTime to;
  Color background;
  bool isAllDay;

  Meeting(this.eventName, this.from, this.to, this.background, this.isAllDay);

  factory Meeting.fromJson(Map<String, dynamic> json) {
    return Meeting(
      json['title'] as String,
      DateTime.parse(json['start'] as String),
      DateTime.parse(json['end'] as String),
      const Color(0xFF0F8644),
      false,
    );
  }
}
