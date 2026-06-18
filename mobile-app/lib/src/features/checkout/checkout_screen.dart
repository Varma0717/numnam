import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:razorpay_flutter/razorpay_flutter.dart';
import '../../config/app_config.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/site_settings.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/inner_page_nav.dart';
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
  String _paymentMethod = 'razorpay';
  bool _placing = false;
  bool _loadingSettings = true;
  String? _error;
  SiteSettings? _siteSettings;
  late final Razorpay _razorpay;

  @override
  void initState() {
    super.initState();
    _razorpay = Razorpay();
    _razorpay.on(Razorpay.EVENT_PAYMENT_SUCCESS, _onPaymentSuccess);
    _razorpay.on(Razorpay.EVENT_PAYMENT_ERROR, _onPaymentError);
    _razorpay.on(Razorpay.EVENT_EXTERNAL_WALLET, _onExternalWallet);
    _loadSettings();
  }

  Future<void> _loadSettings() async {
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get(ApiEndpoints.settings);
      if (mounted) {
        setState(() {
          _siteSettings = SiteSettings.fromJson(
            resp.data['data'] as Map<String, dynamic>? ?? {},
          );
          _loadingSettings = false;
        });
      }
    } catch (_) {
      if (mounted) {
        setState(() {
          _siteSettings = const SiteSettings();
          _loadingSettings = false;
        });
      }
    }
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

  Future<void> _submitOrder({
    String? paymentReference,
    String? razorpayOrderId,
    String? razorpaySignature,
  }) async {
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
        if (paymentReference != null) ...{
          'payment_reference': paymentReference,
          'payment_provider': 'razorpay',
          if (razorpayOrderId != null && razorpayOrderId.isNotEmpty)
            'razorpay_order_id': razorpayOrderId,
          if (razorpaySignature != null && razorpaySignature.isNotEmpty)
            'razorpay_signature': razorpaySignature,
        },
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
    } on DioException catch (e) {
      setState(() {
        _error = _extractError(e);
        _placing = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Unable to place order. Please try again.';
        _placing = false;
      });
    }
  }

  Future<void> _initiateRazorpay() async {
    final cart = context.read<CartProvider>();
    final amount = (cart.cart.totals.total * 100).toInt(); // paise

    if (amount <= 0) {
      setState(() => _error = 'Invalid order total for payment.');
      return;
    }

    try {
      final options = {
        'key': AppConfig.razorpayKeyId,
        'amount': amount,
        'name': 'NumNam',
        'description': 'Order payment',
        'timeout': 300,
        'prefill': {
          'name': _nameCtrl.text.trim(),
          'contact': _phoneCtrl.text.trim(),
        },
        'notes': {
          'shipping_name': _nameCtrl.text.trim(),
          'shipping_phone': _phoneCtrl.text.trim(),
        },
        'theme': {'color': '#FF6B8A'},
      };

      _razorpay.open(options);
    } catch (e) {
      setState(() => _error = 'Payment initialization failed');
    }
  }

  void _onPaymentSuccess(PaymentSuccessResponse response) {
    final paymentId = response.paymentId;
    if (paymentId == null || paymentId.isEmpty) {
      setState(() {
        _placing = false;
        _error =
            'Payment succeeded but reference is missing. Please contact support.';
      });
      return;
    }
    _submitOrder(
      paymentReference: paymentId,
      razorpayOrderId: response.orderId,
      razorpaySignature: response.signature,
    );
  }

  void _onPaymentError(PaymentFailureResponse response) {
    setState(() {
      _placing = false;
      _error = 'Payment failed. Please try again or use COD.';
    });
  }

  void _onExternalWallet(ExternalWalletResponse response) {
    // External wallet was selected; no action required here.
  }

  String _extractError(DioException e) {
    final data = e.response?.data;
    if (data is Map<String, dynamic>) {
      if (data['message'] != null) return data['message'].toString();
      if (data['errors'] is Map) {
        final errors = data['errors'] as Map;
        if (errors.isNotEmpty) {
          final first = errors.values.first;
          if (first is List && first.isNotEmpty) return first.first.toString();
          return first.toString();
        }
      }
    }
    return 'Unable to place order. Please try again.';
  }

  @override
  Widget build(BuildContext context) {
    final cart = context.watch<CartProvider>();
    return Scaffold(
      appBar: AppBar(title: const Text('Checkout')),
      bottomNavigationBar: const InnerPageNav(),
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
              validator: (v) {
                final value = v?.trim() ?? '';
                if (value.isEmpty) return 'Required';
                if (value.length < 10) return 'Enter a valid phone number';
                return null;
              },
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
              validator: (v) {
                final value = v?.trim() ?? '';
                if (value.isEmpty) return 'Required';
                if (value.length < 6) return 'Enter a valid pincode';
                return null;
              },
            ),
            const SizedBox(height: 24),
            Text('Coupon Code',
                style: GoogleFonts.baloo2(
                    fontSize: 18, fontWeight: FontWeight.w700, color: kNavy)),
            const SizedBox(height: 10),
            TextFormField(
              controller: _couponCtrl,
              decoration:
                  const InputDecoration(labelText: 'Enter coupon (optional)'),
            ),
            const SizedBox(height: 24),
            Text('Payment Method',
                style: GoogleFonts.baloo2(
                    fontSize: 20, fontWeight: FontWeight.w700, color: kNavy)),
            const SizedBox(height: 10),
            if (_loadingSettings)
              const Center(
                child: Padding(
                  padding: EdgeInsets.all(16.0),
                  child: CircularProgressIndicator(color: kCoral),
                ),
              )
            else ...[
              // Show COD only if admin has enabled it
              if (_siteSettings?.codEnabled == true) ...[
                _paymentTile('cod', 'Cash on Delivery', Icons.money_rounded),
                const SizedBox(height: 8),
              ],
              _paymentTile(
                  'razorpay', 'Pay Online (Razorpay)', Icons.credit_card),
            ],
            const SizedBox(height: 24),
            Text('Order Summary',
                style: GoogleFonts.baloo2(
                    fontSize: 20, fontWeight: FontWeight.w700, color: kNavy)),
            const SizedBox(height: 10),
            ...cart.cart.items.map((item) => Padding(
                  padding: const EdgeInsets.only(bottom: 10),
                  child: Row(
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.circular(8),
                        child: SizedBox(
                          width: 42,
                          height: 42,
                          child: item.displayImageUrl != null
                              ? CachedNetworkImage(
                                  imageUrl: item.displayImageUrl!,
                                  fit: BoxFit.cover,
                                  errorWidget: (_, __, ___) => Container(
                                    color: const Color(0xFFFFF5F8),
                                    child: const Icon(Icons.fastfood_rounded,
                                        size: 18, color: kCoral),
                                  ),
                                )
                              : Container(
                                  color: const Color(0xFFFFF5F8),
                                  child: const Icon(Icons.fastfood_rounded,
                                      size: 18, color: kCoral),
                                ),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Text('${item.name} × ${item.qty}',
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.poppins(fontSize: 13)),
                      ),
                      Text('₹${item.lineTotal.toStringAsFixed(0)}',
                          style: GoogleFonts.poppins(
                              fontSize: 13, fontWeight: FontWeight.w600)),
                    ],
                  ),
                )),
            const Divider(height: 20),
            _summaryRow(
                'Subtotal', '₹${cart.cart.totals.subtotal.toStringAsFixed(0)}'),
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
