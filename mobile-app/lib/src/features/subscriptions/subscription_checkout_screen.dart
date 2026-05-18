import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:razorpay_flutter/razorpay_flutter.dart';
import 'package:intl/intl.dart';
import '../../config/app_config.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/pricing_plan.dart';
import '../../shared/theme/colors.dart';

class SubscriptionCheckoutScreen extends StatefulWidget {
  final PricingPlan plan;

  const SubscriptionCheckoutScreen({
    super.key,
    required this.plan,
  });

  @override
  State<SubscriptionCheckoutScreen> createState() =>
      _SubscriptionCheckoutScreenState();
}

class _SubscriptionCheckoutScreenState
    extends State<SubscriptionCheckoutScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _addressCtrl = TextEditingController();
  final _cityCtrl = TextEditingController();
  final _stateCtrl = TextEditingController();
  final _pincodeCtrl = TextEditingController();
  String _paymentMethod = 'razorpay'; // Subscriptions typically require payment
  bool _processing = false;
  String? _error;
  late final Razorpay _razorpay;

  @override
  void initState() {
    super.initState();
    _razorpay = Razorpay();
    _razorpay.on(Razorpay.EVENT_PAYMENT_SUCCESS, _onPaymentSuccess);
    _razorpay.on(Razorpay.EVENT_PAYMENT_ERROR, _onPaymentError);
    _razorpay.on(Razorpay.EVENT_EXTERNAL_WALLET, _onExternalWallet);
  }

  @override
  void dispose() {
    _razorpay.clear();
    _nameCtrl.dispose();
    _phoneCtrl.dispose();
    _addressCtrl.dispose();
    _cityCtrl.dispose();
    _stateCtrl.dispose();
    _pincodeCtrl.dispose();
    super.dispose();
  }

  Future<void> _subscribe() async {
    if (!_formKey.currentState!.validate()) return;

    if (_paymentMethod == 'razorpay') {
      if (AppConfig.razorpayKeyId.isEmpty) {
        setState(() =>
            _error = 'Razorpay is not configured. Please contact support.');
        return;
      }
      await _initiateRazorpay();
      return;
    }

    // Direct subscription without payment (if COD or free trial)
    await _submitSubscription();
  }

  Future<void> _submitSubscription({
    String? paymentReference,
    String? razorpayOrderId,
    String? razorpaySignature,
  }) async {
    setState(() {
      _processing = true;
      _error = null;
    });

    try {
      final apiClient = context.read<ApiClient>();
      final body = {
        'pricing_plan_id': widget.plan.id,
        'payment_method': _paymentMethod,
        'ship_name': _nameCtrl.text.trim(),
        'ship_phone': _phoneCtrl.text.trim(),
        'ship_address': _addressCtrl.text.trim(),
        'ship_city': _cityCtrl.text.trim(),
        'ship_state': _stateCtrl.text.trim(),
        'ship_pincode': _pincodeCtrl.text.trim(),
        if (paymentReference != null) ...{
          'payment_reference': paymentReference,
          'razorpay_order_id': razorpayOrderId,
          'razorpay_signature': razorpaySignature,
        },
      };

      final response = await apiClient.dio.post(
        ApiEndpoints.subscriptions,
        data: body,
      );

      if (!mounted) return;

      // Show success and navigate
      _showSuccessDialog(response.data);
    } on DioException catch (e) {
      if (!mounted) return;
      setState(() {
        _error = e.response?.data['message'] ?? 'Subscription failed';
      });
    } catch (e) {
      if (!mounted) return;
      setState(() => _error = 'An unexpected error occurred');
    } finally {
      if (mounted) {
        setState(() => _processing = false);
      }
    }
  }

  Future<void> _initiateRazorpay() async {
    // Calculate amount in paise (rupees * 100)
    final amountInPaise = (widget.plan.price * 100).toInt();

    final options = {
      'key': AppConfig.razorpayKeyId,
      'amount': amountInPaise,
      'name': 'NumNam',
      'description': 'Subscription: ${widget.plan.name}',
      'prefill': {
        'contact': _phoneCtrl.text.trim(),
        'name': _nameCtrl.text.trim(),
      },
      'theme': {
        'color': '#FF6B6B', // Coral color
      },
    };

    try {
      _razorpay.open(options);
    } catch (e) {
      setState(() => _error = 'Failed to open payment gateway');
    }
  }

  void _onPaymentSuccess(PaymentSuccessResponse response) {
    _submitSubscription(
      paymentReference: response.paymentId,
      razorpayOrderId: response.orderId,
      razorpaySignature: response.signature,
    );
  }

  void _onPaymentError(PaymentFailureResponse response) {
    setState(() {
      _error = 'Payment failed: ${response.message}';
    });
  }

  void _onExternalWallet(ExternalWalletResponse response) {
    setState(() {
      _error = 'External wallet not supported yet';
    });
  }

  void _showSuccessDialog(Map<String, dynamic> subscriptionData) {
    final startDate = DateTime.now();
    final renewalDate = _calculateRenewalDate(startDate);

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: kMint.withOpacity(0.2),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.check_circle, color: kMint, size: 32),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Text(
                'Subscription Activated!',
                style: GoogleFonts.poppins(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: kNavy,
                ),
              ),
            ),
          ],
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Your ${widget.plan.name} subscription is now active.',
              style: GoogleFonts.inter(fontSize: 14, color: kNavy),
            ),
            const SizedBox(height: 16),
            _buildInfoRow('Plan', widget.plan.name),
            _buildInfoRow('Price', '₹${widget.plan.price.toStringAsFixed(0)}'),
            _buildInfoRow('Billing', widget.plan.billingCycle ?? 'Monthly'),
            _buildInfoRow(
                'Start Date', DateFormat('MMM dd, yyyy').format(startDate)),
            _buildInfoRow(
                'Next Renewal', DateFormat('MMM dd, yyyy').format(renewalDate)),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () {
              Navigator.of(ctx).pop(); // Close dialog
              Navigator.of(context).pop(); // Close checkout screen
              Navigator.of(context).pop(); // Close subscriptions screen
            },
            child: Text(
              'Done',
              style: GoogleFonts.inter(
                color: kCoral,
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: GoogleFonts.inter(
              fontSize: 13,
              color: kNavy.withOpacity(0.6),
            ),
          ),
          Text(
            value,
            style: GoogleFonts.inter(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: kNavy,
            ),
          ),
        ],
      ),
    );
  }

  DateTime _calculateRenewalDate(DateTime startDate) {
    final cycle = widget.plan.billingCycle?.toLowerCase() ?? 'monthly';
    if (cycle.contains('annual') || cycle.contains('yearly')) {
      return DateTime(startDate.year + 1, startDate.month, startDate.day);
    }
    // Default to monthly
    return DateTime(startDate.year, startDate.month + 1, startDate.day);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          'Subscription Checkout',
          style: GoogleFonts.poppins(
            fontSize: 18,
            fontWeight: FontWeight.w600,
            color: kNavy,
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        leading: const BackButton(color: kNavy),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Plan Summary Card
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [kCoral, kCoral.withOpacity(0.8)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(
                      color: kCoral.withOpacity(0.3),
                      blurRadius: 12,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      widget.plan.name,
                      style: GoogleFonts.poppins(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.baseline,
                      textBaseline: TextBaseline.alphabetic,
                      children: [
                        Text(
                          '₹${widget.plan.price.toStringAsFixed(0)}',
                          style: GoogleFonts.poppins(
                            fontSize: 36,
                            fontWeight: FontWeight.bold,
                            color: Colors.white,
                          ),
                        ),
                        const SizedBox(width: 8),
                        Text(
                          '/${widget.plan.billingCycle ?? 'month'}',
                          style: GoogleFonts.inter(
                            fontSize: 16,
                            color: Colors.white.withOpacity(0.9),
                          ),
                        ),
                      ],
                    ),
                    if (widget.plan.description != null) ...[
                      const SizedBox(height: 12),
                      Text(
                        widget.plan.description!,
                        style: GoogleFonts.inter(
                          fontSize: 14,
                          color: Colors.white.withOpacity(0.9),
                        ),
                      ),
                    ],
                  ],
                ),
              ),

              const SizedBox(height: 24),

              // Delivery Information
              Text(
                'Delivery Information',
                style: GoogleFonts.poppins(
                  fontSize: 18,
                  fontWeight: FontWeight.w600,
                  color: kNavy,
                ),
              ),
              const SizedBox(height: 16),

              _buildTextField(
                controller: _nameCtrl,
                label: 'Full Name',
                icon: Icons.person_outline,
                validator: (v) =>
                    v?.trim().isEmpty ?? true ? 'Name is required' : null,
              ),
              const SizedBox(height: 16),

              _buildTextField(
                controller: _phoneCtrl,
                label: 'Phone Number',
                icon: Icons.phone_outlined,
                keyboardType: TextInputType.phone,
                validator: (v) {
                  if (v?.trim().isEmpty ?? true) return 'Phone is required';
                  if (v!.length < 10) return 'Invalid phone number';
                  return null;
                },
              ),
              const SizedBox(height: 16),

              _buildTextField(
                controller: _addressCtrl,
                label: 'Address',
                icon: Icons.location_on_outlined,
                maxLines: 2,
                validator: (v) =>
                    v?.trim().isEmpty ?? true ? 'Address is required' : null,
              ),
              const SizedBox(height: 16),

              Row(
                children: [
                  Expanded(
                    child: _buildTextField(
                      controller: _cityCtrl,
                      label: 'City',
                      icon: Icons.location_city_outlined,
                      validator: (v) =>
                          v?.trim().isEmpty ?? true ? 'Required' : null,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _buildTextField(
                      controller: _stateCtrl,
                      label: 'State',
                      icon: Icons.map_outlined,
                      validator: (v) =>
                          v?.trim().isEmpty ?? true ? 'Required' : null,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),

              _buildTextField(
                controller: _pincodeCtrl,
                label: 'Pincode',
                icon: Icons.pin_drop_outlined,
                keyboardType: TextInputType.number,
                validator: (v) {
                  if (v?.trim().isEmpty ?? true) return 'Pincode is required';
                  if (v!.length != 6) return 'Invalid pincode';
                  return null;
                },
              ),

              const SizedBox(height: 24),

              // Payment Method
              Text(
                'Payment Method',
                style: GoogleFonts.poppins(
                  fontSize: 18,
                  fontWeight: FontWeight.w600,
                  color: kNavy,
                ),
              ),
              const SizedBox(height: 12),

              _buildPaymentOption(
                value: 'razorpay',
                title: 'Pay with Razorpay',
                subtitle: 'Credit/Debit Card, UPI, Netbanking',
                icon: Icons.payment,
              ),

              if (_error != null) ...[
                const SizedBox(height: 16),
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.red.shade50,
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: Colors.red.shade200),
                  ),
                  child: Row(
                    children: [
                      Icon(Icons.error_outline,
                          color: Colors.red.shade700, size: 20),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          _error!,
                          style: GoogleFonts.inter(
                            fontSize: 13,
                            color: Colors.red.shade700,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],

              const SizedBox(height: 32),

              // Subscribe Button
              SizedBox(
                width: double.infinity,
                height: 56,
                child: ElevatedButton(
                  onPressed: _processing ? null : _subscribe,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: kCoral,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16),
                    ),
                    elevation: 4,
                    disabledBackgroundColor: kNavy.withOpacity(0.3),
                  ),
                  child: _processing
                      ? const SizedBox(
                          height: 24,
                          width: 24,
                          child: CircularProgressIndicator(
                            color: Colors.white,
                            strokeWidth: 2,
                          ),
                        )
                      : Text(
                          'Subscribe Now - ₹${widget.plan.price.toStringAsFixed(0)}',
                          style: GoogleFonts.poppins(
                            fontSize: 16,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                ),
              ),

              const SizedBox(height: 16),

              // Terms & Conditions
              Text(
                'By subscribing, you agree to our Terms & Conditions. Your subscription will auto-renew every ${widget.plan.billingCycle ?? 'month'} unless cancelled.',
                textAlign: TextAlign.center,
                style: GoogleFonts.inter(
                  fontSize: 12,
                  color: kNavy.withOpacity(0.6),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    TextInputType? keyboardType,
    int maxLines = 1,
    String? Function(String?)? validator,
  }) {
    return TextFormField(
      controller: controller,
      keyboardType: keyboardType,
      maxLines: maxLines,
      validator: validator,
      decoration: InputDecoration(
        labelText: label,
        prefixIcon: Icon(icon, color: kCoral),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: kNavy.withOpacity(0.2)),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: kNavy.withOpacity(0.2)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: kCoral, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Colors.red, width: 1),
        ),
        filled: true,
        fillColor: Colors.grey.shade50,
      ),
    );
  }

  Widget _buildPaymentOption({
    required String value,
    required String title,
    required String subtitle,
    required IconData icon,
  }) {
    final isSelected = _paymentMethod == value;
    return InkWell(
      onTap: () => setState(() => _paymentMethod = value),
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          border: Border.all(
            color: isSelected ? kCoral : kNavy.withOpacity(0.2),
            width: isSelected ? 2 : 1,
          ),
          borderRadius: BorderRadius.circular(12),
          color: isSelected ? kCoral.withOpacity(0.05) : Colors.white,
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color:
                    isSelected ? kCoral.withOpacity(0.1) : Colors.grey.shade100,
                shape: BoxShape.circle,
              ),
              child: Icon(
                icon,
                color: isSelected ? kCoral : kNavy.withOpacity(0.6),
                size: 24,
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: GoogleFonts.inter(
                      fontSize: 15,
                      fontWeight: FontWeight.w600,
                      color: kNavy,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    subtitle,
                    style: GoogleFonts.inter(
                      fontSize: 12,
                      color: kNavy.withOpacity(0.6),
                    ),
                  ),
                ],
              ),
            ),
            Radio<String>(
              value: value,
              groupValue: _paymentMethod,
              onChanged: (v) => setState(() => _paymentMethod = v!),
              activeColor: kCoral,
            ),
          ],
        ),
      ),
    );
  }
}
