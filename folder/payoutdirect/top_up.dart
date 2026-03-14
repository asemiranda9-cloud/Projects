import 'package:flutter/material.dart';
import 'package:modal_bottom_sheet/modal_bottom_sheet.dart';

import '../widgets/top_up_sheet.dart';

class TopUpPage extends StatefulWidget {
  const TopUpPage({super.key});

  @override
  State<TopUpPage> createState() => _TopUpPageState();
}

class _TopUpPageState extends State<TopUpPage> {
  String selectedProvider = 'Landbank';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F6FA),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 2,
        shape: const RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(bottom: Radius.circular(20)),
        ),
        leading: IconButton(
          onPressed: () => Navigator.pop(context),
          icon: const Icon(Icons.arrow_back_ios_new, color: Colors.black),
        ),
        title: const Text(
          "Top Up",
          style: TextStyle(fontWeight: FontWeight.w700, color: Colors.black),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(25),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text("Bank Transfer", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 15),

            PaymentProvider(
              image: "assets/landbank.jpg",
              name: "Landbank",
              account: "*** *** *** 47",
              isSelected: selectedProvider == 'Landbank',
              onChanged: (value) => setState(() => selectedProvider = 'Landbank'),
            ),
            PaymentProvider(
              image: "assets/bdo.jpg",
              name: "BDO",
              account: "*** *** *** 11",
              isSelected: selectedProvider == 'BDO',
              onChanged: (value) => setState(() => selectedProvider = 'BDO'),
            ),

            const SizedBox(height: 25),
            const Text("Other", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 10),

            PaymentProvider(
              image: "assets/gcash.png",
              name: "Gcash",
              account: "Easy payment",
              isSelected: selectedProvider == 'Gcash',
              onChanged: (value) => setState(() => selectedProvider = 'Gcash'),
            ),
            PaymentProvider(
              image: "assets/PayMaya.png",
              name: "Maya",
              account: "Easy payment",
              isSelected: selectedProvider == 'Maya',
              onChanged: (value) => setState(() => selectedProvider = 'Maya'),
            ),
            PaymentProvider(
              image: "assets/paypal.jpg",
              name: "Paypal",
              account: "Easy payment",
              isSelected: selectedProvider == 'Paypal',
              onChanged: (value) => setState(() => selectedProvider = 'Paypal'),
            ),

            const SizedBox(height: 35),

            SizedBox(
              width: double.infinity,
              height: 60,
              child: ElevatedButton(
                onPressed: () {
                  showBarModalBottomSheet(
                    context: context,
                    shape: const RoundedRectangleBorder(
                      borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
                    ),
                    builder: (context) => TopUpBottomSheet(
                      selectedProvider: selectedProvider,
                      image: getImageForProvider(selectedProvider),
                      account: getAccountForProvider(selectedProvider),
                    ),
                  );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                  elevation: 5,
                ),
                child: const Text("Confirm", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  String getAccountForProvider(String provider) {
    switch (provider) {
      case 'Landbank':
      case 'BDO':
        return '*** *** *** 11';
      default:
        return 'Easy Payment';
    }
  }

  String getImageForProvider(String provider) {
    switch (provider) {
      case 'Landbank':
        return 'assets/landbank.jpg';
      case 'BDO':
        return 'assets/bdo.jpg';
      case 'Gcash':
        return 'assets/gcash.png';
      case 'Maya':
        return 'assets/PayMaya.png';
      case 'Paypal':
        return 'assets/paypal.jpg';
      default:
        return 'assets/default.png';
    }
  }
}

class PaymentProvider extends StatelessWidget {
  const PaymentProvider({
    super.key,
    required this.image,
    required this.name,
    required this.account,
    required this.isSelected,
    required this.onChanged,
  });

  final String image;
  final String name;
  final String account;
  final bool isSelected;
  final ValueChanged<bool?> onChanged;

  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 250),
      margin: const EdgeInsets.symmetric(vertical: 8),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.06),
            blurRadius: 8,
            offset: const Offset(0, 4),
          )
        ],
        border: Border.all(
          color: isSelected ? Colors.orange : Colors.black12,
          width: 2,
        ),
      ),
      child: ListTile(
        leading: CircleAvatar(
          radius: 22,
          backgroundColor: Colors.grey.shade100,
          child: ClipOval(
            child: SizedBox(
              width: 44,
              height: 44,
              child: Image.asset(
                image,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) =>
                    const Icon(Icons.account_balance, size: 26, color: Colors.orange),
              ),
            ),
          ),
        ),
        title: Text(name, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 16)),
        subtitle: Text(account, style: const TextStyle(color: Colors.grey)),
        trailing: Transform.scale(
          scale: 1.2,
          child: Radio.adaptive(
            value: true,
            groupValue: isSelected,
            onChanged: onChanged,
            activeColor: Colors.orange,
          ),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      ),
    );
  }
}
