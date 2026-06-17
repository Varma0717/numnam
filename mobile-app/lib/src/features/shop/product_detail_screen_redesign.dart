import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../core/wishlist_provider.dart';
import '../../models/product.dart';
import '../../shared/theme/colors.dart';
import '../cart/cart_provider.dart';

class ProductDetailScreenRedesign extends StatefulWidget {
  static const routeName = '/product-detail-redesign';

  final int productId;
  const ProductDetailScreenRedesign({super.key, required this.productId});

  @override
  State<ProductDetailScreenRedesign> createState() =>
      _ProductDetailScreenRedesignState();
}

class _ProductDetailScreenRedesignState
    extends State<ProductDetailScreenRedesign> {
  Product? _product;
  bool _loading = true;
  bool _addingToCart = false;
  int _quantity = 1;
  int _selectedImageIndex = 0;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      final api = context.read<ApiClient>();

      final productResp =
          await api.dio.get('${ApiEndpoints.products}/${widget.productId}');

      if (mounted) {
        setState(() {
          _product =
              Product.fromJson(productResp.data['data'] ?? productResp.data);
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _loading = false);
    }
  }

  Future<void> _addToCart() async {
    if (_product == null || !_product!.inStock) return;

    setState(() => _addingToCart = true);
    try {
      final cartProvider = context.read<CartProvider>();
      await cartProvider.addItem(_product!.id, _quantity);

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              'Added to cart!',
              style: GoogleFonts.poppins(fontWeight: FontWeight.w600),
            ),
            backgroundColor: kMint,
            behavior: SnackBarBehavior.floating,
            margin: const EdgeInsets.all(20),
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
        );
        setState(() => _addingToCart = false);
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Failed to add to cart'),
            backgroundColor: Colors.red,
            behavior: SnackBarBehavior.floating,
          ),
        );
        setState(() => _addingToCart = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return const Scaffold(
        backgroundColor: kCream,
        body: Center(child: CircularProgressIndicator(color: kCoral)),
      );
    }

    if (_product == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Error')),
        body: const Center(child: Text('Product not found')),
      );
    }

    final images = [
      if (_product!.imageUrl != null) _product!.imageUrl!,
      ..._product!.galleryUrls,
    ];

    final wishlist = context.watch<WishlistProvider>();
    final isWished = wishlist.isWished(_product!.id);

    return Scaffold(
      backgroundColor: kCream,
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: MediaQuery.of(context).size.height * 0.45,
            pinned: true,
            backgroundColor: Colors.white,
            leading: Padding(
              padding: const EdgeInsets.all(8.0),
              child: CircleAvatar(
                backgroundColor: Colors.white,
                child: IconButton(
                  icon: const Icon(Icons.arrow_back, color: kNavy),
                  onPressed: () => Navigator.pop(context),
                ),
              ),
            ),
            actions: [
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: CircleAvatar(
                  backgroundColor: Colors.white,
                  child: IconButton(
                    icon: Icon(
                        isWished ? Icons.favorite : Icons.favorite_border,
                        color: kCoral),
                    onPressed: () => wishlist.toggleWishlist(_product!.id),
                  ),
                ),
              ),
            ],
            flexibleSpace: FlexibleSpaceBar(
              background: Stack(
                fit: StackFit.expand,
                children: [
                  if (images.isNotEmpty)
                    PageView.builder(
                      itemCount: images.length,
                      onPageChanged: (i) =>
                          setState(() => _selectedImageIndex = i),
                      itemBuilder: (context, i) => CachedNetworkImage(
                        imageUrl: images[i],
                        fit: BoxFit.cover,
                        placeholder: (_, __) => const Center(
                            child: CircularProgressIndicator(color: kCoral)),
                        errorWidget: (_, __, ___) => Container(
                            color: kCream,
                            child: const Icon(Icons.image,
                                size: 50, color: Colors.grey)),
                      ),
                    )
                  else
                    Container(
                        color: kCream,
                        child: const Icon(Icons.image,
                            size: 50, color: Colors.grey)),
                  if (images.length > 1)
                    Positioned(
                      bottom: 20,
                      left: 0,
                      right: 0,
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: List.generate(
                            images.length,
                            (index) => Container(
                                  margin:
                                      const EdgeInsets.symmetric(horizontal: 4),
                                  width: _selectedImageIndex == index ? 20 : 8,
                                  height: 8,
                                  decoration: BoxDecoration(
                                    color: _selectedImageIndex == index
                                        ? kCoral
                                        : Colors.white.withOpacity(0.5),
                                    borderRadius: BorderRadius.circular(4),
                                  ),
                                )),
                      ),
                    ),
                ],
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Container(
              padding: const EdgeInsets.all(24),
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      if (_product!.ageGroup != null)
                        Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 12, vertical: 6),
                          decoration: BoxDecoration(
                            color: kMint.withOpacity(0.1),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Text(
                            _product!.ageGroup!,
                            style: GoogleFonts.poppins(
                                fontSize: 12,
                                fontWeight: FontWeight.w700,
                                color: kMint),
                          ),
                        ),
                      Text(
                        '₹${_product!.effectivePrice.toStringAsFixed(0)}',
                        style: GoogleFonts.baloo2(
                            fontSize: 32,
                            fontWeight: FontWeight.w900,
                            color: kCoral),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Text(
                    _product!.name,
                    style: GoogleFonts.baloo2(
                        fontSize: 26,
                        fontWeight: FontWeight.w800,
                        color: kNavy,
                        height: 1.2),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    _product!.description ?? 'No description available.',
                    style: GoogleFonts.poppins(
                        fontSize: 14,
                        color: kNavy.withOpacity(0.6),
                        height: 1.6),
                  ),
                  const SizedBox(height: 32),

                  // Ingredients
                  if (_product!.ingredients != null &&
                      _product!.ingredients!.isNotEmpty) ...[
                    Text('Ingredients',
                        style: GoogleFonts.baloo2(
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                            color: kNavy)),
                    const SizedBox(height: 12),
                    Text(_product!.ingredients!,
                        style: GoogleFonts.poppins(
                            fontSize: 14,
                            color: kNavy.withOpacity(0.7),
                            height: 1.6)),
                    const SizedBox(height: 32),
                  ],

                  // Nutrition Info
                  if (_product!.nutritionInfo != null) ...[
                    Text('Nutrition Info',
                        style: GoogleFonts.baloo2(
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                            color: kNavy)),
                    const SizedBox(height: 16),
                    _buildNutritionRow(_product!.nutritionInfo!),
                    const SizedBox(height: 32),
                  ],

                  // Content/Details
                  if (_product!.content != null &&
                      _product!.content!.isNotEmpty) ...[
                    Text('Details',
                        style: GoogleFonts.baloo2(
                            fontSize: 20,
                            fontWeight: FontWeight.w800,
                            color: kNavy)),
                    const SizedBox(height: 12),
                    Text(_product!.content!,
                        style: GoogleFonts.poppins(
                            fontSize: 14,
                            color: kNavy.withOpacity(0.7),
                            height: 1.6)),
                    const SizedBox(height: 32),
                  ],

                  // Quantity
                  Text('Quantity',
                      style: GoogleFonts.baloo2(
                          fontSize: 20,
                          fontWeight: FontWeight.w800,
                          color: kNavy)),
                  const SizedBox(height: 16),
                  Row(
                    children: [
                      _qtyBtn(Icons.remove, () {
                        if (_quantity > 1) setState(() => _quantity--);
                      }),
                      const SizedBox(width: 24),
                      Text('$_quantity',
                          style: GoogleFonts.baloo2(
                              fontSize: 24, fontWeight: FontWeight.w800)),
                      const SizedBox(width: 24),
                      _qtyBtn(Icons.add, () => setState(() => _quantity++)),
                    ],
                  ),
                  const SizedBox(height: 100),
                ],
              ),
            ),
          ),
        ],
      ),
      bottomSheet: Container(
        padding: const EdgeInsets.fromLTRB(24, 16, 24, 32),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
                color: Colors.black.withOpacity(0.05),
                blurRadius: 10,
                offset: const Offset(0, -5))
          ],
        ),
        child: SizedBox(
          width: double.infinity,
          height: 56,
          child: ElevatedButton(
            onPressed: _addingToCart ? null : _addToCart,
            style: ElevatedButton.styleFrom(
              backgroundColor: kCoral,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16)),
              elevation: 0,
            ),
            child: _addingToCart
                ? const CircularProgressIndicator(color: Colors.white)
                : Text(
                    'Add to Cart • ₹${(_product!.effectivePrice * _quantity).toStringAsFixed(0)}',
                    style: GoogleFonts.poppins(
                        fontSize: 16, fontWeight: FontWeight.w700)),
          ),
        ),
      ),
    );
  }

  Widget _qtyBtn(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(10),
        decoration: BoxDecoration(
          color: kCream,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFFFD6E5)),
        ),
        child: Icon(icon, color: kNavy, size: 20),
      ),
    );
  }

  Widget _buildNutritionRow(Map<String, dynamic> info) {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Row(
        children: info.entries
            .map((e) => Container(
                  margin: const EdgeInsets.only(right: 12),
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: kCream,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: const Color(0xFFFFD6E5)),
                  ),
                  child: Column(
                    children: [
                      Text(e.value.toString(),
                          style: GoogleFonts.baloo2(
                              fontSize: 18,
                              fontWeight: FontWeight.w800,
                              color: kCoral)),
                      Text(e.key,
                          style: GoogleFonts.poppins(
                              fontSize: 11, color: kNavy.withOpacity(0.5))),
                    ],
                  ),
                ))
            .toList(),
      ),
    );
  }
}
