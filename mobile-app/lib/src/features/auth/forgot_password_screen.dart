import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/auth_provider.dart';
import '../../shared/theme/colors.dart';

class ForgotPasswordScreen extends StatefulWidget {
  const ForgotPasswordScreen({super.key});
  static const routeName = '/forgot-password';

  @override
  State<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  final _emailFormKey = GlobalKey<FormState>();
  final _resetFormKey = GlobalKey<FormState>();
  final _emailCtrl = TextEditingController();
  final _codeCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  final _confirmCtrl = TextEditingController();
  bool _codeSent = false;
  bool _obscure = true;
  String? _successMessage;

  @override
  void dispose() {
    _emailCtrl.dispose();
    _codeCtrl.dispose();
    _passCtrl.dispose();
    _confirmCtrl.dispose();
    super.dispose();
  }

  Future<void> _sendCode() async {
    if (!_emailFormKey.currentState!.validate()) return;
    final auth = context.read<AuthProvider>();
    final ok = await auth.forgotPassword(_emailCtrl.text.trim());
    if (ok && mounted) {
      setState(() {
        _codeSent = true;
        _successMessage = 'A 6-digit code has been sent to your email.';
      });
    }
  }

  Future<void> _resetPassword() async {
    if (!_resetFormKey.currentState!.validate()) return;
    final auth = context.read<AuthProvider>();
    final ok = await auth.resetPassword(
      _emailCtrl.text.trim(),
      _codeCtrl.text.trim(),
      _passCtrl.text,
      _confirmCtrl.text,
    );
    if (ok && mounted) {
      // Auto-logged in after reset — pop back and the Consumer will show Shell
      Navigator.of(context).popUntil((route) => route.isFirst);
    }
  }

  void _backToEmail() {
    context.read<AuthProvider>().clearError();
    setState(() {
      _codeSent = false;
      _successMessage = null;
      _codeCtrl.clear();
      _passCtrl.clear();
      _confirmCtrl.clear();
    });
  }

  InputDecoration _inputDecor(String hint, IconData icon) {
    return InputDecoration(
      hintText: hint,
      hintStyle: GoogleFonts.poppins(color: Colors.grey.shade400),
      prefixIcon: Icon(icon, color: Colors.grey.shade400, size: 20),
      filled: true,
      fillColor: Colors.grey.shade50,
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: BorderSide(color: Colors.grey.shade200),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: BorderSide(color: Colors.grey.shade200),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(color: kCoral, width: 1.5),
      ),
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
    );
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new, size: 20),
          onPressed: () {
            auth.clearError();
            Navigator.of(context).pop();
          },
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 28),
          child: _codeSent ? _buildResetForm(auth) : _buildEmailForm(auth),
        ),
      ),
    );
  }

  Widget _buildEmailForm(AuthProvider auth) {
    return Form(
      key: _emailFormKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          const SizedBox(height: 20),
          Center(
            child: Container(
              width: 80,
              height: 80,
              decoration: BoxDecoration(
                color: kCoral.withOpacity(0.1),
                borderRadius: BorderRadius.circular(20),
              ),
              child: const Icon(Icons.lock_reset_rounded, size: 40, color: kCoral),
            ),
          ),
          const SizedBox(height: 24),
          Center(
            child: Text(
              'Forgot Password?',
              style: GoogleFonts.poppins(
                  fontSize: 24, fontWeight: FontWeight.w700, color: kNavy),
            ),
          ),
          const SizedBox(height: 8),
          Center(
            child: Text(
              'Enter your email and we\'ll send you\na code to reset your password.',
              textAlign: TextAlign.center,
              style: GoogleFonts.poppins(
                  fontSize: 14, color: Colors.grey.shade500, height: 1.5),
            ),
          ),
          const SizedBox(height: 32),
          TextFormField(
            controller: _emailCtrl,
            keyboardType: TextInputType.emailAddress,
            textInputAction: TextInputAction.done,
            onFieldSubmitted: (_) => _sendCode(),
            style: GoogleFonts.poppins(fontSize: 14),
            decoration: _inputDecor('Email address', Icons.email_outlined),
            validator: (v) {
              if (v == null || v.trim().isEmpty) return 'Email is required';
              if (!v.contains('@')) return 'Enter a valid email';
              return null;
            },
          ),
          if (auth.error != null) ...[
            const SizedBox(height: 14),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.red.shade50,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Text(
                auth.error!,
                style: GoogleFonts.poppins(
                    fontSize: 13, color: Colors.red.shade700),
              ),
            ),
          ],
          const SizedBox(height: 24),
          SizedBox(
            height: 52,
            child: ElevatedButton(
              onPressed: auth.isLoading ? null : _sendCode,
              style: ElevatedButton.styleFrom(
                backgroundColor: kCoral,
                foregroundColor: Colors.white,
                elevation: 0,
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(14)),
                textStyle: GoogleFonts.poppins(
                    fontSize: 15, fontWeight: FontWeight.w600),
              ),
              child: auth.isLoading
                  ? const SizedBox(
                      width: 22,
                      height: 22,
                      child: CircularProgressIndicator(
                          strokeWidth: 2, color: Colors.white),
                    )
                  : const Text('Send Reset Code'),
            ),
          ),
          const SizedBox(height: 20),
          Center(
            child: GestureDetector(
              onTap: () => Navigator.of(context).pop(),
              child: Text(
                'Back to Sign In',
                style: GoogleFonts.poppins(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: kCoral),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildResetForm(AuthProvider auth) {
    return Form(
      key: _resetFormKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          const SizedBox(height: 20),
          Center(
            child: Container(
              width: 80,
              height: 80,
              decoration: BoxDecoration(
                color: kMint.withOpacity(0.1),
                borderRadius: BorderRadius.circular(20),
              ),
              child: const Icon(Icons.mark_email_read_rounded,
                  size: 40, color: kMint),
            ),
          ),
          const SizedBox(height: 24),
          Center(
            child: Text(
              'Reset Password',
              style: GoogleFonts.poppins(
                  fontSize: 24, fontWeight: FontWeight.w700, color: kNavy),
            ),
          ),
          const SizedBox(height: 8),
          Center(
            child: Text(
              'Enter the 6-digit code sent to\n${_emailCtrl.text.trim()}',
              textAlign: TextAlign.center,
              style: GoogleFonts.poppins(
                  fontSize: 14, color: Colors.grey.shade500, height: 1.5),
            ),
          ),
          if (_successMessage != null) ...[
            const SizedBox(height: 14),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.green.shade50,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Row(
                children: [
                  Icon(Icons.check_circle_outline,
                      color: Colors.green.shade700, size: 18),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      _successMessage!,
                      style: GoogleFonts.poppins(
                          fontSize: 13, color: Colors.green.shade700),
                    ),
                  ),
                ],
              ),
            ),
          ],
          const SizedBox(height: 24),
          TextFormField(
            controller: _codeCtrl,
            keyboardType: TextInputType.number,
            textInputAction: TextInputAction.next,
            maxLength: 6,
            style: GoogleFonts.poppins(
                fontSize: 24, fontWeight: FontWeight.w700, letterSpacing: 8),
            textAlign: TextAlign.center,
            decoration: _inputDecor('', Icons.pin_outlined).copyWith(
              hintText: '000000',
              counterText: '',
            ),
            validator: (v) {
              if (v == null || v.trim().length != 6) {
                return 'Enter the 6-digit code';
              }
              return null;
            },
          ),
          const SizedBox(height: 14),
          TextFormField(
            controller: _passCtrl,
            obscureText: _obscure,
            textInputAction: TextInputAction.next,
            style: GoogleFonts.poppins(fontSize: 14),
            decoration: _inputDecor('New password', Icons.lock_outline).copyWith(
              suffixIcon: IconButton(
                icon: Icon(
                  _obscure
                      ? Icons.visibility_off_outlined
                      : Icons.visibility_outlined,
                  color: Colors.grey.shade400,
                  size: 20,
                ),
                onPressed: () => setState(() => _obscure = !_obscure),
              ),
            ),
            validator: (v) {
              if (v == null || v.isEmpty) return 'Password is required';
              if (v.length < 8) return 'Minimum 8 characters';
              return null;
            },
          ),
          const SizedBox(height: 14),
          TextFormField(
            controller: _confirmCtrl,
            obscureText: true,
            textInputAction: TextInputAction.done,
            onFieldSubmitted: (_) => _resetPassword(),
            style: GoogleFonts.poppins(fontSize: 14),
            decoration:
                _inputDecor('Confirm new password', Icons.lock_outline),
            validator: (v) {
              if (v != _passCtrl.text) return 'Passwords do not match';
              return null;
            },
          ),
          if (auth.error != null) ...[
            const SizedBox(height: 14),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.red.shade50,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Text(
                auth.error!,
                style: GoogleFonts.poppins(
                    fontSize: 13, color: Colors.red.shade700),
              ),
            ),
          ],
          const SizedBox(height: 24),
          SizedBox(
            height: 52,
            child: ElevatedButton(
              onPressed: auth.isLoading ? null : _resetPassword,
              style: ElevatedButton.styleFrom(
                backgroundColor: kCoral,
                foregroundColor: Colors.white,
                elevation: 0,
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(14)),
                textStyle: GoogleFonts.poppins(
                    fontSize: 15, fontWeight: FontWeight.w600),
              ),
              child: auth.isLoading
                  ? const SizedBox(
                      width: 22,
                      height: 22,
                      child: CircularProgressIndicator(
                          strokeWidth: 2, color: Colors.white),
                    )
                  : const Text('Reset Password'),
            ),
          ),
          const SizedBox(height: 16),
          Center(
            child: GestureDetector(
              onTap: auth.isLoading ? null : _sendCode,
              child: Text(
                'Resend Code',
                style: GoogleFonts.poppins(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: kMint),
              ),
            ),
          ),
          const SizedBox(height: 12),
          Center(
            child: GestureDetector(
              onTap: _backToEmail,
              child: Text(
                'Use a different email',
                style: GoogleFonts.poppins(
                    fontSize: 13, color: Colors.grey.shade500),
              ),
            ),
          ),
          const SizedBox(height: 32),
        ],
      ),
    );
  }
}
