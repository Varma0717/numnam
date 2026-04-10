import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../theme/colors.dart';

class LoadingIndicator extends StatelessWidget {
  const LoadingIndicator({super.key, this.message});
  final String? message;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          const CircularProgressIndicator(color: kCoral),
          if (message != null) ...[
            const SizedBox(height: 16),
            Text(message!,
                style: GoogleFonts.poppins(fontSize: 13, color: kNavy)),
          ],
        ],
      ),
    );
  }
}
