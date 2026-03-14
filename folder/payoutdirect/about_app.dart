import 'package:flutter/material.dart';

class AboutAppPage extends StatelessWidget {
  const AboutAppPage({super.key});

  @override
  Widget build(BuildContext context) {
    const _ = Color(0xFFFFA500);

    return Scaffold(
      backgroundColor: const Color(0xFFF5F6FA),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 2,
        shape: const RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(bottom: Radius.circular(20)),
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new, color: Colors.black),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text(
          "About App",
          style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold),
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(25),
        child: SingleChildScrollView(   // ✅ allows scrolling if text is long
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: const [
              Text(
                "PayoutDirect",
                style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
              ),
              SizedBox(height: 10),
              Text(
                "PayoutDirect is a mobile application built entirely with Flutter, "
                "designed to modernize the distribution of financial aid for senior citizens "
                "and persons with disabilities (PWDs). It replaces manual, paper-based systems "
                "with a streamlined digital solution that improves speed, transparency, and accessibility.\n\n"
                "The app emphasizes simplicity and clarity, making it usable even for those with limited tech experience. "
                "Its architecture is optimized for cross-platform deployment, ensuring consistent performance across Android and iOS devices.\n\n"
                "👨‍💻 Developers:\n"
                "- Russel Ruiz P. Miranda\n"
                "- Ronen Trinidad\n"
                "- Adrian Gonzales\n"
                "- Karl Villanueva\n\n"
                "Their collaborative work showcases how Flutter can be used to build inclusive, socially impactful financial tools.",
                style: TextStyle(fontSize: 16, height: 1.5),
              ),
              SizedBox(height: 20),
              Text(
                "Version 1.0.0",
                style: TextStyle(color: Colors.grey),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
