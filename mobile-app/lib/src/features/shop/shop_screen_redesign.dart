import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
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
  String _selectedCategory = 'All';
  String _sortBy = 'popular';
  final TextEditingController _searchController = TextEditingController();

  final List<String> _categories = [
    'All',
    '4-6 Months',
    '6-9 Months',
    '9-12 Months',
    '12+ Months',
  ];

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
    return list.map((e) => Product.fromJson(e as Map<String, dynamic>)).toList();
  }

  void _applyFilters() {
    setState(() {
      _filteredProducts = _products.where((product) {
        // Category filter
        if (_selectedCategory != 'All') {
          if (product.ageGroup != _selectedCategory) return false;
        }

        // Search filter
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
    return Scaffold(
      backgroundColor: kCream,
      body: Column(
        children: [
          // Search Bar
          Container(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
            color: Colors.white,
            child: SafeArea(
              bottom: false,
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
                        borderSide: const BorderSide(color: Color(0xFFFFD6E5), width: 2),
                      ),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(16),
                        borderSide: const BorderSide(color: Color(0xFFFFD6E5), width: 2),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(16),
                        borderSide: const BorderSide(color: kCoral, width: 2),
                      ),
                      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                        child: _buildFilterChip(
                          icon: Icons.tune,
                          label: _selectedCategory,
                          onTap: _showCategoryFilter,
                        ),
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: _buildFilterChip(
                          icon: Icons.sort,
                          label: _getSortLabel(),
                          onTap: _showSortOptions,
                        ),
                      ),
                      const SizedBox(width: 8),
                      _buildViewToggle(),
                    ],
                  ),
                ],
              ),
            ),
          ),

          // Products Grid/List
          Expanded(
            child: _loading
                ? const Center(
                    child: CircularProgressIndicator(color: kCoral),
                  )
                : _filteredProducts.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(
                              Icons.shopping_bag_outlined,
                              size: 80,
                              color: kNavy.withOpacity(0.3),
                            ),
                            const SizedBox(height: 16),
                            Text(
                              'No products found',
                              style: GoogleFonts.baloo2(
                                fontSize: 22,
                                fontWeight: FontWeight.w700,
                                color: kNavy.withOpacity(0.5),
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              'Try adjusting your filters',
                              style: GoogleFonts.poppins(
                                fontSize: 14,
                                color: kNavy.withOpacity(0.4),
                              ),
                            ),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        color: kCoral,
                        onRefresh: _load,
                        child: _gridView
                            ? GridView.builder(
                                padding: const EdgeInsets.all(16),
                                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                                  crossAxisCount: 2,
                                  childAspectRatio: 0.7,
                                  crossAxisSpacing: 12,
                                  mainAxisSpacing: 12,
                                ),
                                itemCount: _filteredProducts.length,
                                itemBuilder: (context, index) =>
                                    _buildProductGridCard(_filteredProducts[index]),
                              )
                            : ListView.separated(
                                padding: const EdgeInsets.all(16),
                                itemCount: _filteredProducts.length,
                                separatorBuilder: (_, __) => const SizedBox(height: 12),
                                itemBuilder: (context, index) =>
                                    _buildProductListCard(_filteredProducts[index]),
                              ),
                      ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChip({
    required IconData icon,
    required String label,
    required VoidCallback onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        decoration: BoxDecoration(
          color: kCream,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 18, color: kCoral),
            const SizedBox(width: 6),
            Flexible(
              child: Text(
                label,
                overflow: TextOverflow.ellipsis,
                style: GoogleFonts.poppins(
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  color: kNavy,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildViewToggle() {
    return GestureDetector(
      onTap: () => setState(() => _gridView = !_gridView),
      child: Container(
        padding: const EdgeInsets.all(10),
        decoration: BoxDecoration(
          color: kCoral,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Icon(
          _gridView ? Icons.view_list : Icons.grid_view,
          size: 24,
          color: Colors.white,
        ),
      ),
    );
  }

  void _showCategoryFilter() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (context) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Shop by Age',
              style: GoogleFonts.baloo2(
                fontSize: 24,
                fontWeight: FontWeight.w800,
                color: kNavy,
              ),
            ),
            const SizedBox(height: 16),
            ..._categories.map((category) {
              final isSelected = _selectedCategory == category;
              return GestureDetector(
                onTap: () {
                  setState(() => _selectedCategory = category);
                  _applyFilters();
                  Navigator.pop(context);
                },
                child: Container(
                  margin: const EdgeInsets.only(bottom: 8),
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: isSelected ? kCoral.withOpacity(0.1) : kCream,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(
                      color: isSelected ? kCoral : const Color(0xFFFFD6E5),
                      width: 2,
                    ),
                  ),
                  child: Row(
                    children: [
                      Icon(
                        isSelected ? Icons.radio_button_checked : Icons.radio_button_off,
                        color: isSelected ? kCoral : kNavy.withOpacity(0.3),
                      ),
                      const SizedBox(width: 12),
                      Text(
                        category,
                        style: GoogleFonts.poppins(
                          fontSize: 15,
                          fontWeight: isSelected ? FontWeight.w600 : FontWeight.w500,
                          color: isSelected ? kCoral : kNavy,
                        ),
                      ),
                    ],
                  ),
                ),
              );
            }).toList(),
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
      'name': 'Name: A-Z',
    };

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (context) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Sort By',
              style: GoogleFonts.baloo2(
                fontSize: 24,
                fontWeight: FontWeight.w800,
                color: kNavy,
              ),
            ),
            const SizedBox(height: 16),
            ...options.entries.map((entry) {
              final isSelected = _sortBy == entry.key;
              return GestureDetector(
                onTap: () {
                  setState(() => _sortBy = entry.key);
                  Navigator.pop(context);
                  _load();
                },
                child: Container(
                  margin: const EdgeInsets.only(bottom: 8),
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: isSelected ? kCoral.withOpacity(0.1) : kCream,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(
                      color: isSelected ? kCoral : const Color(0xFFFFD6E5),
                      width: 2,
                    ),
                  ),
                  child: Row(
                    children: [
                      Icon(
                        isSelected ? Icons.radio_button_checked : Icons.radio_button_off,
                        color: isSelected ? kCoral : kNavy.withOpacity(0.3),
                      ),
                      const SizedBox(width: 12),
                      Text(
                        entry.value,
                        style: GoogleFonts.poppins(
                          fontSize: 15,
                          fontWeight: isSelected ? FontWeight.w600 : FontWeight.w500,
                          color: isSelected ? kCoral : kNavy,
                        ),
                      ),
                    ],
                  ),
                ),
              );
            }).toList(),
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
      case 'name':
        return 'Name';
      default:
        return 'Popular';
    }
  }

  Widget _buildProductGridCard(Product product) {
    return GestureDetector(
      onTap: () {
        Navigator.of(context).push(
          MaterialPageRoute(
            builder: (_) => ProductDetailScreenRedesign(productId: product.id),
          ),
        );
      },
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Product Image
            Expanded(
              flex: 3,
              child: Container(
                decoration: BoxDecoration(
                  color: kCream,
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(18)),
                ),
                child: Stack(
                  children: [
                    Center(
                      child: product.imageUrl != null
                          ? CachedNetworkImage(
                              imageUrl: product.imageUrl!,
                              fit: BoxFit.cover,
                              width: double.infinity,
                              height: double.infinity,
                              placeholder: (_, __) => const Center(
                                child: CircularProgressIndicator(
                                  color: kCoral,
                                  strokeWidth: 2,
                                ),
                              ),
                              errorWidget: (_, __, ___) => Icon(
                                Icons.fastfood_rounded,
                                size: 48,
                                color: kNavy.withOpacity(0.3),
                              ),
                            )
                          : Icon(
                              Icons.fastfood_rounded,
                              size: 48,
                              color: kNavy.withOpacity(0.3),
                            ),
                    ),
                    if (product.isOnSale)
                      Positioned(
                        top: 8,
                        left: 8,
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                          decoration: BoxDecoration(
                            color: kCoral,
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Text(
                            'SALE',
                            style: GoogleFonts.poppins(
                              fontSize: 10,
                              fontWeight: FontWeight.w700,
                              color: Colors.white,
                            ),
                          ),
                        ),
                      ),
                    if (!product.inStock)
                      Positioned.fill(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Colors.black54,
                            borderRadius: const BorderRadius.vertical(top: Radius.circular(18)),
                          ),
                          child: Center(
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Text(
                                'Out of Stock',
                                style: GoogleFonts.poppins(
                                  fontSize: 11,
                                  fontWeight: FontWeight.w700,
                                  color: kNavy,
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                  ],
                ),
              ),
            ),
            // Product Info
            Expanded(
              flex: 2,
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      product.name,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: GoogleFonts.poppins(
                        fontSize: 13,
                        fontWeight: FontWeight.w600,
                        color: kNavy,
                        height: 1.2,
                      ),
                    ),
                    const Spacer(),
                    Row(
                      children: [
                        Text(
                          '₹${product.effectivePrice.toStringAsFixed(0)}',
                          style: GoogleFonts.baloo2(
                            fontSize: 18,
                            fontWeight: FontWeight.w800,
                            color: kCoral,
                          ),
                        ),
                        if (product.isOnSale) ...[
                          const SizedBox(width: 6),
                          Text(
                            '₹${product.price.toStringAsFixed(0)}',
                            style: GoogleFonts.poppins(
                              fontSize: 12,
                              color: kNavy.withOpacity(0.5),
                              decoration: TextDecoration.lineThrough,
                            ),
                          ),
                        ],
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

  Widget _buildProductListCard(Product product) {
    return GestureDetector(
      onTap: () {
        Navigator.of(context).push(
          MaterialPageRoute(
            builder: (_) => ProductDetailScreenRedesign(productId: product.id),
          ),
        );
      },
      child: Container(
        height: 120,
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
        ),
        child: Row(
          children: [
            // Product Image
            Container(
              width: 120,
              decoration: BoxDecoration(
                color: kCream,
                borderRadius: const BorderRadius.horizontal(left: Radius.circular(14)),
              ),
              child: Stack(
                children: [
                  Center(
                    child: product.imageUrl != null
                        ? CachedNetworkImage(
                            imageUrl: product.imageUrl!,
                            fit: BoxFit.cover,
                            width: double.infinity,
                            height: double.infinity,
                            placeholder: (_, __) => const CircularProgressIndicator(
                              color: kCoral,
                              strokeWidth: 2,
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
                  if (product.isOnSale)
                    Positioned(
                      top: 8,
                      left: 8,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 3),
                        decoration: BoxDecoration(
                          color: kCoral,
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          'SALE',
                          style: GoogleFonts.poppins(
                            fontSize: 9,
                            fontWeight: FontWeight.w700,
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ),
                ],
              ),
            ),
            // Product Info
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(14),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          product.name,
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: GoogleFonts.poppins(
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                            color: kNavy,
                            height: 1.3,
                          ),
                        ),
                        if (product.ageGroup != null) ...[
                          const SizedBox(height: 4),
                          Text(
                            product.ageGroup!,
                            style: GoogleFonts.poppins(
                              fontSize: 11,
                              color: kNavy.withOpacity(0.6),
                            ),
                          ),
                        ],
                      ],
                    ),
                    Row(
                      children: [
                        Text(
                          '₹${product.effectivePrice.toStringAsFixed(0)}',
                          style: GoogleFonts.baloo2(
                            fontSize: 20,
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
                              color: kNavy.withOpacity(0.5),
                              decoration: TextDecoration.lineThrough,
                            ),
                          ),
                        ],
                        const Spacer(),
                        if (!product.inStock)
                          Text(
                            'Out of Stock',
                            style: GoogleFonts.poppins(
                              fontSize: 11,
                              fontWeight: FontWeight.w600,
                              color: Colors.red,
                            ),
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
