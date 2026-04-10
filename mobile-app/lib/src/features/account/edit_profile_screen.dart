import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:provider/provider.dart';
import '../../core/auth_provider.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/inner_page_nav.dart';

class EditProfileScreen extends StatefulWidget {
  const EditProfileScreen({super.key});
  static const routeName = '/edit-profile';

  @override
  State<EditProfileScreen> createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _nameCtrl;
  late TextEditingController _phoneCtrl;
  late TextEditingController _dobCtrl;
  late TextEditingController _addressLine1Ctrl;
  late TextEditingController _addressLine2Ctrl;
  late TextEditingController _cityCtrl;
  late TextEditingController _stateCtrl;
  late TextEditingController _postalCodeCtrl;
  late TextEditingController _countryCtrl;
  String? _gender;
  bool _saving = false;
  String? _error;
  String? _success;

  @override
  void initState() {
    super.initState();
    final user = context.read<AuthProvider>().user;
    _nameCtrl = TextEditingController(text: user?.name ?? '');
    _phoneCtrl = TextEditingController(text: user?.phone ?? '');
    _dobCtrl = TextEditingController(text: user?.dateOfBirth ?? '');
    _addressLine1Ctrl = TextEditingController(text: user?.addressLine1 ?? '');
    _addressLine2Ctrl = TextEditingController(text: user?.addressLine2 ?? '');
    _cityCtrl = TextEditingController(text: user?.city ?? '');
    _stateCtrl = TextEditingController(text: user?.state ?? '');
    _postalCodeCtrl = TextEditingController(text: user?.postalCode ?? '');
    _countryCtrl = TextEditingController(text: user?.country ?? '');
    _gender = user?.gender;
  }

  @override
  void dispose() {
    _nameCtrl.dispose();
    _phoneCtrl.dispose();
    _dobCtrl.dispose();
    _addressLine1Ctrl.dispose();
    _addressLine2Ctrl.dispose();
    _cityCtrl.dispose();
    _stateCtrl.dispose();
    _postalCodeCtrl.dispose();
    _countryCtrl.dispose();
    super.dispose();
  }

  Future<void> _pickDate() async {
    final now = DateTime.now();
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.tryParse(_dobCtrl.text) ?? DateTime(now.year - 1),
      firstDate: DateTime(2015),
      lastDate: now,
    );
    if (picked != null) {
      _dobCtrl.text =
          '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
    }
  }

  Future<void> _pickAvatar() async {
    final picker = ImagePicker();
    final file = await picker.pickImage(source: ImageSource.gallery, maxWidth: 512, imageQuality: 80);
    if (file == null) return;
    setState(() { _saving = true; _error = null; _success = null; });
    final ok = await context.read<AuthProvider>().uploadAvatar(file.path);
    setState(() {
      _saving = false;
      if (ok) _success = 'Avatar updated!';
      else _error = context.read<AuthProvider>().error ?? 'Upload failed';
    });
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() { _saving = true; _error = null; _success = null; });
    try {
      await context.read<AuthProvider>().updateProfile({
        'name': _nameCtrl.text.trim(),
        'phone': _phoneCtrl.text.trim().isEmpty ? null : _phoneCtrl.text.trim(),
        'date_of_birth': _dobCtrl.text.trim().isEmpty ? null : _dobCtrl.text.trim(),
        'gender': _gender,
        'address_line1': _addressLine1Ctrl.text.trim().isEmpty ? null : _addressLine1Ctrl.text.trim(),
        'address_line2': _addressLine2Ctrl.text.trim().isEmpty ? null : _addressLine2Ctrl.text.trim(),
        'city': _cityCtrl.text.trim().isEmpty ? null : _cityCtrl.text.trim(),
        'state': _stateCtrl.text.trim().isEmpty ? null : _stateCtrl.text.trim(),
        'postal_code': _postalCodeCtrl.text.trim().isEmpty ? null : _postalCodeCtrl.text.trim(),
        'country': _countryCtrl.text.trim().isEmpty ? null : _countryCtrl.text.trim(),
      });
      final authError = context.read<AuthProvider>().error;
      setState(() {
        _saving = false;
        if (authError != null) { _error = authError; } else { _success = 'Profile updated!'; }
      });
    } catch (e) {
      setState(() { _error = 'Failed to update profile'; _saving = false; });
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = context.watch<AuthProvider>().user;
    return Scaffold(
      appBar: AppBar(title: const Text('Edit Profile')),
      bottomNavigationBar: const InnerPageNav(),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(20),
          children: [
            // Avatar
            Center(
              child: GestureDetector(
                onTap: _saving ? null : _pickAvatar,
                child: Stack(
                  children: [
                    CircleAvatar(
                      radius: 48,
                      backgroundColor: const Color(0xFFFFF0F5),
                      backgroundImage: user?.avatar != null
                          ? CachedNetworkImageProvider(user!.avatar!)
                          : null,
                      child: user?.avatar == null
                          ? Text(
                              (user?.name ?? 'U').substring(0, 1).toUpperCase(),
                              style: GoogleFonts.baloo2(fontSize: 36, fontWeight: FontWeight.w900, color: kCoral),
                            )
                          : null,
                    ),
                    Positioned(
                      bottom: 0, right: 0,
                      child: Container(
                        padding: const EdgeInsets.all(6),
                        decoration: const BoxDecoration(color: kCoral, shape: BoxShape.circle),
                        child: const Icon(Icons.camera_alt, size: 16, color: Colors.white),
                      ),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),

            // --- Personal Info ---
            _sectionHeader('Personal Information'),
            const SizedBox(height: 8),
            TextFormField(
              controller: _nameCtrl,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(labelText: 'Full Name', prefixIcon: Icon(Icons.person_outline)),
              validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
            ),
            const SizedBox(height: 14),
            TextFormField(
              controller: _phoneCtrl,
              keyboardType: TextInputType.phone,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(labelText: 'Phone', prefixIcon: Icon(Icons.phone_outlined)),
            ),
            const SizedBox(height: 14),
            TextFormField(
              controller: _dobCtrl,
              readOnly: true,
              onTap: _pickDate,
              decoration: const InputDecoration(
                labelText: 'Child\'s Date of Birth',
                prefixIcon: Icon(Icons.cake_outlined),
                hintText: 'YYYY-MM-DD',
              ),
            ),
            const SizedBox(height: 14),
            DropdownButtonFormField<String>(
              value: _gender,
              decoration: const InputDecoration(labelText: 'Gender', prefixIcon: Icon(Icons.wc_outlined)),
              items: const [
                DropdownMenuItem(value: 'male', child: Text('Male')),
                DropdownMenuItem(value: 'female', child: Text('Female')),
                DropdownMenuItem(value: 'other', child: Text('Other')),
              ],
              onChanged: (v) => setState(() => _gender = v),
            ),
            const SizedBox(height: 24),

            // --- Address ---
            _sectionHeader('Shipping Address'),
            const SizedBox(height: 8),
            TextFormField(
              controller: _addressLine1Ctrl,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(labelText: 'Address Line 1', prefixIcon: Icon(Icons.home_outlined)),
            ),
            const SizedBox(height: 14),
            TextFormField(
              controller: _addressLine2Ctrl,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(labelText: 'Address Line 2'),
            ),
            const SizedBox(height: 14),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _cityCtrl,
                    textInputAction: TextInputAction.next,
                    decoration: const InputDecoration(labelText: 'City'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    controller: _stateCtrl,
                    textInputAction: TextInputAction.next,
                    decoration: const InputDecoration(labelText: 'State'),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 14),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _postalCodeCtrl,
                    keyboardType: TextInputType.number,
                    textInputAction: TextInputAction.next,
                    decoration: const InputDecoration(labelText: 'PIN Code'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    controller: _countryCtrl,
                    textInputAction: TextInputAction.done,
                    decoration: const InputDecoration(labelText: 'Country'),
                  ),
                ),
              ],
            ),

            // Status messages
            if (_error != null) ...[
              const SizedBox(height: 16),
              Text(_error!, style: GoogleFonts.poppins(fontSize: 13, color: const Color(0xFFB91C1C))),
            ],
            if (_success != null) ...[
              const SizedBox(height: 16),
              Text(_success!, style: GoogleFonts.poppins(fontSize: 13, color: kMint)),
            ],
            const SizedBox(height: 24),
            SizedBox(
              height: 48,
              child: FilledButton(
                onPressed: _saving ? null : _save,
                child: _saving
                    ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                    : const Text('Save Changes'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _sectionHeader(String text) {
    return Text(text,
        style: GoogleFonts.baloo2(fontSize: 18, fontWeight: FontWeight.w700, color: kNavy));
  }
}
