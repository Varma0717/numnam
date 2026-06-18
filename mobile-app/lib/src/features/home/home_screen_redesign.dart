import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../core/auth_provider.dart';
import '../../core/wishlist_provider.dart';
import '../cart/cart_provider.dart';
import '../../models/product.dart';
import '../../models/pricing_plan.dart';
import '../../shared/theme/colors.dart';
import '../../config/app_config.dart';
import '../shop/product_detail_screen_redesign.dart';
import '../shop/shop_screen_redesign.dart';
import '../subscriptions/subscriptions_screen_redesign.dart';

class HomeScreenRedesign extends StatefulWidget {
  const HomeScreenRedesign({super.key});

  @override
  State<HomeScreenRedesign> createState() => _HomeScreenRedesignState();
}

class _HomeScreenRedesignState extends State<HomeScreenRedesign> {
  List<Product> _featured = [];
  List<PricingPlan> _plans = [];
  bool _loading = true;
  int _currentBanner = 0;
  final PageController _bannerController = PageController();

  @override
  void initState() {
    super.initState();
    _load();
    _startBannerAutoScroll();
  }

  @override
  void dispose() {
    _bannerController.dispose();
    super.dispose();
  }

  void _startBannerAutoScroll() {
    Future.delayed(const Duration(seconds: 5), () {
      if (!mounted) return;
      if (_bannerController.hasClients) {
        final nextPage = (_currentBanner + 1) % 3;
        _bannerController.animateToPage(
          nextPage,
          duration: const Duration(milliseconds: 800),
          curve: Curves.easeInOutQuint,
        );
      }
      _startBannerAutoScroll();
    });
  }

  Future<void> _load() async {
    if (mounted) {
      setState(() => _loading = true);
    }

    final api = context.read<ApiClient>();
    List<Product> featured = [];
    List<PricingPlan> plans = [];

    try {
      final productsResp = await api.dio.get(
        ApiEndpoints.products,
        queryParameters: {'per_page': 12, 'page': 1, 'featured': true},
      );
      print('✓ Products response: ${productsResp.statusCode}');
      featured = _parseProducts(productsResp.data);
      print('✓ Parsed ${featured.length} featured products');

      if (featured.isEmpty) {
        final fallbackResp = await api.dio.get(
          ApiEndpoints.products,
          queryParameters: {'per_page': 12, 'page': 1},
        );
        featured = _parseProducts(fallbackResp.data);
        print('✓ Fallback products count: ${featured.length}');
      }
    } catch (e, st) {
      print('✗ Products load error: $e');
      print('✗ Products stack trace: $st');
    }

    try {
      final plansResp = await api.dio.get(ApiEndpoints.pricingPlans);
      print('✓ Plans response: ${plansResp.statusCode}');
      plans = _parsePlans(plansResp.data);
      print('✓ Parsed ${plans.length} pricing plans');
    } catch (e, st) {
      print('✗ Plans load error: $e');
      print('✗ Plans stack trace: $st');
    }

    if (mounted) {
      setState(() {
        _featured = featured;
        _plans = plans;
        _loading = false;
      });
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
    final parsed = <Product>[];
    for (final item in list) {
      if (item is Map<String, dynamic>) {
        parsed.add(Product.fromJson(item));
      }
    }
    return parsed;
  }

  List<PricingPlan> _parsePlans(dynamic data) {
    print('DEBUG: _parsePlans received data type: ${data.runtimeType}');
    print('DEBUG: _parsePlans data: $data');

    List<dynamic> list = [];

    if (data is Map) {
      final dataField = data['data'];
      print('DEBUG: dataField type: ${dataField.runtimeType}');

      if (dataField is List) {
        list = dataField;
      } else if (dataField is Map && dataField['data'] != null) {
        list = dataField['data'] as List;
      }
    } else if (data is List) {
      list = data;
    }

    print('✓ Parsed ${list.length} pricing plans');

    final result = <PricingPlan>[];
    for (int i = 0; i < list.length; i++) {
      try {
        final item = list[i];
        if (item is! Map<String, dynamic>) {
          print('WARN: Plan item $i is not a Map, got ${item.runtimeType}');
          continue;
        }
        result.add(PricingPlan.fromJson(item));
      } catch (e) {
        print('ERROR parsing pricing plan $i: $e');
      }
    }

    print('✓ Successfully parsed ${result.length} pricing plans');
    return result;
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final user = auth.user;

    return RefreshIndicator(
      color: kCoral,
      onRefresh: _load,
      child: CustomScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        slivers: [
          // Header & Greeting
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.fromLTRB(20, 16, 20, 8),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Hello, ${user?.name.split(' ').first ?? 'Parent'} 👋',
                              style: GoogleFonts.baloo2(
                                  fontSize: 28,
                                  fontWeight: FontWeight.w800,
                                  color: kNavy),
                            ),
                            Text(
                              'What would you like for your baby today?',
                              style: GoogleFonts.poppins(
                                  fontSize: 14,
                                  color: kNavy.withOpacity(0.5),
                                  fontWeight: FontWeight.w500),
                            ),
                          ],
                        ),
                      ),
                      CircleAvatar(
                        radius: 24,
                        backgroundColor: kCoral.withOpacity(0.1),
                        backgroundImage: user?.avatar != null
                            ? CachedNetworkImageProvider(user!.avatar!)
                            : null,
                        child: user?.avatar == null
                            ? const Icon(Icons.person, color: kCoral)
                            : null,
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),

          // Search Bar
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.fromLTRB(20, 12, 20, 12),
              child: Hero(
                tag: 'search_bar',
                child: Material(
                  color: Colors.transparent,
                  child: InkWell(
                    onTap: () {
                      // Switch to Shop tab (index 1)
                      final shellState = context
                          .findAncestorStateOfType<State<StatefulWidget>>();
                      if (shellState != null && shellState.mounted) {
                        // Find the root navigator and switch tab
                        Navigator.of(context, rootNavigator: true)
                            .popUntil((route) => route.isFirst);
                      }
                    },
                    borderRadius: BorderRadius.circular(16),
                    child: Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 16, vertical: 14),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(
                            color: const Color(0xFFFFD6E5), width: 1.5),
                        boxShadow: [
                          BoxShadow(
                              color: kCoral.withOpacity(0.06),
                              blurRadius: 15,
                              offset: const Offset(0, 5)),
                        ],
                      ),
                      child: Row(
                        children: [
                          const Icon(Icons.search_rounded,
                              color: kCoral, size: 22),
                          const SizedBox(width: 12),
                          Text(
                            'Search fresh food, cereals, tips...',
                            style: GoogleFonts.poppins(
                                color: Colors.grey.shade400, fontSize: 14),
                          ),
                          const Spacer(),
                          const Icon(Icons.tune_rounded,
                              color: kCoral, size: 20),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),

          // Hero Banners
          SliverToBoxAdapter(
            child: Container(
              height: 190,
              margin: const EdgeInsets.symmetric(vertical: 8),
              child: PageView(
                controller: _bannerController,
                onPageChanged: (i) => setState(() => _currentBanner = i),
                children: [
                  _buildBannerCard(
                    '20% OFF FIRST BOX',
                    'Start your subscription today!',
                    kCoral,
                    '${AppConfig.siteBaseUrl}/storage/banners/banner1.jpg',
                  ),
                  _buildBannerCard(
                    'PURE & ORGANIC',
                    'Zero preservatives, 100% love.',
                    kMint,
                    '${AppConfig.siteBaseUrl}/storage/banners/banner2.jpg',
                  ),
                  _buildBannerCard(
                    'EXPERT NUTRITION',
                    'Tailored for every growth stage.',
                    kYellow,
                    '${AppConfig.siteBaseUrl}/storage/banners/banner3.jpg',
                  ),
                ],
              ),
            ),
          ),

          // Featured Products Header
          SliverToBoxAdapter(
            child: _buildSectionHeader('Featured Products 🛍️', () {
              Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (_) => const ShopScreenRedesign(),
                ),
              );
            }),
          ),

          // Product Grid
          if (_loading)
            const SliverToBoxAdapter(
                child: Center(
                    child: Padding(
                        padding: EdgeInsets.all(40),
                        child: CircularProgressIndicator(color: kCoral))))
          else if (_featured.isEmpty)
            const SliverToBoxAdapter(
                child: Center(child: Text('No featured products')))
          else
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              sliver: SliverGrid(
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.64,
                  crossAxisSpacing: 16,
                  mainAxisSpacing: 16,
                ),
                delegate: SliverChildBuilderDelegate(
                  (context, index) => _buildProductCard(_featured[index]),
                  childCount: _featured.length,
                ),
              ),
            ),

          // Subscription section lives below products now.
          if (!_loading)
            SliverToBoxAdapter(
              child: _buildSubscriptionBottomSection(),
            ),

          // Footer Space
          const SliverToBoxAdapter(child: SizedBox(height: 100)),
        ],
      ),
    );
  }

  Widget _buildBannerCard(
      String title, String subtitle, Color color, String imageUrl) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(24),
      ),
      child: Stack(
        children: [
          // Background Image with error handling
          Positioned.fill(
            child: ClipRRect(
              borderRadius: BorderRadius.circular(24),
              child: CachedNetworkImage(
                imageUrl: imageUrl,
                fit: BoxFit.cover,
                color: Colors.black.withOpacity(0.3),
                colorBlendMode: BlendMode.darken,
                placeholder: (context, url) => Container(
                  color: color,
                ),
                errorWidget: (context, url, error) => Container(
                  color: color,
                  child: const Center(
                    child: Icon(
                      Icons.image_outlined,
                      color: Colors.white38,
                      size: 40,
                    ),
                  ),
                ),
              ),
            ),
          ),
          // Text Content
          Padding(
            padding: const EdgeInsets.all(24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                Text(title,
                    style: GoogleFonts.baloo2(
                        fontSize: 24,
                        fontWeight: FontWeight.w900,
                        color: Colors.white)),
                Text(subtitle,
                    style: GoogleFonts.poppins(
                        fontSize: 14,
                        color: Colors.white.withOpacity(0.9),
                        fontWeight: FontWeight.w500)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionHeader(String title, VoidCallback onSeeAll) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(20, 24, 20, 12),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(title,
              style: GoogleFonts.baloo2(
                  fontSize: 22, fontWeight: FontWeight.w800, color: kNavy)),
          TextButton(
            onPressed: onSeeAll,
            child: Text('See All',
                style: GoogleFonts.poppins(
                    fontSize: 13, fontWeight: FontWeight.w700, color: kCoral)),
          ),
        ],
      ),
    );
  }

  Widget _buildSubscriptionBottomSection() {
    return Padding(
      padding: const EdgeInsets.fromLTRB(20, 24, 20, 12),
      child: Container(
        width: double.infinity,
        decoration: BoxDecoration(
          gradient: const LinearGradient(
            colors: [kCoral, kLavender],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          borderRadius: BorderRadius.circular(28),
          boxShadow: [
            BoxShadow(
                color: kCoral.withOpacity(0.3),
                blurRadius: 20,
                offset: const Offset(0, 10)),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(28),
          child: Stack(
            children: [
              Positioned(
                right: -20,
                top: -20,
                child: Icon(Icons.star,
                    size: 120, color: Colors.white.withOpacity(0.1)),
              ),
              Padding(
                padding: const EdgeInsets.all(24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 12, vertical: 6),
                      decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.2),
                          borderRadius: BorderRadius.circular(10)),
                      child: Text('SUBSCRIPTIONS',
                          style: GoogleFonts.poppins(
                              fontSize: 10,
                              fontWeight: FontWeight.w800,
                              color: Colors.white)),
                    ),
                    const SizedBox(height: 12),
                    Text('The NumNam Way',
                        style: GoogleFonts.baloo2(
                            fontSize: 26,
                            fontWeight: FontWeight.w900,
                            color: Colors.white,
                            height: 1.1)),
                    Text(
                        'Fresh organic purees, delivered to your door every month.',
                        style: GoogleFonts.poppins(
                            fontSize: 13,
                            color: Colors.white.withOpacity(0.9))),
                    const SizedBox(height: 20),
                    if (_plans.isNotEmpty)
                      SingleChildScrollView(
                        scrollDirection: Axis.horizontal,
                        physics: const BouncingScrollPhysics(),
                        child: Row(
                          children: _plans
                              .take(3)
                              .map((plan) => _buildPlanMiniCard(plan))
                              .toList(),
                        ),
                      )
                    else
                      Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(14),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.15),
                          borderRadius: BorderRadius.circular(14),
                          border:
                              Border.all(color: Colors.white.withOpacity(0.3)),
                        ),
                        child: Text(
                          'Subscription plans will appear here once published.',
                          style: GoogleFonts.poppins(
                              fontSize: 12,
                              fontWeight: FontWeight.w600,
                              color: Colors.white),
                        ),
                      ),
                    const SizedBox(height: 20),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.of(context).push(
                            MaterialPageRoute(
                              builder: (_) =>
                                  const SubscriptionsScreenRedesign(),
                            ),
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.white,
                          foregroundColor: kCoral,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(14)),
                          elevation: 0,
                        ),
                        child: Text('EXPLORE ALL PLANS',
                            style: GoogleFonts.poppins(
                                fontSize: 14, fontWeight: FontWeight.w800)),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildPlanMiniCard(PricingPlan plan) {
    return GestureDetector(
      onTap: () {
        // Navigate to subscription detail
        Navigator.of(context).push(
          MaterialPageRoute(
            builder: (_) => const SubscriptionsScreenRedesign(),
          ),
        );
      },
      child: Container(
        width: 130,
        margin: const EdgeInsets.only(right: 12),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white.withOpacity(0.15),
          borderRadius: BorderRadius.circular(18),
          border: Border.all(color: Colors.white.withOpacity(0.3)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(plan.name,
                style: GoogleFonts.baloo2(
                    fontSize: 15,
                    fontWeight: FontWeight.w800,
                    color: Colors.white),
                maxLines: 1,
                overflow: TextOverflow.ellipsis),
            const SizedBox(height: 2),
            Text('₹${plan.price.toStringAsFixed(0)}',
                style: GoogleFonts.poppins(
                    fontSize: 18,
                    fontWeight: FontWeight.w900,
                    color: Colors.white)),
            Text('/ ${plan.billingCycle ?? 'mo'}',
                style: GoogleFonts.poppins(
                    fontSize: 10, color: Colors.white.withOpacity(0.7))),
          ],
        ),
      ),
    );
  }

  Widget _buildProductCard(Product product) {
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
          borderRadius: BorderRadius.circular(24),
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
                  Hero(
                    tag: 'prod_${product.id}',
                    child: ClipRRect(
                      borderRadius:
                          const BorderRadius.vertical(top: Radius.circular(24)),
                      child: SizedBox(
                        width: double.infinity,
                        height: double.infinity,
                        child: CachedNetworkImage(
                          imageUrl: product.displayImageUrl ?? '',
                          fit: BoxFit.cover,
                          errorWidget: (_, __, ___) => Container(
                              color: kCream,
                              child:
                                  const Icon(Icons.image, color: Colors.grey)),
                        ),
                      ),
                    ),
                  ),
                  Positioned(
                    top: 10,
                    right: 10,
                    child: GestureDetector(
                      onTap: () => wishlist.toggleWishlist(product.id),
                      child: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: const BoxDecoration(
                            color: Colors.white, shape: BoxShape.circle),
                        child: Icon(
                            isWished ? Icons.favorite : Icons.favorite_border,
                            color: kCoral,
                            size: 18),
                      ),
                    ),
                  ),
                  if (product.isOnSale)
                    Positioned(
                      top: 10,
                      left: 10,
                      child: Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                            color: kCoral,
                            borderRadius: BorderRadius.circular(8)),
                        child: Text('SALE',
                            style: GoogleFonts.poppins(
                                fontSize: 9,
                                fontWeight: FontWeight.w800,
                                color: Colors.white)),
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
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('₹${product.effectivePrice.toStringAsFixed(0)}',
                              style: GoogleFonts.baloo2(
                                  fontSize: 18,
                                  fontWeight: FontWeight.w800,
                                  color: kCoral)),
                          if (product.isOnSale)
                            Text('₹${product.price.toStringAsFixed(0)}',
                                style: GoogleFonts.poppins(
                                    fontSize: 11,
                                    color: kNavy.withOpacity(0.3),
                                    decoration: TextDecoration.lineThrough)),
                        ],
                      ),
                      GestureDetector(
                        onTap: () {
                          cart.addItem(product.id, 1);
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Added to cart'),
                              behavior: SnackBarBehavior.floating,
                              backgroundColor: kNavy,
                              duration: Duration(seconds: 1),
                            ),
                          );
                        },
                        child: Container(
                          padding: const EdgeInsets.all(8),
                          decoration: BoxDecoration(
                              color: kCoral.withOpacity(0.1),
                              borderRadius: BorderRadius.circular(10)),
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
}
