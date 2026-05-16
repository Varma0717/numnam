import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/product.dart';
import '../../models/review.dart';
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
  List<Review> _reviews = [];
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

      // Fetch product details
      debugPrint('🔍 Loading product ID: ${widget.productId}');
      final productResp =
          await api.dio.get('${ApiEndpoints.products}/${widget.productId}');

      debugPrint('✅ Product response received');
      debugPrint('Response data: ${productResp.data}');

      // Fetch reviews
      try {
        final reviewsResp = await api.dio.get(
          '${ApiEndpoints.products}/${widget.productId}/reviews',
        );
        if (mounted) {
          setState(() {
            _product =
                Product.fromJson(productResp.data['data'] ?? productResp.data);
            _reviews = _parseReviews(reviewsResp.data);
            _loading = false;
          });
          debugPrint('✅ Product loaded: ${_product?.name}');
        }
      } catch (e) {
        debugPrint('⚠️  Reviews failed (non-critical): $e');
        // Reviews endpoint might not exist, continue without them
        if (mounted) {
          setState(() {
            _product =
                Product.fromJson(productResp.data['data'] ?? productResp.data);
            _loading = false;
          });
          debugPrint('✅ Product loaded without reviews: ${_product?.name}');
        }
      }
    } catch (e) {
      debugPrint('❌ Product load error: $e');
      if (mounted) setState(() => _loading = false);
    }
  }

  List<Review> _parseReviews(dynamic data) {
    List<dynamic> list;
    if (data is Map && data['data'] != null) {
      list = data['data'] as List? ?? [];
    } else if (data is List) {
      list = data;
    } else {
      list = [];
    }
    return list.map((e) => Review.fromJson(e as Map<String, dynamic>)).toList();
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
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
        );
        setState(() => _addingToCart = false);
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              'Failed to add to cart',
              style: GoogleFonts.poppins(fontWeight: FontWeight.w600),
            ),
            backgroundColor: Colors.red,
            behavior: SnackBarBehavior.floating,
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
        );
        setState(() => _addingToCart = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return Scaffold(
        backgroundColor: kCream,
        appBar: AppBar(
          backgroundColor: Colors.white,
          elevation: 0,
        ),
        body: const Center(
          child: CircularProgressIndicator(color: kCoral),
        ),
      );
    }

    if (_product == null) {
      return Scaffold(
        backgroundColor: kCream,
        appBar: AppBar(
          backgroundColor: Colors.white,
          title: Text(
            'Product Not Found',
            style: GoogleFonts.baloo2(fontWeight: FontWeight.w800),
          ),
        ),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(Icons.error_outline,
                  size: 80, color: kNavy.withOpacity(0.3)),
              const SizedBox(height: 16),
              Text(
                'Product not found',
                style: GoogleFonts.baloo2(
                  fontSize: 22,
                  fontWeight: FontWeight.w700,
                  color: kNavy.withOpacity(0.5),
                ),
              ),
            ],
          ),
        ),
      );
    }

    final images = [
      if (_product!.imageUrl != null) _product!.imageUrl!,
      ..._product!.galleryUrls,
    ];

    return Scaffold(
      backgroundColor: kCream,
      body: CustomScrollView(
        slivers: [
          // App Bar with Image Gallery
          SliverAppBar(
            expandedHeight: 400,
            pinned: true,
            backgroundColor: Colors.white,
            leading: Container(
              margin: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: Colors.white,
                shape: BoxShape.circle,
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.1),
                    blurRadius: 8,
                  ),
                ],
              ),
              child: IconButton(
                icon: const Icon(Icons.arrow_back, color: kNavy),
                onPressed: () => Navigator.pop(context),
              ),
            ),
            actions: [
              Container(
                margin: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: Colors.white,
                  shape: BoxShape.circle,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.1),
                      blurRadius: 8,
                    ),
                  ],
                ),
                child: IconButton(
                  icon: const Icon(Icons.favorite_border, color: kCoral),
                  onPressed: () {
                    // TODO: Add to wishlist
                  },
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
                      onPageChanged: (index) =>
                          setState(() => _selectedImageIndex = index),
                      itemBuilder: (context, index) => CachedNetworkImage(
                        imageUrl: images[index],
                        fit: BoxFit.cover,
                        placeholder: (_, __) => Container(
                          color: kCream,
                          child: const Center(
                            child: CircularProgressIndicator(color: kCoral),
                          ),
                        ),
                        errorWidget: (_, __, ___) => Container(
                          color: kCream,
                          child: Icon(
                            Icons.fastfood_rounded,
                            size: 100,
                            color: kNavy.withOpacity(0.3),
                          ),
                        ),
                      ),
                    )
                  else
                    Container(
                      color: kCream,
                      child: Icon(
                        Icons.fastfood_rounded,
                        size: 100,
                        color: kNavy.withOpacity(0.3),
                      ),
                    ),
                  if (_product!.isOnSale)
                    Positioned(
                      bottom: 16,
                      left: 16,
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 16, vertical: 8),
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(
                            colors: [kCoral, Color(0xFFFF4568)],
                          ),
                          borderRadius: BorderRadius.circular(12),
                          boxShadow: [
                            BoxShadow(
                              color: kCoral.withOpacity(0.4),
                              blurRadius: 8,
                              offset: const Offset(0, 4),
                            ),
                          ],
                        ),
                        child: Text(
                          '${(((_product!.price - _product!.effectivePrice) / _product!.price) * 100).round()}% OFF',
                          style: GoogleFonts.poppins(
                            fontSize: 14,
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ),
                  if (images.length > 1)
                    Positioned(
                      bottom: 16,
                      right: 16,
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: Colors.black54,
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Text(
                          '${_selectedImageIndex + 1}/${images.length}',
                          style: GoogleFonts.poppins(
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ),
                ],
              ),
            ),
          ),

          // Product Details
          SliverToBoxAdapter(
            child: Container(
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
              ),
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Product Name & Category
                    if (_product!.ageGroup != null)
                      Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: kYellow.withOpacity(0.2),
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(color: kYellow, width: 1.5),
                        ),
                        child: Text(
                          _product!.ageGroup!,
                          style: GoogleFonts.poppins(
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                            color: kNavy,
                          ),
                        ),
                      ),
                    const SizedBox(height: 12),
                    Text(
                      _product!.name,
                      style: GoogleFonts.baloo2(
                        fontSize: 28,
                        fontWeight: FontWeight.w900,
                        color: kNavy,
                        height: 1.2,
                      ),
                    ),
                    const SizedBox(height: 8),
                    if (_product!.shortDescription != null)
                      Text(
                        _product!.shortDescription!,
                        style: GoogleFonts.poppins(
                          fontSize: 14,
                          color: kNavy.withOpacity(0.7),
                          height: 1.5,
                        ),
                      ),
                    const SizedBox(height: 16),

                    // Price & Stock
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.end,
                      children: [
                        Text(
                          '₹${_product!.effectivePrice.toStringAsFixed(0)}',
                          style: GoogleFonts.baloo2(
                            fontSize: 36,
                            fontWeight: FontWeight.w900,
                            color: kCoral,
                          ),
                        ),
                        if (_product!.isOnSale) ...[
                          const SizedBox(width: 10),
                          Padding(
                            padding: const EdgeInsets.only(bottom: 8),
                            child: Text(
                              '₹${_product!.price.toStringAsFixed(0)}',
                              style: GoogleFonts.poppins(
                                fontSize: 18,
                                color: kNavy.withOpacity(0.4),
                                decoration: TextDecoration.lineThrough,
                              ),
                            ),
                          ),
                        ],
                        const Spacer(),
                        Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 12, vertical: 6),
                          decoration: BoxDecoration(
                            color: _product!.inStock
                                ? kMint.withOpacity(0.2)
                                : Colors.red.withOpacity(0.1),
                            borderRadius: BorderRadius.circular(8),
                            border: Border.all(
                              color: _product!.inStock ? kMint : Colors.red,
                              width: 1.5,
                            ),
                          ),
                          child: Text(
                            _product!.inStock ? 'In Stock' : 'Out of Stock',
                            style: GoogleFonts.poppins(
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                              color: _product!.inStock ? kMint : Colors.red,
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 24),

                    // Quantity Selector
                    if (_product!.inStock) ...[
                      Text(
                        'Quantity',
                        style: GoogleFonts.poppins(
                          fontSize: 16,
                          fontWeight: FontWeight.w600,
                          color: kNavy,
                        ),
                      ),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          _buildQuantityButton(
                            Icons.remove,
                            () {
                              if (_quantity > 1) {
                                setState(() => _quantity--);
                              }
                            },
                          ),
                          Container(
                            width: 60,
                            alignment: Alignment.center,
                            child: Text(
                              _quantity.toString(),
                              style: GoogleFonts.baloo2(
                                fontSize: 24,
                                fontWeight: FontWeight.w800,
                                color: kNavy,
                              ),
                            ),
                          ),
                          _buildQuantityButton(
                            Icons.add,
                            () {
                              if (_quantity < _product!.stock) {
                                setState(() => _quantity++);
                              }
                            },
                          ),
                          const SizedBox(width: 16),
                          Text(
                            '${_product!.stock} available',
                            style: GoogleFonts.poppins(
                              fontSize: 13,
                              color: kNavy.withOpacity(0.5),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 24),
                    ],

                    // Description
                    if (_product!.description != null) ...[
                      const Divider(height: 32),
                      _buildSectionTitle(
                          'Description', Icons.description_outlined),
                      const SizedBox(height: 12),
                      Text(
                        _product!.description!,
                        style: GoogleFonts.poppins(
                          fontSize: 14,
                          color: kNavy.withOpacity(0.8),
                          height: 1.6,
                        ),
                      ),
                    ],

                    // Ingredients
                    if (_product!.ingredients != null) ...[
                      const Divider(height: 32),
                      _buildSectionTitle('Ingredients', Icons.eco_outlined),
                      const SizedBox(height: 12),
                      Text(
                        _product!.ingredients!,
                        style: GoogleFonts.poppins(
                          fontSize: 14,
                          color: kNavy.withOpacity(0.8),
                          height: 1.6,
                        ),
                      ),
                    ],

                    // Nutrition Facts
                    if (_product!.nutritionInfo != null) ...[
                      const Divider(height: 32),
                      _buildSectionTitle(
                          'Nutrition Info', Icons.favorite_outline),
                      const SizedBox(height: 16),
                      _buildNutritionTable(),
                    ],

                    // Reviews
                    if (_reviews.isNotEmpty) ...[
                      const Divider(height: 32),
                      _buildSectionTitle(
                        'Reviews (${_reviews.length})',
                        Icons.star_outline,
                      ),
                      const SizedBox(height: 16),
                      ..._reviews
                          .take(3)
                          .map((review) => _buildReviewCard(review)),
                    ],

                    const SizedBox(height: 100), // Space for bottom button
                  ],
                ),
              ),
            ),
          ),
        ],
      ),

      // Add to Cart Button
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 10,
              offset: const Offset(0, -4),
            ),
          ],
        ),
        child: SafeArea(
          child: SizedBox(
            height: 56,
            child: ElevatedButton(
              onPressed:
                  _product!.inStock && !_addingToCart ? _addToCart : null,
              style: ElevatedButton.styleFrom(
                backgroundColor: kCoral,
                foregroundColor: Colors.white,
                disabledBackgroundColor: kNavy.withOpacity(0.3),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16),
                ),
                elevation: 0,
              ),
              child: _addingToCart
                  ? const SizedBox(
                      width: 24,
                      height: 24,
                      child: CircularProgressIndicator(
                        color: Colors.white,
                        strokeWidth: 2,
                      ),
                    )
                  : Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(Icons.shopping_cart_outlined, size: 22),
                        const SizedBox(width: 12),
                        Text(
                          _product!.inStock ? 'Add to Cart' : 'Out of Stock',
                          style: GoogleFonts.poppins(
                            fontSize: 16,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ],
                    ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildQuantityButton(IconData icon, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 44,
        height: 44,
        decoration: BoxDecoration(
          color: kCoral.withOpacity(0.1),
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: kCoral, width: 2),
        ),
        child: Icon(icon, color: kCoral, size: 22),
      ),
    );
  }

  Widget _buildSectionTitle(String title, IconData icon) {
    return Row(
      children: [
        Icon(icon, color: kCoral, size: 22),
        const SizedBox(width: 10),
        Text(
          title,
          style: GoogleFonts.baloo2(
            fontSize: 20,
            fontWeight: FontWeight.w800,
            color: kNavy,
          ),
        ),
      ],
    );
  }

  Widget _buildNutritionTable() {
    final nutritionInfo = _product!.nutritionInfo!;
    final entries = nutritionInfo.entries.toList();

    return Container(
      decoration: BoxDecoration(
        color: kCream,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
      ),
      child: Column(
        children: List.generate(entries.length, (index) {
          final entry = entries[index];
          return Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
            decoration: BoxDecoration(
              border: index < entries.length - 1
                  ? const Border(
                      bottom: BorderSide(color: Color(0xFFFFD6E5), width: 1),
                    )
                  : null,
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  entry.key,
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
                    color: kNavy,
                  ),
                ),
                Text(
                  entry.value.toString(),
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: kCoral,
                  ),
                ),
              ],
            ),
          );
        }),
      ),
    );
  }

  Widget _buildReviewCard(Review review) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: kCream,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              CircleAvatar(
                radius: 20,
                backgroundColor: kCoral.withOpacity(0.2),
                child: Text(
                  review.user?.name.substring(0, 1).toUpperCase() ?? 'U',
                  style: GoogleFonts.baloo2(
                    fontSize: 18,
                    fontWeight: FontWeight.w800,
                    color: kCoral,
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      review.user?.name ?? 'Anonymous',
                      style: GoogleFonts.poppins(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                        color: kNavy,
                      ),
                    ),
                    Row(
                      children: List.generate(5, (index) {
                        return Icon(
                          index < review.rating
                              ? Icons.star
                              : Icons.star_border,
                          size: 14,
                          color: kYellow,
                        );
                      }),
                    ),
                  ],
                ),
              ),
            ],
          ),
          if (review.body != null) ...[
            const SizedBox(height: 12),
            Text(
              review.body!,
              style: GoogleFonts.poppins(
                fontSize: 13,
                color: kNavy.withOpacity(0.7),
                height: 1.5,
              ),
            ),
          ],
        ],
      ),
    );
  }
}
