import 'package:ahome/login_page.dart';
import 'package:ahome/registrer_page.dart';
import 'package:flutter/material.dart';

class LoginOrRegistrer extends StatefulWidget {
  const LoginOrRegistrer({Key? key}) : super(key: key);

  @override
  State<LoginOrRegistrer> createState() => _LoginOrRegistrerState();
}

class _LoginOrRegistrerState extends State<LoginOrRegistrer> {
  //initially, show login page
  bool showLoginPage = true;

  //toogles between Login and registrer page
  void tooglesPage() {
    setState(() {
      showLoginPage = !showLoginPage;
    });
  }

  @override
  Widget build(BuildContext context) {
    if (showLoginPage) {
      return LoginPage(onTap: tooglesPage);
    } else {
      return RegistrerPage(onTap: tooglesPage);
    }
  }
}

