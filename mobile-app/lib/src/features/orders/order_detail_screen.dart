import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/order.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/error_view.dart';
import '../../shared/widgets/inner_page_nav.dart';
import '../../shared/widgets/loading_indicator.dart';

class OrderDetailScreen extends StatefulWidget {
  const OrderDetailScreen({super.key});
  static const routeName = '/order-detail';

  @override
  State<OrderDetailScreen> createState() => _OrderDetailScreenState();
}

class _OrderDetailScreenState extends State<OrderDetailScreen> {
  Order? _order;
  bool _loading = true;
  String? _error;
  bool _cancelling = false;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_order == null && _loading) {
      final number = ModalRoute.of(context)!.settings.arguments as String;
      _load(number);
    }
  }

  Future<void> _load(String number) async {
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get('${ApiEndpoints.orders}/$number');
      final data = resp.data['data'] as Map<String, dynamic>;
      setState(() {
        _order = Order.fromJson(data);
        _loading = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Failed to load order';
        _loading = false;
      });
    }
  }

  Future<void> _cancel() async {
    setState(() => _cancelling = true);
    try {
      final api = context.read<ApiClient>();
      await api.dio.patch('${ApiEndpoints.orders}/${_order!.id}/cancel');
      setState(() {
        _order = Order(
          id: _order!.id,
          orderNumber: _order!.orderNumber,
          status: 'cancelled',
          subtotal: _order!.subtotal,
          discount: _order!.discount,
          shippingFee: _order!.shippingFee,
          total: _order!.total,
          paymentMethod: _order!.paymentMethod,
          paymentStatus: _order!.paymentStatus,
          items: _order!.items,
          createdAt: _order!.createdAt,
          shipName: _order!.shipName,
          shipPhone: _order!.shipPhone,
          shipAddress: _order!.shipAddress,
          shipCity: _order!.shipCity,
          shipState: _order!.shipState,
          shipPincode: _order!.shipPincode,
        );
        _cancelling = false;
      });
    } catch (_) {
      setState(() => _cancelling = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
              content: Text('Failed to cancel order'),
              backgroundColor: kCoral),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(_order != null ? '#${_order!.orderNumber}' : 'Order')),
      bottomNavigationBar: const InnerPageNav(),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (_loading) return const LoadingIndicator();
    if (_error != null) return ErrorView(message: _error!);
    final o = _order!;
    final canCancel = ['pending', 'processing'].contains(o.status.toLowerCase());
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        _section('Status', _statusChip(o.status)),
        const SizedBox(height: 16),

        Text('Items',
            style: GoogleFonts.baloo2(
                fontSize: 18, fontWeight: FontWeight.w700, color: kNavy)),
        const SizedBox(height: 8),
        ...o.items.map((item) => Container(
              margin: const EdgeInsets.only(bottom: 8),
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                border:
                    Border.all(color: const Color(0xFFFFD6E5), width: 1),
              ),
              child: Row(
                children: [
                  Expanded(
                      child: Text(item.productName,
                          style: GoogleFonts.poppins(
                              fontSize: 13,
                              fontWeight: FontWeight.w500,
                              color: kNavy))),
                  Text('×${item.quantity}',
                      style: GoogleFonts.poppins(
                          fontSize: 13, color: const Color(0xFF6B6B8A))),
                  const SizedBox(width: 12),
                  Text('₹${item.lineTotal.toStringAsFixed(0)}',
                      style: GoogleFonts.poppins(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: kNavy)),
                ],
              ),
            )),

        const SizedBox(height: 16),
        Text('Payment',
            style: GoogleFonts.baloo2(
                fontSize: 18, fontWeight: FontWeight.w700, color: kNavy)),
        const SizedBox(height: 8),
        _infoRow('Method', (o.paymentMethod ?? 'N/A').toUpperCase()),
        _infoRow('Status', o.paymentStatus ?? 'pending'),
        if (o.couponCode != null) _infoRow('Coupon', o.couponCode!),
        const Divider(height: 20),
        _infoRow('Subtotal', '₹${o.subtotal.toStringAsFixed(0)}'),
        if (o.discount > 0)
          _infoRow('Discount', '-₹${o.discount.toStringAsFixed(0)}'),
        if (o.shippingFee > 0)
          _infoRow('Shipping', '₹${o.shippingFee.toStringAsFixed(0)}'),
        _infoRow('Total', '₹${o.total.toStringAsFixed(0)}', bold: true),

        if (o.shipName != null) ...[
          const SizedBox(height: 16),
          Text('Shipping To',
              style: GoogleFonts.baloo2(
                  fontSize: 18, fontWeight: FontWeight.w700, color: kNavy)),
          const SizedBox(height: 8),
          Text(
            '${o.shipName}\n${o.shipAddress}\n${o.shipCity}, ${o.shipState} ${o.shipPincode}\n${o.shipPhone}',
            style: GoogleFonts.poppins(
                fontSize: 13, color: const Color(0xFF4A4A6A), height: 1.6),
          ),
        ],

        if (canCancel) ...[
          const SizedBox(height: 24),
          SizedBox(
            height: 48,
            child: OutlinedButton(
              onPressed: _cancelling ? null : _cancel,
              style: OutlinedButton.styleFrom(
                foregroundColor: kCoral,
                side: const BorderSide(color: kCoral),
              ),
              child: _cancelling
                  ? const SizedBox(
                      width: 18,
                      height: 18,
                      child: CircularProgressIndicator(
                          strokeWidth: 2, color: kCoral))
                  : const Text('Cancel Order'),
            ),
          ),
        ],
        const SizedBox(height: 32),
      ],
    );
  }

  Widget _section(String label, Widget child) {
    return Row(
      children: [
        Text('$label: ',
            style: GoogleFonts.poppins(
                fontSize: 14, fontWeight: FontWeight.w600, color: kNavy)),
        child,
      ],
    );
  }

  Widget _statusChip(String status) {
    Color color;
    switch (status.toLowerCase()) {
      case 'delivered':
        color = kMint;
        break;
      case 'cancelled':
        color = kCoral;
        break;
      case 'processing':
      case 'shipped':
        color = kYellow;
        break;
      default:
        color = kLavender;
    }
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
      decoration: BoxDecoration(
        color: color.withOpacity(0.15),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(status.toUpperCase(),
          style: GoogleFonts.poppins(
              fontSize: 12, fontWeight: FontWeight.w700, color: color)),
    );
  }

  Widget _infoRow(String label, String value, {bool bold = false}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label,
              style: GoogleFonts.poppins(
                  fontSize: 13,
                  color:
                      bold ? kNavy : const Color(0xFF6B6B8A),
                  fontWeight: bold ? FontWeight.w700 : FontWeight.w400)),
          Text(value,
              style: GoogleFonts.poppins(
                  fontSize: 13,
                  fontWeight: bold ? FontWeight.w700 : FontWeight.w600,
                  color: bold ? kCoral : kNavy)),
        ],
      ),
    );
  }
}
