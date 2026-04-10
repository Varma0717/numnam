import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../config/app_config.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/empty_state.dart';
import '../../shared/widgets/error_view.dart';
import '../../shared/widgets/loading_indicator.dart';
import '../../shared/widgets/price_tag.dart';
import '../auth/auth_gate.dart';
import '../cart/cart_provider.dart';
import '../shop/product_detail_screen.dart';

class WishlistScreen extends StatefulWidget {
  const WishlistScreen({super.key});
  static const routeName = '/wishlist';

  @override
  State<WishlistScreen> createState() => _WishlistScreenState();
}

class _WishlistScreenState extends State<WishlistScreen> {
  List<_WishItem> _items = [];
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
      setState(() {
        _items = list.map((e) => _WishItem.fromJson(e)).toList();
        _loading = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Failed to load wishlist';
        _loading = false;
      });
    }
  }

  Future<void> _remove(int productId) async {
    try {
      final api = context.read<ApiClient>();
      await api.dio.delete('${ApiEndpoints.wishlist}/$productId');
      setState(() => _items.removeWhere((i) => i.productId == productId));
    } catch (_) {}
  }

  Future<void> _addToCart(int productId) async {
    final cart = context.read<CartProvider>();
    await cart.addItem(productId, 1);
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Added to cart'),
          backgroundColor: kMint,
          duration: Duration(seconds: 1),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Wishlist')),
      body: AuthGate(
        child: _loading
            ? const LoadingIndicator(message: 'Loading wishlist...')
            : _error != null
                ? ErrorView(message: _error!, onRetry: _load)
                : _items.isEmpty
                    ? const EmptyState(
                        icon: Icons.favorite_border_rounded,
                        title: 'Your Wishlist is Empty',
                        subtitle: 'Save items you love here!',
                      )
                    : RefreshIndicator(
                        color: kCoral,
                        onRefresh: _load,
                        child: ListView.separated(
                          padding: const EdgeInsets.all(16),
                          itemCount: _items.length,
                          separatorBuilder: (_, __) =>
                              const SizedBox(height: 12),
                          itemBuilder: (_, i) {
                            final item = _items[i];
                            return _WishTile(
                              item: item,
                              onRemove: () => _remove(item.productId),
                              onAddToCart: () => _addToCart(item.productId),
                              onTap: () => Navigator.of(context).pushNamed(
                                ProductDetailScreen.routeName,
                                arguments: item.slug,
                              ),
                            );
                          },
                        ),
                      ),
      ),
    );
  }
}

class _WishItem {
  final int id;
  final int productId;
  final String name;
  final String slug;
  final String? image;
  final double price;
  final double? salePrice;

  _WishItem({
    required this.id,
    required this.productId,
    required this.name,
    required this.slug,
    this.image,
    required this.price,
    this.salePrice,
  });

  factory _WishItem.fromJson(Map<String, dynamic> json) {
    final product = json['product'] as Map<String, dynamic>?;
    double _d(dynamic v) => (v is num ? v.toDouble() : double.tryParse('$v') ?? 0);
    return _WishItem(
      id: json['id'] ?? 0,
      productId: json['product_id'] ?? product?['id'] ?? 0,
      name: product?['name'] ?? json['name'] ?? '',
      slug: product?['slug'] ?? '',
      image: product?['image'] ?? product?['primary_image'],
      price: _d(product?['price'] ?? json['price']),
      salePrice: product?['sale_price'] != null
          ? _d(product!['sale_price'])
          : null,
    );
  }
}

class _WishTile extends StatelessWidget {
  const _WishTile(
      {required this.item,
      required this.onRemove,
      required this.onAddToCart,
      this.onTap});
  final _WishItem item;
  final VoidCallback onRemove;
  final VoidCallback onAddToCart;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(10),
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
                child: item.image != null
                    ? CachedNetworkImage(
                        imageUrl: AppConfig.imageUrl(item.image!),
                        fit: BoxFit.cover,
                      )
                    : Container(
                        color: const Color(0xFFFFF0F5),
                        child: const Icon(Icons.image, color: kLavender)),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(item.name,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: GoogleFonts.poppins(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: kNavy)),
                  const SizedBox(height: 4),
                  PriceTag(
                      price: item.price, salePrice: item.salePrice, size: 13),
                ],
              ),
            ),
            Column(
              children: [
                IconButton(
                  icon: const Icon(Icons.shopping_cart_outlined, size: 20),
                  color: kMint,
                  onPressed: onAddToCart,
                ),
                IconButton(
                  icon: const Icon(Icons.delete_outline_rounded, size: 20),
                  color: kCoral,
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
