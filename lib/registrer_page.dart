import 'dart:html';

import 'package:ahome/components/buttons.dart';
import 'package:ahome/components/textfields.dart';
import 'package:ahome/delay_animation.dart';
import 'package:flutter/material.dart';

class RegistrerPage extends StatelessWidget {

  final void Function()? onTap;

  RegistrerPage({
    super.key,
    required this.onTap
});

  //text controller
  final TextEditingController usernamController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final TextEditingController confirmpwController = TextEditingController();

  //Registrer methode
  void registrer() {}

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

                //Username textfield
                MyTextField(
                  hintText: "Username",
                  obscureText: false,
                  controller: usernamController,
                ),

                SizedBox(
                  height: 10,
                ),

                //Email textfield
                MyTextField(
                  hintText: "Mail",
                  obscureText: false,
                  controller: emailController,
                ),

                SizedBox(
                  height: 10,
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

                //confirm password textfield
                MyTextField(
                  hintText: "Confirm password",
                  obscureText: true,
                  controller: confirmpwController,
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

                //Registrer button
                MyButton(
                    text: "Registrer",
                    onTap: registrer,
                ),

                SizedBox(
                  height: 25,
                ),

                //Already have an account ? Login here
                Text(
                  "Already have an account ?",
                  style: TextStyle(
                      color: Theme.of(context).colorScheme.inversePrimary
                  ),
                ),
                GestureDetector(
                  onTap: onTap,
                  child: Text(
                    "Login here",
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
