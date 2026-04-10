import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../config/app_config.dart';
import '../../core/api_client.dart';
import '../../core/auth_provider.dart';
import '../../core/constants.dart';
import '../../models/product.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/error_view.dart';
import '../../shared/widgets/loading_indicator.dart';
import '../../shared/widgets/inner_page_nav.dart';
import '../../shared/widgets/price_tag.dart';
import '../cart/cart_provider.dart';
import 'product_reviews_section.dart';

class ProductDetailScreen extends StatefulWidget {
  const ProductDetailScreen({super.key});
  static const routeName = '/product-detail';

  @override
  State<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends State<ProductDetailScreen> {
  Product? _product;
  bool _loading = true;
  String? _error;
  int _qty = 1;
  bool _addingToCart = false;
  bool _wishlistLoading = false;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_product == null && _loading) {
      final slug = ModalRoute.of(context)!.settings.arguments as String;
      _load(slug);
    }
  }

  Future<void> _load(String slug) async {
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get('${ApiEndpoints.products}/$slug');
      setState(() {
        _product =
            Product.fromJson(resp.data['data'] as Map<String, dynamic>);
        _loading = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Failed to load product';
        _loading = false;
      });
    }
  }

  Future<void> _addToCart() async {
    final auth = context.read<AuthProvider>();
    if (!auth.isAuthenticated) {
      Navigator.of(context).pushNamed('/login');
      return;
    }
    setState(() => _addingToCart = true);
    try {
      final cart = context.read<CartProvider>();
      await cart.addItem(_product!.id, _qty);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Added ${_product!.name} to cart'),
            backgroundColor: kMint,
          ),
        );
      }
    } catch (_) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
              content: Text('Failed to add to cart'),
              backgroundColor: kCoral),
        );
      }
    }
    setState(() => _addingToCart = false);
  }

  Future<void> _toggleWishlist() async {
    final auth = context.read<AuthProvider>();
    if (!auth.isAuthenticated) {
      Navigator.of(context).pushNamed('/login');
      return;
    }
    setState(() => _wishlistLoading = true);
    try {
      final api = context.read<ApiClient>();
      await api.dio
          .post(ApiEndpoints.wishlist, data: {'product_id': _product!.id});
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
              content: Text('Added to wishlist'), backgroundColor: kMint),
        );
      }
    } catch (_) {}
    setState(() => _wishlistLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(_product?.name ?? 'Product'),
        actions: [
          if (_product != null)
            IconButton(
              onPressed: _wishlistLoading ? null : _toggleWishlist,
              icon: const Icon(Icons.favorite_border_rounded),
            ),
        ],
      ),
      body: _buildBody(),
      bottomNavigationBar: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (_product != null) _buildBottomBar(),
          const InnerPageNav(),
        ],
      ),
    );
  }

  Widget _buildBody() {
    if (_loading) return const LoadingIndicator();
    if (_error != null) return ErrorView(message: _error!);
    final p = _product!;
    final imgUrl = AppConfig.imageUrl(p.image);
    return ListView(
      children: [
        // Image
        AspectRatio(
          aspectRatio: 1,
          child: imgUrl.isNotEmpty
              ? CachedNetworkImage(imageUrl: imgUrl, fit: BoxFit.cover)
              : Container(
                  color: const Color(0xFFFFF0F5),
                  child: const Icon(Icons.fastfood_rounded,
                      size: 64, color: kCoral)),
        ),
        Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Badges
              if (p.badges.isNotEmpty)
                Wrap(
                  spacing: 6,
                  runSpacing: 6,
                  children: p.badges
                      .map((b) => Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 10, vertical: 4),
                            decoration: BoxDecoration(
                              color: kCoral.withOpacity(0.10),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(b,
                                style: GoogleFonts.poppins(
                                    fontSize: 10,
                                    fontWeight: FontWeight.w600,
                                    color: kCoral)),
                          ))
                      .toList(),
                ),
              if (p.badges.isNotEmpty) const SizedBox(height: 12),

              // Name
              Text(p.name,
                  style: GoogleFonts.baloo2(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: kNavy)),
              const SizedBox(height: 4),

              // Category & age
              if (p.productCategory != null || p.ageGroup != null)
                Text(
                  [p.productCategory?.name, p.ageGroup]
                      .where((e) => e != null)
                      .join(' · '),
                  style: GoogleFonts.poppins(
                      fontSize: 13, color: const Color(0xFF6B6B8A)),
                ),
              const SizedBox(height: 12),

              // Price
              PriceTag(
                  price: p.price, salePrice: p.salePrice, fontSize: 22),
              const SizedBox(height: 4),
              Text(
                p.inStock ? 'In Stock' : 'Out of Stock',
                style: GoogleFonts.poppins(
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  color: p.inStock ? kMint : kCoral,
                ),
              ),
              const SizedBox(height: 16),

              // Qty
              Row(
                children: [
                  Text('Quantity: ',
                      style: GoogleFonts.poppins(
                          fontSize: 14, fontWeight: FontWeight.w600)),
                  _qtyButton(Icons.remove,
                      () => setState(() => _qty = (_qty - 1).clamp(1, 99))),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    child: Text('$_qty',
                        style: GoogleFonts.poppins(
                            fontSize: 16, fontWeight: FontWeight.w700)),
                  ),
                  _qtyButton(Icons.add,
                      () => setState(() => _qty = (_qty + 1).clamp(1, 99))),
                ],
              ),

              // Description
              if (p.description != null && p.description!.isNotEmpty) ...[
                const SizedBox(height: 24),
                Text('Description',
                    style: GoogleFonts.baloo2(
                        fontSize: 18,
                        fontWeight: FontWeight.w700,
                        color: kNavy)),
                const SizedBox(height: 8),
                Text(p.description!,
                    style: GoogleFonts.poppins(
                        fontSize: 13,
                        color: const Color(0xFF4A4A6A),
                        height: 1.6)),
              ],

              // Ingredients
              if (p.ingredients != null && p.ingredients!.isNotEmpty) ...[
                const SizedBox(height: 20),
                Text('Ingredients',
                    style: GoogleFonts.baloo2(
                        fontSize: 18,
                        fontWeight: FontWeight.w700,
                        color: kNavy)),
                const SizedBox(height: 8),
                Text(p.ingredients!,
                    style: GoogleFonts.poppins(
                        fontSize: 13,
                        color: const Color(0xFF4A4A6A),
                        height: 1.6)),
              ],

              // Reviews
              const SizedBox(height: 24),
              ProductReviewsSection(productId: p.id),
              const SizedBox(height: 32),
            ],
          ),
        ),
      ],
    );
  }

  Widget _qtyButton(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 34,
        height: 34,
        decoration: BoxDecoration(
          color: const Color(0xFFFFF0F5),
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: const Color(0xFFFFD6E5)),
        ),
        child: Icon(icon, size: 18, color: kCoral),
      ),
    );
  }

  Widget _buildBottomBar() {
    return Container(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 24),
      decoration: const BoxDecoration(
        color: Colors.white,
        border: Border(top: BorderSide(color: Color(0xFFFFD6E5), width: 2)),
      ),
      child: SizedBox(
        height: 52,
        child: FilledButton.icon(
          onPressed:
              _product!.inStock && !_addingToCart ? _addToCart : null,
          icon: _addingToCart
              ? const SizedBox(
                  width: 18,
                  height: 18,
                  child: CircularProgressIndicator(
                      strokeWidth: 2, color: Colors.white))
              : const Icon(Icons.shopping_bag_rounded),
          label: Text(_product!.inStock ? 'Add to Cart' : 'Out of Stock'),
        ),
      ),
    );
  }
}
