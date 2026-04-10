import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../shared/theme/colors.dart';

class ContactFormScreen extends StatefulWidget {
  const ContactFormScreen({super.key});
  static const routeName = '/contact';

  @override
  State<ContactFormScreen> createState() => _ContactFormScreenState();
}

class _ContactFormScreenState extends State<ContactFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _subjectCtrl = TextEditingController();
  final _messageCtrl = TextEditingController();
  bool _sending = false;
  String? _error;
  bool _sent = false;

  @override
  void dispose() {
    _nameCtrl.dispose();
    _emailCtrl.dispose();
    _subjectCtrl.dispose();
    _messageCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() {
      _sending = true;
      _error = null;
    });
    try {
      final api = context.read<ApiClient>();
      await api.dio.post(ApiEndpoints.contactForm, data: {
        'name': _nameCtrl.text.trim(),
        'email': _emailCtrl.text.trim(),
        'subject': _subjectCtrl.text.trim(),
        'message': _messageCtrl.text.trim(),
      });
      setState(() {
        _sent = true;
        _sending = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Failed to send message. Please try again.';
        _sending = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Contact Us')),
      body: _sent ? _successView() : _formView(),
    );
  }

  Widget _successView() {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(32),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 80,
              height: 80,
              decoration: BoxDecoration(
                color: kMint.withOpacity(0.15),
                borderRadius: BorderRadius.circular(24),
              ),
              child: const Icon(Icons.check_circle_rounded,
                  size: 44, color: kMint),
            ),
            const SizedBox(height: 20),
            Text('Message Sent!',
                style: GoogleFonts.baloo2(
                    fontSize: 24,
                    fontWeight: FontWeight.w800,
                    color: kNavy)),
            const SizedBox(height: 8),
            Text("We'll get back to you soon.",
                style: GoogleFonts.poppins(
                    fontSize: 14, color: const Color(0xFF6B6B8A))),
            const SizedBox(height: 24),
            FilledButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Go Back'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _formView() {
    return Form(
      key: _formKey,
      child: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          Text(
            "We'd love to hear from you! 💬",
            style: GoogleFonts.baloo2(
                fontSize: 20, fontWeight: FontWeight.w700, color: kNavy),
          ),
          const SizedBox(height: 16),
          TextFormField(
            controller: _nameCtrl,
            textInputAction: TextInputAction.next,
            decoration: const InputDecoration(labelText: 'Your Name'),
            validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
          ),
          const SizedBox(height: 12),
          TextFormField(
            controller: _emailCtrl,
            keyboardType: TextInputType.emailAddress,
            textInputAction: TextInputAction.next,
            decoration: const InputDecoration(labelText: 'Email'),
            validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
          ),
          const SizedBox(height: 12),
          TextFormField(
            controller: _subjectCtrl,
            textInputAction: TextInputAction.next,
            decoration: const InputDecoration(labelText: 'Subject'),
            validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
          ),
          const SizedBox(height: 12),
          TextFormField(
            controller: _messageCtrl,
            maxLines: 5,
            textInputAction: TextInputAction.done,
            decoration: const InputDecoration(
                labelText: 'Message', alignLabelWithHint: true),
            validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
          ),
          if (_error != null) ...[
            const SizedBox(height: 14),
            Text(_error!,
                style: GoogleFonts.poppins(
                    fontSize: 13, color: const Color(0xFFB91C1C))),
          ],
          const SizedBox(height: 24),
          SizedBox(
            height: 48,
            child: FilledButton(
              onPressed: _sending ? null : _submit,
              child: _sending
                  ? const SizedBox(
                      width: 20,
                      height: 20,
                      child: CircularProgressIndicator(
                          strokeWidth: 2, color: Colors.white))
                  : const Text('Send Message'),
            ),
          ),
        ],
      ),
    );
  }
}
