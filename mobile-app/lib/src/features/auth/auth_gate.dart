import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/auth_provider.dart';
import '../../shared/theme/colors.dart';
import 'login_screen.dart';

class AuthGate extends StatelessWidget {
  const AuthGate({super.key, required this.child});
  final Widget child;

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    if (auth.isAuthenticated) return child;

    return Center(
      child: Padding(
        padding: const EdgeInsets.all(32),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 88,
              height: 88,
              decoration: BoxDecoration(
                color: kCoral.withOpacity(0.10),
                borderRadius: BorderRadius.circular(24),
              ),
              child: const Icon(Icons.lock_outline_rounded,
                  size: 44, color: kCoral),
            ),
            const SizedBox(height: 20),
            Text('Sign In Required',
                style: GoogleFonts.baloo2(
                    fontSize: 22, fontWeight: FontWeight.w800, color: kNavy)),
            const SizedBox(height: 8),
            Text(
              'Please log in to access this feature',
              style: GoogleFonts.poppins(
                  fontSize: 13, color: const Color(0xFF9E9EBE)),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 24),
            SizedBox(
              height: 48,
              width: double.infinity,
              child: FilledButton(
                onPressed: () =>
                    Navigator.of(context).pushNamed(LoginScreen.routeName),
                child: const Text('Login'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
