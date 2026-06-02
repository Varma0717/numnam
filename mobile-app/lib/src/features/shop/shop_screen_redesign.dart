import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../core/wishlist_provider.dart';
import '../cart/cart_provider.dart';
import '../../models/product.dart';
import '../../shared/theme/colors.dart';
import 'product_detail_screen_redesign.dart';

class ShopScreenRedesign extends StatefulWidget {
  const ShopScreenRedesign({super.key});

  @override
  State<ShopScreenRedesign> createState() => _ShopScreenRedesignState();
}

class _ShopScreenRedesignState extends State<ShopScreenRedesign> {
  List<Product> _products = [];
  List<Product> _filteredProducts = [];
  bool _loading = true;
  bool _gridView = true;
  String _sortBy = 'popular';
  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get(ApiEndpoints.products, queryParameters: {
        'per_page': 50,
        'sort': _sortBy,
      });

      if (mounted) {
        setState(() {
          _products = _parseProducts(resp.data);
          _applyFilters();
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _loading = false);
    }
  }

  List<Product> _parseProducts(dynamic data) {
    List<dynamic> list;
    if (data is Map) {
      final dataField = data['data'];
      if (dataField is List) {
        list = dataField;
      } else if (dataField is Map && dataField['data'] != null) {
        list = dataField['data'] as List;
      } else {
        list = [];
      }
    } else if (data is List) {
      list = data;
    } else {
      list = [];
    }
    return list
        .map((e) => Product.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  void _applyFilters() {
    setState(() {
      _filteredProducts = _products.where((product) {
        if (_searchController.text.isNotEmpty) {
          final query = _searchController.text.toLowerCase();
          if (!product.name.toLowerCase().contains(query)) return false;
        }
        return true;
      }).toList();
    });
  }

  @override
  Widget build(BuildContext context) {
    final cartCount = context.watch<CartProvider>().cart.itemCount;
    return Scaffold(
      backgroundColor: kCream,
      appBar: AppBar(
        title: const Text('Our Shop'),
        centerTitle: true,
      ),
      body: Column(
        children: [
          // Search & Filters
          Container(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
            color: Colors.white,
            child: Column(
              children: [
                TextField(
                  controller: _searchController,
                  onChanged: (_) => _applyFilters(),
                  decoration: InputDecoration(
                    hintText: 'Search products...',
                    prefixIcon: const Icon(Icons.search, color: kCoral),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear, color: kNavy),
                            onPressed: () {
                              _searchController.clear();
                              _applyFilters();
                            },
                          )
                        : null,
                    filled: true,
                    fillColor: kCream,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(16),
                      borderSide: const BorderSide(
                          color: Color(0xFFFFD6E5), width: 1.5),
                    ),
                    enabledBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(16),
                      borderSide: const BorderSide(
                          color: Color(0xFFFFD6E5), width: 1.5),
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    _buildFilterChip(
                      icon: Icons.sort,
                      label: _getSortLabel(),
                      onTap: _showSortOptions,
                    ),
                    const Spacer(),
                    IconButton(
                      icon: Icon(_gridView ? Icons.view_list : Icons.grid_view,
                          color: kCoral),
                      onPressed: () => setState(() => _gridView = !_gridView),
                    ),
                  ],
                ),
              ],
            ),
          ),

          // Product List
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator(color: kCoral))
                : _filteredProducts.isEmpty
                    ? _buildEmpty()
                    : RefreshIndicator(
                        color: kCoral,
                        onRefresh: _load,
                        child: _gridView
                            ? GridView.builder(
                                padding: const EdgeInsets.all(16),
                                gridDelegate:
                                    const SliverGridDelegateWithFixedCrossAxisCount(
                                  crossAxisCount: 2,
                                  childAspectRatio: 0.65,
                                  crossAxisSpacing: 14,
                                  mainAxisSpacing: 14,
                                ),
                                itemCount: _filteredProducts.length,
                                itemBuilder: (context, index) =>
                                    _buildGridCard(_filteredProducts[index]),
                              )
                            : ListView.separated(
                                padding: const EdgeInsets.all(16),
                                itemCount: _filteredProducts.length,
                                separatorBuilder: (_, __) =>
                                    const SizedBox(height: 12),
                                itemBuilder: (context, index) =>
                                    _buildListCard(_filteredProducts[index]),
                              ),
                      ),
          ),
        ],
      ),
    );
  }

  Widget _buildEmpty() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.search_off_rounded,
              size: 80, color: kNavy.withOpacity(0.2)),
          const SizedBox(height: 16),
          Text('No products found',
              style: GoogleFonts.baloo2(
                  fontSize: 22,
                  fontWeight: FontWeight.w700,
                  color: kNavy.withOpacity(0.4))),
        ],
      ),
    );
  }

  Widget _buildFilterChip(
      {required IconData icon,
      required String label,
      required VoidCallback onTap}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: kCream,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFFFD6E5)),
        ),
        child: Row(
          children: [
            Icon(icon, size: 16, color: kCoral),
            const SizedBox(width: 6),
            Text(label,
                style: GoogleFonts.poppins(
                    fontSize: 12, fontWeight: FontWeight.w600, color: kNavy)),
          ],
        ),
      ),
    );
  }

  void _showSortOptions() {
    final options = {
      'popular': 'Most Popular',
      'newest': 'Newest First',
      'price_asc': 'Price: Low to High',
      'price_desc': 'Price: High to Low',
    };
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (_) => Container(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Sort By',
                style: GoogleFonts.baloo2(
                    fontSize: 22, fontWeight: FontWeight.w800)),
            const SizedBox(height: 12),
            ...options.entries.map((e) => ListTile(
                  title:
                      Text(e.value, style: GoogleFonts.poppins(fontSize: 15)),
                  trailing: _sortBy == e.key
                      ? const Icon(Icons.check, color: kCoral)
                      : null,
                  onTap: () {
                    setState(() => _sortBy = e.key);
                    Navigator.pop(context);
                    _load();
                  },
                )),
          ],
        ),
      ),
    );
  }

  String _getSortLabel() {
    switch (_sortBy) {
      case 'newest':
        return 'Newest';
      case 'price_asc':
        return 'Price ↑';
      case 'price_desc':
        return 'Price ↓';
      default:
        return 'Popular';
    }
  }

  Widget _buildGridCard(Product product) {
    final wishlist = context.watch<WishlistProvider>();
    final isWished = wishlist.isWished(product.id);
    final cart = context.read<CartProvider>();

    return GestureDetector(
      onTap: () => Navigator.of(context).push(
        MaterialPageRoute(
            builder: (_) => ProductDetailScreenRedesign(productId: product.id)),
      ),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(
                color: kNavy.withOpacity(0.04),
                blurRadius: 10,
                offset: const Offset(0, 4))
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: Stack(
                children: [
                  ClipRRect(
                    borderRadius:
                        const BorderRadius.vertical(top: Radius.circular(20)),
                    child: CachedNetworkImage(
                      imageUrl: product.imageUrl ?? '',
                      fit: BoxFit.cover,
                      width: double.infinity,
                      errorWidget: (_, __, ___) => Container(color: kCream),
                    ),
                  ),
                  Positioned(
                    top: 8,
                    right: 8,
                    child: GestureDetector(
                      onTap: () => wishlist.toggleWishlist(product.id),
                      child: CircleAvatar(
                        radius: 16,
                        backgroundColor: Colors.white,
                        child: Icon(
                            isWished ? Icons.favorite : Icons.favorite_border,
                            color: kCoral,
                            size: 18),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(product.name,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: GoogleFonts.poppins(
                          fontSize: 13,
                          fontWeight: FontWeight.w700,
                          color: kNavy)),
                  const SizedBox(height: 4),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('₹${product.effectivePrice.toStringAsFixed(0)}',
                          style: GoogleFonts.baloo2(
                              fontSize: 18,
                              fontWeight: FontWeight.w800,
                              color: kCoral)),
                      GestureDetector(
                        onTap: () {
                          cart.addItem(product.id, 1);
                          ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                  content: Text('Added to cart'),
                                  duration: Duration(seconds: 1)));
                        },
                        child: Container(
                          padding: const EdgeInsets.all(6),
                          decoration: BoxDecoration(
                              color: kCoral.withOpacity(0.1),
                              borderRadius: BorderRadius.circular(8)),
                          child: const Icon(Icons.add_shopping_cart_rounded,
                              color: kCoral, size: 18),
                        ),
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

  Widget _buildListCard(Product product) {
    final wishlist = context.watch<WishlistProvider>();
    final isWished = wishlist.isWished(product.id);
    final cart = context.read<CartProvider>();

    return GestureDetector(
      onTap: () => Navigator.of(context).push(
        MaterialPageRoute(
            builder: (_) => ProductDetailScreenRedesign(productId: product.id)),
      ),
      child: Container(
        height: 110,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
        ),
        child: Row(
          children: [
            ClipRRect(
              borderRadius:
                  const BorderRadius.horizontal(left: Radius.circular(15)),
              child: SizedBox(
                width: 110,
                height: 110,
                child: CachedNetworkImage(
                  imageUrl: product.imageUrl ?? '',
                  fit: BoxFit.cover,
                  errorWidget: (_, __, ___) => Container(color: kCream),
                ),
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(product.name,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: GoogleFonts.poppins(
                            fontSize: 14,
                            fontWeight: FontWeight.w700,
                            color: kNavy)),
                    const SizedBox(height: 4),
                    Text(product.ageGroup ?? '',
                        style: GoogleFonts.poppins(
                            fontSize: 11, color: kNavy.withOpacity(0.5))),
                    const Spacer(),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text('₹${product.effectivePrice.toStringAsFixed(0)}',
                            style: GoogleFonts.baloo2(
                                fontSize: 18,
                                fontWeight: FontWeight.w800,
                                color: kCoral)),
                        Row(
                          children: [
                            IconButton(
                              icon: Icon(
                                  isWished
                                      ? Icons.favorite
                                      : Icons.favorite_border,
                                  color: kCoral,
                                  size: 20),
                              onPressed: () =>
                                  wishlist.toggleWishlist(product.id),
                            ),
                            IconButton(
                              icon: const Icon(Icons.add_shopping_cart_rounded,
                                  color: kMint, size: 20),
                              onPressed: () {
                                cart.addItem(product.id, 1);
                                ScaffoldMessenger.of(context).showSnackBar(
                                    const SnackBar(
                                        content: Text('Added to cart'),
                                        duration: Duration(seconds: 1)));
                              },
                            ),
                          ],
                        ),
                      ],
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
}
