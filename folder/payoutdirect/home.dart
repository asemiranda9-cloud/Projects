import 'package:flutter/material.dart';

import '../widgets/action_button.dart';
import '../widgets/credit_card.dart';
import '../widgets/transaction_list.dart';

class Home extends StatelessWidget {
  const Home({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color.fromARGB(255, 255, 165, 0), // Orange brand color
      body: SafeArea(
        bottom: false,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header Section
            Padding(
              padding: const EdgeInsets.all(20.0),
              child: Row(
                children: [
                  const Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        "Welcome back!",
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 14,
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                      SizedBox(height: 4),
                      Text(
                        "Adrian Gonzales",
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 24,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ],
                  ),
                  const Spacer(),
                  IconButton.outlined(
                    onPressed: () {},
                    icon: const Icon(
                      Icons.notifications,
                      color: Colors.white,
                    ),
                  ),
                ],
              ),
            ),

            // Main Content
            Expanded(
              child: Stack(
                children: [
                  // White background container with rounded top corners
                  Container(
                    margin: const EdgeInsets.only(top: 180),
                    decoration: const BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.vertical(
                        top: Radius.circular(30),
                      ),
                    ),
                    child: SingleChildScrollView(
                      padding: const EdgeInsets.only(top: 120, bottom: 30),
                      child: Column(
                        children: const [
                          // Action Buttons
                          ActionButtons(),
                          SizedBox(height: 30),
                          // Transaction List
                          TransactionList(),
                        ],
                      ),
                    ),
                  ),

                  // Credit Card Positioned
                  const Positioned(
                    top: 40,
                    left: 25,
                    right: 25,
                    child: CreditCard(),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
