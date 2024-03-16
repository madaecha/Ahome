import 'package:ahome/authentifications/login_or_registrer.dart';
import 'package:ahome/themes/dark_mode.dart';
import 'package:ahome/themes/light_mode.dart';
import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'firebase_options.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp(
    options: DefaultFirebaseOptions.currentPlatform,
  );
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Ahome App',
      debugShowCheckedModeBanner: false,
      home: const LoginOrRegistrer(),
      theme: lightMode,
      darkTheme: darkMode,
    );
  }
}
