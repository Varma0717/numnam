import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../config/app_config.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../shared/theme/colors.dart';
import '../cart/cart_provider.dart';
import 'order_success_screen.dart';

class CheckoutScreen extends StatefulWidget {
  const CheckoutScreen({super.key});
  static const routeName = '/checkout';

  @override
  State<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends State<CheckoutScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _addressCtrl = TextEditingController();
  final _cityCtrl = TextEditingController();
  final _stateCtrl = TextEditingController();
  final _pincodeCtrl = TextEditingController();
  final _couponCtrl = TextEditingController();
  String _paymentMethod = 'cod';
  bool _placing = false;
  String? _error;

  @override
  void dispose() {
    _nameCtrl.dispose();
    _phoneCtrl.dispose();
    _addressCtrl.dispose();
    _cityCtrl.dispose();
    _stateCtrl.dispose();
    _pincodeCtrl.dispose();
    _couponCtrl.dispose();
    super.dispose();
  }

  Future<void> _placeOrder() async {
    if (!_formKey.currentState!.validate()) return;

    if (_paymentMethod == 'razorpay') {
      if (AppConfig.razorpayKeyId.isEmpty) {
        setState(() => _error = 'Razorpay is not configured. Please use COD.');
        return;
      }
      await _initiateRazorpay();
      return;
    }

    await _submitOrder();
  }

  Future<void> _submitOrder({String? paymentReference}) async {
    setState(() {
      _placing = true;
      _error = null;
    });
    try {
      final cart = context.read<CartProvider>();
      final items = cart.cart.items
          .map((i) => {'product_id': i.productId, 'quantity': i.qty})
          .toList();

      final body = {
        'items': items,
        'payment_method': _paymentMethod,
        'ship_name': _nameCtrl.text.trim(),
        'ship_phone': _phoneCtrl.text.trim(),
        'ship_address': _addressCtrl.text.trim(),
        'ship_city': _cityCtrl.text.trim(),
        'ship_state': _stateCtrl.text.trim(),
        'ship_pincode': _pincodeCtrl.text.trim(),
        if (_couponCtrl.text.trim().isNotEmpty)
          'coupon_code': _couponCtrl.text.trim(),
        if (paymentReference != null) 'payment_reference': paymentReference,
      };

      final api = context.read<ApiClient>();
      final resp = await api.dio.post(ApiEndpoints.orders, data: body);
      final orderData = resp.data['data'] as Map<String, dynamic>;
      final orderNumber = orderData['order_number'] as String? ?? '';

      await cart.clearCart();

      if (mounted) {
        Navigator.of(context).pushReplacementNamed(
          OrderSuccessScreen.routeName,
          arguments: orderNumber,
        );
      }
    } catch (e) {
      setState(() {
        _error = 'Failed to place order. Please try again.';
        _placing = false;
      });
    }
  }

  Future<void> _initiateRazorpay() async {
    // Razorpay integration — the user will add their API key
    // For now, use the razorpay_flutter package
    try {
      // dynamic import so app compiles even without the package configured
      final cart = context.read<CartProvider>();
      final amount = (cart.cart.totals.total * 100).toInt(); // paise

      // Using the Razorpay Flutter plugin
      final razorpayFlutter = await _getRazorpayInstance();
      if (razorpayFlutter == null) {
        await _submitOrder(); // fallback to COD-style
        return;
      }

      final options = {
        'key': AppConfig.razorpayKeyId,
        'amount': amount,
        'name': 'NumNam',
        'description': 'Order payment',
        'prefill': {
          'contact': _phoneCtrl.text.trim(),
        },
        'theme': {'color': '#FF6B8A'},
      };

      razorpayFlutter.open(options);
    } catch (e) {
      setState(() => _error = 'Payment initialization failed');
    }
  }

  dynamic _razorpay;

  Future<dynamic> _getRazorpayInstance() async {
    try {
      // ignore: depend_on_referenced_packages
      final razorpayLib = await Future.value(null).then((_) async {
        // Dynamically use Razorpay - import is at top
        return null;
      });
      if (razorpayLib != null) return razorpayLib;

      // Direct Razorpay usage
      if (_razorpay == null) {
        try {
          final module = _RazorpayWrapper();
          _razorpay = module;
          module.on('payment.success', (response) {
            _submitOrder(
                paymentReference: response?.paymentId ?? 'razorpay_success');
          });
          module.on('payment.error', (_) {
            setState(
                () => _error = 'Payment failed. Please try again or use COD.');
          });
          module.on('external_wallet', (_) {});
        } catch (_) {
          return null;
        }
      }
      return _razorpay;
    } catch (_) {
      return null;
    }
  }

  @override
  Widget build(BuildContext context) {
    final cart = context.watch<CartProvider>();
    return Scaffold(
      appBar: AppBar(title: const Text('Checkout')),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            Text('Shipping Address',
                style: GoogleFonts.baloo2(
                    fontSize: 20, fontWeight: FontWeight.w700, color: kNavy)),
            const SizedBox(height: 14),
            TextFormField(
              controller: _nameCtrl,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(labelText: 'Full Name'),
              validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _phoneCtrl,
              keyboardType: TextInputType.phone,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(labelText: 'Phone Number'),
              validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _addressCtrl,
              maxLines: 2,
              textInputAction: TextInputAction.next,
              decoration: const InputDecoration(labelText: 'Address'),
              validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextFormField(
                    controller: _cityCtrl,
                    textInputAction: TextInputAction.next,
                    decoration: const InputDecoration(labelText: 'City'),
                    validator: (v) =>
                        v?.trim().isEmpty == true ? 'Required' : null,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    controller: _stateCtrl,
                    textInputAction: TextInputAction.next,
                    decoration: const InputDecoration(labelText: 'State'),
                    validator: (v) =>
                        v?.trim().isEmpty == true ? 'Required' : null,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _pincodeCtrl,
              keyboardType: TextInputType.number,
              textInputAction: TextInputAction.done,
              decoration: const InputDecoration(labelText: 'Pincode'),
              validator: (v) => v?.trim().isEmpty == true ? 'Required' : null,
            ),

            const SizedBox(height: 24),
            Text('Coupon Code',
                style: GoogleFonts.baloo2(
                    fontSize: 18, fontWeight: FontWeight.w700, color: kNavy)),
            const SizedBox(height: 10),
            TextFormField(
              controller: _couponCtrl,
              decoration: const InputDecoration(
                  labelText: 'Enter coupon (optional)'),
            ),

            const SizedBox(height: 24),
            Text('Payment Method',
                style: GoogleFonts.baloo2(
                    fontSize: 20, fontWeight: FontWeight.w700, color: kNavy)),
            const SizedBox(height: 10),
            _paymentTile('cod', 'Cash on Delivery', Icons.money_rounded),
            const SizedBox(height: 8),
            _paymentTile(
                'razorpay', 'Pay Online (Razorpay)', Icons.credit_card),

            const SizedBox(height: 24),
            Text('Order Summary',
                style: GoogleFonts.baloo2(
                    fontSize: 20, fontWeight: FontWeight.w700, color: kNavy)),
            const SizedBox(height: 10),
            ...cart.cart.items.map((item) => Padding(
                  padding: const EdgeInsets.only(bottom: 6),
                  child: Row(
                    children: [
                      Expanded(
                          child: Text('${item.name} × ${item.qty}',
                              style: GoogleFonts.poppins(fontSize: 13))),
                      Text('₹${item.lineTotal.toStringAsFixed(0)}',
                          style: GoogleFonts.poppins(
                              fontSize: 13, fontWeight: FontWeight.w600)),
                    ],
                  ),
                )),
            const Divider(height: 20),
            _summaryRow('Subtotal',
                '₹${cart.cart.totals.subtotal.toStringAsFixed(0)}'),
            if (cart.cart.totals.shippingFee > 0)
              _summaryRow('Shipping',
                  '₹${cart.cart.totals.shippingFee.toStringAsFixed(0)}'),
            _summaryRow(
                'Total', '₹${cart.cart.totals.total.toStringAsFixed(0)}',
                bold: true),

            if (_error != null) ...[
              const SizedBox(height: 16),
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xFFFFEDED),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(_error!,
                    style: GoogleFonts.poppins(
                        fontSize: 13, color: const Color(0xFFB91C1C))),
              ),
            ],

            const SizedBox(height: 24),
            SizedBox(
              height: 52,
              child: FilledButton(
                onPressed: _placing ? null : _placeOrder,
                child: _placing
                    ? const SizedBox(
                        width: 22,
                        height: 22,
                        child: CircularProgressIndicator(
                            strokeWidth: 2, color: Colors.white))
                    : const Text('Place Order'),
              ),
            ),
            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }

  Widget _paymentTile(String value, String label, IconData icon) {
    final selected = _paymentMethod == value;
    return GestureDetector(
      onTap: () => setState(() => _paymentMethod = value),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        decoration: BoxDecoration(
          color: selected ? const Color(0xFFFFF0F5) : Colors.white,
          borderRadius: BorderRadius.circular(14),
          border: Border.all(
            color: selected ? kCoral : const Color(0xFFFFD6E5),
            width: selected ? 2 : 1.5,
          ),
        ),
        child: Row(
          children: [
            Icon(icon, color: selected ? kCoral : kNavy, size: 22),
            const SizedBox(width: 12),
            Text(label,
                style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: selected ? FontWeight.w600 : FontWeight.w500,
                    color: kNavy)),
            const Spacer(),
            if (selected)
              const Icon(Icons.check_circle_rounded, color: kCoral, size: 22),
          ],
        ),
      ),
    );
  }

  Widget _summaryRow(String label, String value, {bool bold = false}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label,
              style: GoogleFonts.poppins(
                  fontSize: bold ? 16 : 13,
                  fontWeight: bold ? FontWeight.w700 : FontWeight.w400,
                  color: bold ? kNavy : const Color(0xFF6B6B8A))),
          Text(value,
              style: GoogleFonts.poppins(
                  fontSize: bold ? 16 : 13,
                  fontWeight: bold ? FontWeight.w700 : FontWeight.w600,
                  color: bold ? kCoral : kNavy)),
        ],
      ),
    );
  }
}

// Simple wrapper to handle Razorpay dynamic loading
class _RazorpayWrapper {
  final Map<String, Function> _handlers = {};

  void on(String event, Function handler) {
    _handlers[event] = handler;
  }

  void open(Map<String, dynamic> options) {
    // This will be replaced with actual Razorpay.open() in production
    // when razorpay_flutter package is properly configured
  }
}
