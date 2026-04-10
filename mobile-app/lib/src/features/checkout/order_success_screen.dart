import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../shared/theme/colors.dart';

class OrderSuccessScreen extends StatelessWidget {
  const OrderSuccessScreen({super.key});
  static const routeName = '/order-success';

  @override
  Widget build(BuildContext context) {
    final orderNumber =
        ModalRoute.of(context)!.settings.arguments as String? ?? '';
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: Padding(
            padding: const EdgeInsets.all(32),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  width: 100,
                  height: 100,
                  decoration: BoxDecoration(
                    color: kMint.withOpacity(0.15),
                    borderRadius: BorderRadius.circular(28),
                  ),
                  child: const Icon(Icons.check_circle_rounded,
                      size: 56, color: kMint),
                ),
                const SizedBox(height: 24),
                Text(
                  'Order Placed! 🎉',
                  style: GoogleFonts.baloo2(
                      fontSize: 28,
                      fontWeight: FontWeight.w900,
                      color: kNavy),
                ),
                const SizedBox(height: 10),
                Text(
                  'Your order has been placed successfully.',
                  style: GoogleFonts.poppins(
                      fontSize: 14, color: const Color(0xFF6B6B8A)),
                  textAlign: TextAlign.center,
                ),
                if (orderNumber.isNotEmpty) ...[
                  const SizedBox(height: 16),
                  Container(
                    padding: const EdgeInsets.symmetric(
                        horizontal: 20, vertical: 12),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFFF0F5),
                      borderRadius: BorderRadius.circular(16),
                      border:
                          Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
                    ),
                    child: Column(
                      children: [
                        Text('Order Number',
                            style: GoogleFonts.poppins(
                                fontSize: 11,
                                color: const Color(0xFF9E9EBE))),
                        const SizedBox(height: 4),
                        Text(orderNumber,
                            style: GoogleFonts.poppins(
                                fontSize: 18,
                                fontWeight: FontWeight.w700,
                                color: kCoral)),
                      ],
                    ),
                  ),
                ],
                const SizedBox(height: 32),
                SizedBox(
                  width: double.infinity,
                  height: 48,
                  child: FilledButton(
                    onPressed: () => Navigator.of(context)
                        .pushNamedAndRemoveUntil('/orders', (r) => r.isFirst),
                    child: const Text('View My Orders'),
                  ),
                ),
                const SizedBox(height: 12),
                SizedBox(
                  width: double.infinity,
                  height: 48,
                  child: OutlinedButton(
                    onPressed: () => Navigator.of(context)
                        .popUntil((r) => r.isFirst),
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: Color(0xFFFFD6E5), width: 2),
                    ),
                    child: const Text('Continue Shopping'),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
