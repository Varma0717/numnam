import 'package:flutter/material.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.fromLTRB(16, 18, 16, 22),
      children: [
        Container(
          padding: const EdgeInsets.all(18),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(24),
            gradient: const LinearGradient(
              colors: [Color(0xFFFDE68A), Color(0xFFF59E0B)],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
            boxShadow: const [
              BoxShadow(
                color: Color(0x332D2A4A),
                blurRadius: 24,
                offset: Offset(0, 10),
              ),
            ],
          ),
          child: const Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Yummy Nutrition for Tiny Tummies',
                style: TextStyle(
                  color: Color(0xFF1F2937),
                  fontSize: 24,
                  fontWeight: FontWeight.w800,
                  height: 1.15,
                ),
              ),
              SizedBox(height: 8),
              Text(
                'App foundation is live. Next steps: auth, catalog, cart, checkout, and orders.',
                style: TextStyle(
                  color: Color(0xFF374151),
                  fontSize: 13,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}
