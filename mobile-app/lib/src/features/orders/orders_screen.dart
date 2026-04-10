import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/order.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/empty_state.dart';
import '../../shared/widgets/error_view.dart';
import '../../shared/widgets/inner_page_nav.dart';
import '../../shared/widgets/loading_indicator.dart';
import '../auth/auth_gate.dart';
import 'order_detail_screen.dart';

class OrdersScreen extends StatefulWidget {
  const OrdersScreen({super.key});
  static const routeName = '/orders';

  @override
  State<OrdersScreen> createState() => _OrdersScreenState();
}

class _OrdersScreenState extends State<OrdersScreen> {
  List<Order> _orders = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get(ApiEndpoints.orders);
      // The response wraps orders in 'data' key from Laravel paginate
      final data = resp.data;
      List<dynamic> list;
      if (data is Map && data['data'] != null) {
        final inner = data['data'];
        if (inner is List) {
          list = inner;
        } else if (inner is Map && inner['data'] != null) {
          list = inner['data'] as List;
        } else {
          list = [];
        }
      } else {
        list = [];
      }
      setState(() {
        _orders = list
            .map((e) => Order.fromJson(e as Map<String, dynamic>))
            .toList();
        _loading = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Failed to load orders';
        _loading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('My Orders')),
      bottomNavigationBar: const InnerPageNav(),
      body: AuthGate(child: _buildBody()),
    );
  }

  Widget _buildBody() {
    if (_loading) return const LoadingIndicator(message: 'Loading orders...');
    if (_error != null) return ErrorView(message: _error!, onRetry: _load);
    if (_orders.isEmpty) {
      return const EmptyState(
        icon: Icons.receipt_long_rounded,
        title: 'No Orders Yet',
        subtitle: 'Your order history will appear here.',
      );
    }
    return RefreshIndicator(
      color: kCoral,
      onRefresh: _load,
      child: ListView.separated(
        padding: const EdgeInsets.all(16),
        itemCount: _orders.length,
        separatorBuilder: (_, __) => const SizedBox(height: 12),
        itemBuilder: (_, i) {
          final o = _orders[i];
          return _OrderTile(order: o, onTap: () {
            Navigator.of(context).pushNamed(
              OrderDetailScreen.routeName,
              arguments: o.orderNumber,
            );
          });
        },
      ),
    );
  }
}

class _OrderTile extends StatelessWidget {
  const _OrderTile({required this.order, this.onTap});
  final Order order;
  final VoidCallback? onTap;

  Color _statusColor() {
    switch (order.status.toLowerCase()) {
      case 'delivered':
        return kMint;
      case 'cancelled':
        return kCoral;
      case 'processing':
      case 'shipped':
        return kYellow;
      default:
        return kLavender;
    }
  }

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Text('#${order.orderNumber}',
                    style: GoogleFonts.poppins(
                        fontSize: 14,
                        fontWeight: FontWeight.w700,
                        color: kNavy)),
                const Spacer(),
                Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                  decoration: BoxDecoration(
                    color: _statusColor().withOpacity(0.15),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    order.status.toUpperCase(),
                    style: GoogleFonts.poppins(
                        fontSize: 10,
                        fontWeight: FontWeight.w700,
                        color: _statusColor()),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                Text('${order.items.length} item(s)',
                    style: GoogleFonts.poppins(
                        fontSize: 12, color: const Color(0xFF6B6B8A))),
                const Spacer(),
                Text('₹${order.total.toStringAsFixed(0)}',
                    style: GoogleFonts.poppins(
                        fontSize: 15,
                        fontWeight: FontWeight.w700,
                        color: kCoral)),
              ],
            ),
            if (order.paymentMethod != null) ...[
              const SizedBox(height: 4),
              Text(
                  'Payment: ${order.paymentMethod!.toUpperCase()} · ${order.paymentStatus ?? 'pending'}',
                  style: GoogleFonts.poppins(
                      fontSize: 11, color: const Color(0xFF9E9EBE))),
            ],
          ],
        ),
      ),
    );
  }
}
