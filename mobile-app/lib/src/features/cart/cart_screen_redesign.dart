import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../shared/theme/colors.dart';
import '../cart/cart_provider.dart';
import '../checkout/checkout_screen.dart';

class CartScreenRedesign extends StatelessWidget {
  const CartScreenRedesign({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: kCream,
      body: Consumer<CartProvider>(
        builder: (context, cartProvider, _) {
          if (cartProvider.isLoading) {
            return const Center(
              child: CircularProgressIndicator(color: kCoral),
            );
          }

          if (cartProvider.cart.items.isEmpty) {
            return _buildEmptyCart(context);
          }

          return Column(
            children: [
              Expanded(
                child: ListView.separated(
                  padding: const EdgeInsets.all(16),
                  itemCount: cartProvider.cart.items.length,
                  separatorBuilder: (_, __) => const SizedBox(height: 12),
                  itemBuilder: (context, index) {
                    final item = cartProvider.cart.items[index];
                    return _buildCartItem(context, item, cartProvider);
                  },
                ),
              ),
              _buildCheckoutSection(context, cartProvider),
            ],
          );
        },
      ),
    );
  }

  Widget _buildEmptyCart(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(40),
            decoration: BoxDecoration(
              color: kCoral.withOpacity(0.1),
              shape: BoxShape.circle,
            ),
            child: Icon(
              Icons.shopping_cart_outlined,
              size: 80,
              color: kCoral.withOpacity(0.5),
            ),
          ),
          const SizedBox(height: 24),
          Text(
            'Your Cart is Empty',
            style: GoogleFonts.baloo2(
              fontSize: 28,
              fontWeight: FontWeight.w900,
              color: kNavy,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Add products to get started!',
            style: GoogleFonts.poppins(
              fontSize: 15,
              color: kNavy.withOpacity(0.6),
            ),
          ),
          const SizedBox(height: 32),
          ElevatedButton(
            onPressed: () => Navigator.pop(context),
            style: ElevatedButton.styleFrom(
              backgroundColor: kCoral,
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
              ),
              elevation: 0,
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Icon(Icons.shopping_bag_outlined),
                const SizedBox(width: 10),
                Text(
                  'Continue Shopping',
                  style: GoogleFonts.poppins(
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCartItem(
    BuildContext context,
    dynamic item,
    CartProvider cartProvider,
  ) {
    final product = item.product;
    final quantity = item.qty;

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
      ),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Product Image
            Container(
              width: 100,
              height: 100,
              decoration: BoxDecoration(
                color: kCream,
                borderRadius: BorderRadius.circular(14),
              ),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(14),
                child: product.imageUrl != null
                    ? CachedNetworkImage(
                        imageUrl: product.imageUrl!,
                        fit: BoxFit.cover,
                        placeholder: (_, __) => const Center(
                          child: CircularProgressIndicator(
                            color: kCoral,
                            strokeWidth: 2,
                          ),
                        ),
                        errorWidget: (_, __, ___) => Icon(
                          Icons.fastfood_rounded,
                          size: 40,
                          color: kNavy.withOpacity(0.3),
                        ),
                      )
                    : Icon(
                        Icons.fastfood_rounded,
                        size: 40,
                        color: kNavy.withOpacity(0.3),
                      ),
              ),
            ),
            const SizedBox(width: 12),

            // Product Details
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    product.name,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: GoogleFonts.poppins(
                      fontSize: 15,
                      fontWeight: FontWeight.w600,
                      color: kNavy,
                      height: 1.3,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      Text(
                        '₹${product.effectivePrice.toStringAsFixed(0)}',
                        style: GoogleFonts.baloo2(
                          fontSize: 22,
                          fontWeight: FontWeight.w800,
                          color: kCoral,
                        ),
                      ),
                      if (product.isOnSale) ...[
                        const SizedBox(width: 8),
                        Text(
                          '₹${product.price.toStringAsFixed(0)}',
                          style: GoogleFonts.poppins(
                            fontSize: 13,
                            color: kNavy.withOpacity(0.4),
                            decoration: TextDecoration.lineThrough,
                          ),
                        ),
                      ],
                    ],
                  ),
                  const SizedBox(height: 12),

                  // Quantity Controls
                  Row(
                    children: [
                      _buildQuantityButton(
                        Icons.remove,
                        () {
                          if (quantity > 1) {
                            cartProvider.updateQty(product.id, quantity - 1);
                          }
                        },
                      ),
                      Container(
                        width: 40,
                        alignment: Alignment.center,
                        child: Text(
                          quantity.toString(),
                          style: GoogleFonts.baloo2(
                            fontSize: 18,
                            fontWeight: FontWeight.w800,
                            color: kNavy,
                          ),
                        ),
                      ),
                      _buildQuantityButton(
                        Icons.add,
                        () {
                          if (quantity < product.stock) {
                            cartProvider.updateQty(product.id, quantity + 1);
                          }
                        },
                      ),
                      const Spacer(),
                      IconButton(
                        onPressed: () => cartProvider.removeItem(product.id),
                        icon: const Icon(Icons.delete_outline),
                        color: Colors.red,
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildQuantityButton(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 32,
        height: 32,
        decoration: BoxDecoration(
          color: kCoral.withOpacity(0.1),
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: kCoral, width: 1.5),
        ),
        child: Icon(icon, color: kCoral, size: 18),
      ),
    );
  }

  Widget _buildCheckoutSection(BuildContext context, CartProvider cartProvider) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, -4),
          ),
        ],
      ),
      child: SafeArea(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Subtotal
            _buildPriceRow(
              'Subtotal',
              cartProvider.cart.totals.subtotal,
              false,
            ),
            const SizedBox(height: 8),

            const Divider(height: 20),

            // Total
            _buildPriceRow(
              'Total',
              cartProvider.cart.totals.total,
              true,
            ),
            const SizedBox(height: 20),

            // Checkout Button
            SizedBox(
              width: double.infinity,
              height: 56,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.of(context).push(
                    MaterialPageRoute(
                      builder: (_) => const CheckoutScreen(),
                    ),
                  );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: kCoral,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 0,
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.lock_outline, size: 20),
                    const SizedBox(width: 12),
                    Text(
                      'Proceed to Checkout',
                      style: GoogleFonts.poppins(
                        fontSize: 16,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPriceRow(String label, double amount, bool isBold,
      {Color? color}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          label,
          style: GoogleFonts.poppins(
            fontSize: isBold ? 18 : 15,
            fontWeight: isBold ? FontWeight.w700 : FontWeight.w500,
            color: color ?? kNavy,
          ),
        ),
        Text(
          '₹${amount.abs().toStringAsFixed(0)}',
          style: GoogleFonts.baloo2(
            fontSize: isBold ? 26 : 20,
            fontWeight: FontWeight.w800,
            color: color ?? (isBold ? kCoral : kNavy),
          ),
        ),
      ],
    );
  }
}
