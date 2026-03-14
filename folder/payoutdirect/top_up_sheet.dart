import 'package:flutter/material.dart';

class TopUpBottomSheet extends StatefulWidget {
  final String selectedProvider;
  final String account;
  final String image;

  const TopUpBottomSheet({
    super.key,
    required this.selectedProvider,
    required this.account,
    required this.image,
  });

  @override
  State<TopUpBottomSheet> createState() => _TopUpBottomSheetState();
}

class _TopUpBottomSheetState extends State<TopUpBottomSheet> {
  double amount = 100.0;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.7,
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // --- Provider Card ---
          Container(
            padding: const EdgeInsets.all(14),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(18),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.06),
                  blurRadius: 8,
                  offset: const Offset(0, 4),
                )
              ],
              border: Border.all(color: Colors.black12),
            ),
            child: Row(
              children: [
                CircleAvatar(
                  radius: 20,
                  backgroundColor: Colors.grey.shade100,
                  child: ClipOval(
                    child: SizedBox(
                      width: 40,
                      height: 40,
                      child: Image.asset(
                        widget.image,
                        fit: BoxFit.cover,
                        errorBuilder: (context, error, stackTrace) =>
                            const Icon(Icons.account_balance, size: 22, color: Colors.orange),
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        widget.selectedProvider,
                        style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 16),
                      ),
                      Text(
                        widget.account,
                        style: const TextStyle(color: Colors.grey),
                      ),
                    ],
                  ),
                ),
                const Icon(Icons.keyboard_arrow_down, size: 28, color: Colors.grey),
              ],
            ),
          ),
          const SizedBox(height: 25),

          // --- Amount Label ---
          const Text(
            "Amount",
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 12),

          // --- Amount Controls ---
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              _AmountButton(
                icon: Icons.remove,
                onTap: () {
                  setState(() {
                    if (amount > 5) amount -= 5;
                  });
                },
              ),
              Text(
                "₱ ${amount.toStringAsFixed(0)}",
                style: const TextStyle(fontSize: 26, fontWeight: FontWeight.bold),
              ),
              _AmountButton(
                icon: Icons.add,
                onTap: () {
                  setState(() {
                    amount += 5;
                  });
                },
              ),
            ],
          ),
          const SizedBox(height: 25),

          // --- Slider ---
          SliderTheme(
            data: SliderTheme.of(context).copyWith(
              thumbShape: const RoundSliderThumbShape(enabledThumbRadius: 10),
              overlayShape: const RoundSliderOverlayShape(overlayRadius: 18),
            ),
            child: Slider(
              value: amount,
              min: 5,
              max: 500,
              activeColor: Colors.orange,
              inactiveColor: Colors.grey.shade300,
              onChanged: (value) {
                setState(() {
                  amount = value;
                });
              },
            ),
          ),
          const SizedBox(height: 25),

          // --- Quick Amount Options ---
          Center(
            child: Wrap(
              spacing: 18,
              runSpacing: 18,
              children: [5, 10, 15, 20, 50, 100, 200, 500].map((value) {
                final isSelected = amount == value.toDouble();
                return AnimatedContainer(
                  duration: const Duration(milliseconds: 250),
                  curve: Curves.easeInOut,
                  width: 75,
                  height: 75,
                  alignment: Alignment.center,
                  decoration: BoxDecoration(
                    color: isSelected ? Colors.orange : Colors.grey[100],
                    borderRadius: BorderRadius.circular(14),
                    boxShadow: isSelected
                        ? [
                            BoxShadow(
                              color: Colors.orange.withOpacity(0.3),
                              blurRadius: 8,
                              offset: const Offset(0, 4),
                            )
                          ]
                        : [],
                  ),
                  child: InkWell(
                    onTap: () {
                      setState(() {
                        amount = value.toDouble();
                      });
                    },
                    borderRadius: BorderRadius.circular(14),
                    child: Text(
                      '₱$value',
                      style: TextStyle(
                        color: isSelected ? Colors.white : Colors.black,
                        fontWeight: FontWeight.w600,
                        fontSize: 17,
                      ),
                    ),
                  ),
                );
              }).toList(),
            ),
          ),
          const Spacer(),

          // --- Top Up Button ---
          SizedBox(
            width: double.infinity,
            height: 60,
            child: ElevatedButton(
              onPressed: () {
                // TODO: Add top-up logic
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.orange,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                elevation: 5,
              ),
              child: const Text(
                "Top Up",
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
            ),
          )
        ],
      ),
    );
  }
}

// --- Custom Amount Button ---
class _AmountButton extends StatelessWidget {
  final IconData icon;
  final VoidCallback onTap;

  const _AmountButton({required this.icon, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(10),
      child: Container(
        width: 45,
        height: 45,
        decoration: BoxDecoration(
          color: Colors.grey[100],
          borderRadius: BorderRadius.circular(10),
        ),
        child: Icon(icon, color: Colors.black),
      ),
    );
  }
}
