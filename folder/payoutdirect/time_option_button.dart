import 'package:flutter/material.dart';

class TimeOptionButton extends StatelessWidget {
  const TimeOptionButton({
    super.key,
    required this.label,
    required this.isSelected,
    required this.onTap,
  });

  final String label;
  final bool isSelected;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 250),
      curve: Curves.easeInOut,
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(20),
        gradient: isSelected
            ? const LinearGradient(
                colors: [Colors.orange, Color(0xFFFFB74D)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              )
            : null,
        color: isSelected ? null : Colors.transparent,
        border: Border.all(
          color: isSelected ? Colors.orange : Colors.grey.shade300,
          width: 1.5,
        ),
        boxShadow: isSelected
            ? [
                BoxShadow(
                  color: Colors.orange.withOpacity(0.25),
                  blurRadius: 6,
                  offset: const Offset(0, 3),
                )
              ]
            : [],
      ),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(20),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 14,
            fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
            color: isSelected ? Colors.white : Colors.black,
          ),
        ),
      ),
    );
  }
}

class TimeOptionsRow extends StatefulWidget {
  const TimeOptionsRow({super.key});

  @override
  State<TimeOptionsRow> createState() => _TimeOptionsRowState();
}

class _TimeOptionsRowState extends State<TimeOptionsRow> {
  String selectedPeriod = 'Week';

  final List<String> periods = ["Day", "Week", "Month", "Year"];

  @override
  Widget build(BuildContext context) {
    return Wrap(
      spacing: 12,
      alignment: WrapAlignment.center,
      children: periods.map((period) {
        return TimeOptionButton(
          label: period,
          isSelected: selectedPeriod == period,
          onTap: () => setState(() => selectedPeriod = period),
        );
      }).toList(),
    );
  }
}
