import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../core/wishlist_provider.dart';
import '../../config/app_config.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/empty_state.dart';
import '../../shared/widgets/loading_indicator.dart';
import '../../app.dart';
import '../auth/auth_gate.dart';
import '../cart/cart_provider.dart';
import '../shop/product_detail_screen_redesign.dart';

class WishlistScreen extends StatefulWidget {
  const WishlistScreen({super.key});
  static const routeName = '/wishlist';

  @override
  State<WishlistScreen> createState() => _WishlistScreenState();
}

class _WishlistScreenState extends State<WishlistScreen> {
  List<_WishItem> _fullItems = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _loadFullDetails();
  }

  // We fetch full details for the list, but sync removal with the provider
  Future<void> _loadFullDetails() async {
    setState(() {
      _loading = true;
    });
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get(ApiEndpoints.wishlist);
      final data = resp.data['data'];
      List<dynamic> list;
      if (data is List) {
        list = data;
      } else if (data is Map && data['data'] != null) {
        list = data['data'] as List;
      } else {
        list = [];
      }

      if (mounted) {
        setState(() {
          _fullItems = list.map((e) => _WishItem.fromJson(e)).toList();
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _loading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final wishlistProvider = context.watch<WishlistProvider>();
    final cartCount = context.watch<CartProvider>().cart.itemCount;

    // Filter _fullItems based on what's still in the global provider (optimistic sync)
    final items = _fullItems
        .where((item) => wishlistProvider.isWished(item.productId))
        .toList();

    return Scaffold(
      backgroundColor: kCream,
      appBar: AppBar(
        title: const Text('My Wishlist'),
        centerTitle: true,
      ),
      body: AuthGate(
        child: _loading
            ? const LoadingIndicator(message: 'Loading your favorites...')
            : items.isEmpty
                ? const EmptyState(
                    icon: Icons.favorite_border_rounded,
                    title: 'Your Wishlist is Empty',
                    subtitle: 'Save items you love to find them easily later!',
                  )
                : RefreshIndicator(
                    color: kCoral,
                    onRefresh: _loadFullDetails,
                    child: ListView.separated(
                      padding: const EdgeInsets.all(16),
                      itemCount: items.length,
                      separatorBuilder: (_, __) => const SizedBox(height: 12),
                      itemBuilder: (_, i) {
                        final item = items[i];
                        return _WishTile(
                          item: item,
                          onRemove: () async {
                            await wishlistProvider
                                .toggleWishlist(item.productId);
                            // Reload to ensure UI is in sync
                            if (mounted) {
                              await _loadFullDetails();
                            }
                          },
                          onAddToCart: () {
                            context
                                .read<CartProvider>()
                                .addItem(item.productId, 1);
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                  content: Text('Added to cart'),
                                  duration: Duration(seconds: 1)),
                            );
                          },
                          onTap: () => Navigator.of(context).push(
                            MaterialPageRoute(
                              builder: (_) => ProductDetailScreenRedesign(
                                  productId: item.productId),
                            ),
                          ),
                        );
                      },
                    ),
                  ),
      ),
      bottomNavigationBar: AppBottomNav(
        currentIndex: -1,
        cartCount: cartCount,
        onTap: (index) => switchToShellTab(context, index),
      ),
    );
  }
}

class _WishItem {
  final int productId;
  final String name;
  final String? imageUrl;
  final String? image;
  final double price;
  final double? salePrice;

  _WishItem({
    required this.productId,
    required this.name,
    this.imageUrl,
    this.image,
    required this.price,
    this.salePrice,
  });

  String? get displayImageUrl => _normalizeImageUrl(imageUrl ?? image);

  factory _WishItem.fromJson(Map<String, dynamic> json) {
    final product = json['product'] as Map<String, dynamic>?;
    double toD(dynamic v) =>
        (v is num ? v.toDouble() : double.tryParse('$v') ?? 0);
    return _WishItem(
      productId: (json['product_id'] ?? product?['id'] ?? 0) as int,
      name: product?['name'] ?? json['name'] ?? '',
      imageUrl: (json['image_url'] ?? product?['image_url']) as String?,
      image: (json['image'] ?? product?['image']) as String?,
      price: toD(product?['price'] ?? json['price']),
      salePrice:
          product?['sale_price'] != null ? toD(product!['sale_price']) : null,
    );
  }

  static String? _normalizeImageUrl(String? raw) {
    if (raw == null || raw.trim().isEmpty) return null;

    final value = raw.trim();
    if (value.startsWith('http://') || value.startsWith('https://')) {
      return value;
    }

    if (value.startsWith('/')) {
      return '${AppConfig.siteBaseUrl}$value';
    }

    if (value.startsWith('storage/') ||
        value.startsWith('assets/') ||
        value.startsWith('cms-media/')) {
      return '${AppConfig.siteBaseUrl}/$value';
    }

    return '${AppConfig.siteBaseUrl}/storage/$value';
  }
}

class _WishTile extends StatelessWidget {
  const _WishTile(
      {required this.item,
      required this.onRemove,
      required this.onAddToCart,
      this.onTap});
  final _WishItem item;
  final Future<void> Function() onRemove;
  final VoidCallback onAddToCart;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
        ),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(14),
              child: SizedBox(
                width: 80,
                height: 80,
                child: item.displayImageUrl != null
                    ? CachedNetworkImage(
                        imageUrl: item.displayImageUrl!, fit: BoxFit.cover)
                    : Container(
                        color: kCream,
                        child: const Icon(Icons.image, color: kCoral)),
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(item.name,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: GoogleFonts.poppins(
                          fontSize: 14,
                          fontWeight: FontWeight.w600,
                          color: kNavy)),
                  const SizedBox(height: 6),
                  Row(
                    children: [
                      Text(
                          '₹${(item.salePrice ?? item.price).toStringAsFixed(0)}',
                          style: GoogleFonts.baloo2(
                              fontSize: 18,
                              fontWeight: FontWeight.w800,
                              color: kCoral)),
                      if (item.salePrice != null) ...[
                        const SizedBox(width: 8),
                        Text('₹${item.price.toStringAsFixed(0)}',
                            style: GoogleFonts.poppins(
                                fontSize: 12,
                                color: kNavy.withOpacity(0.4),
                                decoration: TextDecoration.lineThrough)),
                      ],
                    ],
                  ),
                ],
              ),
            ),
            Column(
              children: [
                IconButton(
                  icon:
                      const Icon(Icons.add_shopping_cart_rounded, color: kMint),
                  onPressed: onAddToCart,
                ),
                IconButton(
                  icon: const Icon(Icons.delete_outline_rounded, color: kCoral),
                  onPressed: onRemove,
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
