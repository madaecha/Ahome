import 'dart:html';

import 'package:ahome/components/buttons.dart';
import 'package:ahome/components/textfields.dart';
import 'package:ahome/delay_animation.dart';
import 'package:flutter/material.dart';

class LoginPage extends StatelessWidget {

  final void Function()? onTap;

  //text controller
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();

  LoginPage({
    super.key,
    required this.onTap
  });

  //Login methode
  void login() {}

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).colorScheme.background,
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(25.0),
          child: Column(
            children: [
              //Logo
              Icon(
                Icons.person,
                size: 80,
                color: Theme.of(context).colorScheme.inversePrimary,
              ),

              SizedBox(
                height: 25,
              ),

              //App name
              Text(
                "A H O M E",
                style: TextStyle(
                  fontSize: 20,
                ),
              ),

              SizedBox(
                height: 50,
              ),

              //Email textfield
              MyTextField(
                  hintText: "Mail",
                  obscureText: false,
                  controller: emailController,
              ),

              SizedBox(
                height: 20,
              ),

              //password textfield
              MyTextField(
                hintText: "Password",
                obscureText: true,
                controller: passwordController,
              ),

              SizedBox(
                height: 10,
              ),

              //forgot password
              Row(
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  Text(
                    "Forgot password ?",
                    style: TextStyle(
                      color: Theme.of(context).colorScheme.secondary
                    ),
                  ),
                ],
              ),

              SizedBox(
                height: 25,
              ),

              //Sign in button
              MyButton(
                  text: "Login",
                  onTap: login
              ),

              SizedBox(
                height: 25,
              ),

              //Don't have an account ? Registrer here
              Text(
                "Don't have an account ?",
                style: TextStyle(
                    color: Theme.of(context).colorScheme.secondary
                ),
              ),
              GestureDetector(
                onTap: onTap,
                child: Text(
                  "Registrer here",
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
        ),
      )
    );
  }
}
