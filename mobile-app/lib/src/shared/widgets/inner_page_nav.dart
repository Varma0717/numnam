import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../features/cart/cart_provider.dart';
import '../../app.dart';

/// Bottom navigation bar for inner (non-tab) screens.
/// Pops back to Shell and switches to the tapped tab.
class InnerPageNav extends StatelessWidget {
  const InnerPageNav({super.key});

  @override
  Widget build(BuildContext context) {
    final cartCount = context.watch<CartProvider>().cart.items.length;
    return AppBottomNav(
      currentIndex: -1, // no tab selected on inner pages
      cartCount: cartCount,
      onTap: (index) => switchToShellTab(context, index),
    );
  }
}
