import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/auth_provider.dart';
import '../../models/cart.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/empty_state.dart';
import '../../shared/widgets/loading_indicator.dart';
import '../auth/auth_gate.dart';
import '../checkout/checkout_screen.dart';
import 'cart_provider.dart';

class CartScreen extends StatefulWidget {
  const CartScreen({super.key});

  @override
  State<CartScreen> createState() => _CartScreenState();
}

class _CartScreenState extends State<CartScreen> {
  @override
  void initState() {
    super.initState();
    final auth = context.read<AuthProvider>();
    if (auth.isAuthenticated) {
      context.read<CartProvider>().loadCart();
    }
  }

  @override
  Widget build(BuildContext context) {
    return AuthGate(child: _CartBody());
  }
}

class _CartBody extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final cart = context.watch<CartProvider>();
    if (cart.isLoading) return const LoadingIndicator(message: 'Loading cart...');
    if (cart.isEmpty) {
      return EmptyState(
        icon: Icons.shopping_bag_outlined,
        title: 'Cart is Empty',
        subtitle: 'Start adding delicious items for your little one!',
        actionLabel: 'Shop Now',
        onAction: () {
          // Switch to Shop tab — handled by parent shell
          final navState = Navigator.of(context);
          if (navState.canPop()) navState.pop();
        },
      );
    }
    return Column(
      children: [
        Expanded(
          child: ListView.separated(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 16),
            itemCount: cart.cart.items.length,
            separatorBuilder: (_, __) => const SizedBox(height: 12),
            itemBuilder: (_, i) => _CartItemTile(item: cart.cart.items[i]),
          ),
        ),
        _CartSummaryBar(cart: cart),
      ],
    );
  }
}

class _CartItemTile extends StatelessWidget {
  const _CartItemTile({required this.item});
  final CartItem item;

  @override
  Widget build(BuildContext context) {
    final cart = context.read<CartProvider>();
    final imgUrl = item.imageUrl ?? '';
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
      ),
      child: Row(
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(12),
            child: SizedBox(
              width: 72,
              height: 72,
              child: imgUrl.isNotEmpty
                  ? CachedNetworkImage(imageUrl: imgUrl, fit: BoxFit.cover)
                  : Container(
                      color: const Color(0xFFFFF0F5),
                      child: const Icon(Icons.fastfood_rounded,
                          color: kCoral, size: 28)),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item.name,
                  style: GoogleFonts.poppins(
                      fontSize: 13, fontWeight: FontWeight.w600, color: kNavy),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 4),
                Text('₹${item.unitPrice.toStringAsFixed(0)}',
                    style: GoogleFonts.poppins(
                        fontSize: 14,
                        fontWeight: FontWeight.w700,
                        color: kCoral)),
                const SizedBox(height: 8),
                Row(
                  children: [
                    _QtyBtn(
                      icon: Icons.remove,
                      onTap: () {
                        if (item.qty <= 1) {
                          cart.removeItem(item.productId);
                        } else {
                          cart.updateQty(item.productId, item.qty - 1);
                        }
                      },
                    ),
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      child: Text('${item.qty}',
                          style: GoogleFonts.poppins(
                              fontSize: 15, fontWeight: FontWeight.w700)),
                    ),
                    _QtyBtn(
                      icon: Icons.add,
                      onTap: () =>
                          cart.updateQty(item.productId, item.qty + 1),
                    ),
                    const Spacer(),
                    Text('₹${item.lineTotal.toStringAsFixed(0)}',
                        style: GoogleFonts.poppins(
                            fontSize: 14,
                            fontWeight: FontWeight.w700,
                            color: kNavy)),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(width: 4),
          IconButton(
            icon: const Icon(Icons.delete_outline_rounded, size: 20),
            color: kCoral,
            onPressed: () => cart.removeItem(item.productId),
          ),
        ],
      ),
    );
  }
}

class _QtyBtn extends StatelessWidget {
  const _QtyBtn({required this.icon, required this.onTap});
  final IconData icon;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 30,
        height: 30,
        decoration: BoxDecoration(
          color: const Color(0xFFFFF0F5),
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: const Color(0xFFFFD6E5)),
        ),
        child: Icon(icon, size: 16, color: kCoral),
      ),
    );
  }
}

class _CartSummaryBar extends StatelessWidget {
  const _CartSummaryBar({required this.cart});
  final CartProvider cart;

  @override
  Widget build(BuildContext context) {
    final t = cart.cart.totals;
    return Container(
      padding: const EdgeInsets.fromLTRB(16, 14, 16, 24),
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(top: BorderSide(color: Color(0xFFFFD6E5), width: 2)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          _row('Subtotal', '₹${t.subtotal.toStringAsFixed(0)}'),
          if (t.shippingFee > 0)
            _row('Shipping', '₹${t.shippingFee.toStringAsFixed(0)}'),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Total',
                  style: GoogleFonts.poppins(
                      fontSize: 18, fontWeight: FontWeight.w700, color: kNavy)),
              Text('₹${t.total.toStringAsFixed(0)}',
                  style: GoogleFonts.poppins(
                      fontSize: 18, fontWeight: FontWeight.w700, color: kCoral)),
            ],
          ),
          const SizedBox(height: 14),
          SizedBox(
            width: double.infinity,
            height: 52,
            child: FilledButton(
              onPressed: () => Navigator.of(context)
                  .pushNamed(CheckoutScreen.routeName),
              child: const Text('Proceed to Checkout'),
            ),
          ),
        ],
      ),
    );
  }

  Widget _row(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label,
              style: GoogleFonts.poppins(
                  fontSize: 13, color: const Color(0xFF6B6B8A))),
          Text(value,
              style: GoogleFonts.poppins(
                  fontSize: 13, fontWeight: FontWeight.w600, color: kNavy)),
        ],
      ),
    );
  }
}
