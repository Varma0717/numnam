import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/product.dart';
import '../../models/pricing_plan.dart';
import '../../shared/theme/colors.dart';
import '../shop/product_detail_screen_redesign.dart';
import '../shop/shop_screen.dart';
import '../subscriptions/subscriptions_screen.dart';
import '../blog/blog_list_screen.dart';

class HomeScreenRedesign extends StatefulWidget {
  const HomeScreenRedesign({super.key});

  @override
  State<HomeScreenRedesign> createState() => _HomeScreenRedesignState();
}

class _HomeScreenRedesignState extends State<HomeScreenRedesign> {
  List<Product> _featured = [];
  List<Product> _bestSellers = [];
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
    Future.delayed(const Duration(seconds: 4), () {
      if (!mounted) return;
      final nextPage = (_currentBanner + 1) % 3;
      _bannerController.animateToPage(
        nextPage,
        duration: const Duration(milliseconds: 600),
        curve: Curves.easeInOut,
      );
      _startBannerAutoScroll();
    });
  }

  Future<void> _load() async {
    try {
      final api = context.read<ApiClient>();

      // Fetch featured products
      final featuredResp =
          await api.dio.get(ApiEndpoints.products, queryParameters: {
        'per_page': 6,
        'featured': 1,
      });

      // Fetch best sellers (or popular products)
      final bestSellersResp =
          await api.dio.get(ApiEndpoints.products, queryParameters: {
        'per_page': 6,
        'sort': 'popular',
      });

      // Fetch subscription plans
      final plansResp = await api.dio.get(ApiEndpoints.pricingPlans);

      if (mounted) {
        setState(() {
          _featured = _parseProducts(featuredResp.data);
          _bestSellers = _parseProducts(bestSellersResp.data);
          _plans = _parsePlans(plansResp.data);
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

  List<PricingPlan> _parsePlans(dynamic data) {
    List<dynamic> list;
    if (data is Map && data['data'] != null) {
      list = data['data'] as List? ?? [];
    } else if (data is List) {
      list = data;
    } else {
      list = [];
    }
    return list
        .map((e) => PricingPlan.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      color: kCoral,
      onRefresh: _load,
      child: CustomScrollView(
        slivers: [
          // Image-Only Banner Slider (No Text, No Buttons)
          SliverToBoxAdapter(
            child: Container(
              height: 220,
              margin: const EdgeInsets.fromLTRB(16, 16, 16, 12),
              child: Stack(
                children: [
                  PageView(
                    controller: _bannerController,
                    onPageChanged: (index) =>
                        setState(() => _currentBanner = index),
                    children: [
                      _buildImageBanner(
                        'https://numnam.com/storage/banners/banner1.jpg',
                        kCoral,
                      ),
                      _buildImageBanner(
                        'https://numnam.com/storage/banners/banner2.jpg',
                        kMint,
                      ),
                      _buildImageBanner(
                        'https://numnam.com/storage/banners/banner3.jpg',
                        kYellow,
                      ),
                    ],
                  ),
                  Positioned(
                    bottom: 12,
                    left: 0,
                    right: 0,
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: List.generate(3, (index) {
                        return Container(
                          margin: const EdgeInsets.symmetric(horizontal: 4),
                          width: _currentBanner == index ? 28 : 8,
                          height: 8,
                          decoration: BoxDecoration(
                            color: _currentBanner == index
                                ? Colors.white
                                : Colors.white.withOpacity(0.5),
                            borderRadius: BorderRadius.circular(4),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.black.withOpacity(0.2),
                                blurRadius: 4,
                                offset: const Offset(0, 2),
                              ),
                            ],
                          ),
                        );
                      }),
                    ),
                  ),
                ],
              ),
            ),
          ),

          // Quick Categories Section
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildSectionTitle('Shop by Age', Icons.child_care_rounded),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                          child: _buildCategoryCard(
                              '4-6 Months', FontAwesomeIcons.baby, kCoral)),
                      const SizedBox(width: 12),
                      Expanded(
                          child: _buildCategoryCard('6-9 Months',
                              FontAwesomeIcons.babyCarriage, kYellow)),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Expanded(
                          child: _buildCategoryCard(
                              '9-12 Months', FontAwesomeIcons.utensils, kMint)),
                      const SizedBox(width: 12),
                      Expanded(
                          child: _buildCategoryCard('12+ Months',
                              FontAwesomeIcons.plateWheat, kLavender)),
                    ],
                  ),
                ],
              ),
            ),
          ),

          // Trust Badges
          SliverToBoxAdapter(
            child: Container(
              margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceAround,
                children: [
                  _buildTrustBadge(FontAwesomeIcons.leaf, 'Organic', kMint),
                  _buildTrustBadge(
                      FontAwesomeIcons.flaskVial, 'Lab Tested', kLavender),
                  _buildTrustBadge(
                      FontAwesomeIcons.shield, 'No Additives', kCoral),
                ],
              ),
            ),
          ),

          // Featured Products Section
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  _buildSectionTitle('Popular Picks', Icons.star_rounded),
                  TextButton(
                    onPressed: () {
                      Navigator.of(context).push(
                        MaterialPageRoute(builder: (_) => const ShopScreen()),
                      );
                    },
                    child: Text(
                      'View All',
                      style:
                          TextStyle(color: kCoral, fontWeight: FontWeight.w600),
                    ),
                  ),
                ],
              ),
            ),
          ),

          if (_loading)
            const SliverToBoxAdapter(
              child: Padding(
                padding: EdgeInsets.all(32),
                child: Center(child: CircularProgressIndicator(color: kCoral)),
              ),
            )
          else if (_featured.isEmpty)
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Container(
                  padding: const EdgeInsets.all(24),
                  decoration: BoxDecoration(
                    color: kYellow.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: Center(
                    child: Column(
                      children: [
                        Icon(Icons.shopping_bag_outlined,
                            size: 48, color: kNavy.withOpacity(0.5)),
                        const SizedBox(height: 12),
                        Text(
                          'No featured products yet',
                          style: TextStyle(
                              color: kNavy.withOpacity(0.7), fontSize: 16),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              sliver: SliverGrid(
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  childAspectRatio: 0.7,
                  crossAxisSpacing: 12,
                  mainAxisSpacing: 12,
                ),
                delegate: SliverChildBuilderDelegate(
                  (context, index) => _buildModernProductCard(_featured[index]),
                  childCount: _featured.length > 6 ? 6 : _featured.length,
                ),
              ),
            ),

          // Subscription Plans Section
          if (_plans.isNotEmpty)
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(16, 24, 16, 8),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    _buildSectionTitle(
                        'Subscription Plans', Icons.card_membership_rounded),
                    TextButton(
                      onPressed: () {
                        Navigator.of(context).push(
                          MaterialPageRoute(
                              builder: (_) => const SubscriptionsScreen()),
                        );
                      },
                      child: Text(
                        'Explore',
                        style: TextStyle(
                            color: kCoral, fontWeight: FontWeight.w600),
                      ),
                    ),
                  ],
                ),
              ),
            ),

          if (_plans.isNotEmpty)
            SliverToBoxAdapter(
              child: Container(
                height: 220,
                margin: const EdgeInsets.only(bottom: 16),
                child: ListView.separated(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  scrollDirection: Axis.horizontal,
                  itemCount: _plans.length > 3 ? 3 : _plans.length,
                  separatorBuilder: (_, __) => const SizedBox(width: 12),
                  itemBuilder: (_, index) =>
                      _buildSubscriptionCard(_plans[index]),
                ),
              ),
            ),

          // Best Sellers Section
          if (_bestSellers.isNotEmpty)
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
                child: _buildSectionTitle(
                    'Best Sellers', Icons.trending_up_rounded),
              ),
            ),

          if (_bestSellers.isNotEmpty)
            SliverToBoxAdapter(
              child: Container(
                height: 200,
                margin: const EdgeInsets.only(bottom: 24),
                child: ListView.separated(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  scrollDirection: Axis.horizontal,
                  itemCount: _bestSellers.length,
                  separatorBuilder: (_, __) => const SizedBox(width: 12),
                  itemBuilder: (_, index) => SizedBox(
                    width: 150,
                    child: _buildCompactProductCard(_bestSellers[index]),
                  ),
                ),
              ),
            ),

          // Blog & Tips Section
          SliverToBoxAdapter(
            child: Container(
              margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [kLavender.withOpacity(0.2), kMint.withOpacity(0.2)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: kLavender.withOpacity(0.4), width: 2),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          color: kLavender.withOpacity(0.3),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const FaIcon(FontAwesomeIcons.bookOpen,
                            color: kLavender, size: 22),
                      ),
                      const SizedBox(width: 12),
                      Text(
                        'Tips & Recipes',
                        style: GoogleFonts.baloo2(
                          fontSize: 22,
                          fontWeight: FontWeight.w800,
                          color: kNavy,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Text(
                    'Expert advice on baby nutrition, feeding tips, and delicious recipes',
                    style: GoogleFonts.poppins(
                      fontSize: 14,
                      color: const Color(0xFF6B6B8A),
                    ),
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () {
                        Navigator.of(context)
                            .pushNamed(BlogListScreen.routeName);
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: kLavender,
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(14),
                        ),
                      ),
                      child: Text(
                        'Read Our Blog',
                        style: GoogleFonts.poppins(
                          fontSize: 15,
                          fontWeight: FontWeight.w700,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),

          // Bottom Spacing
          const SliverToBoxAdapter(child: SizedBox(height: 80)),
        ],
      ),
    );
  }

  Widget _buildImageBanner(String imageUrl, Color fallbackColor) {
    return Container(
      decoration: BoxDecoration(
        color: fallbackColor.withOpacity(0.1),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: fallbackColor.withOpacity(0.15),
            blurRadius: 20,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(20),
        child: CachedNetworkImage(
          imageUrl: imageUrl,
          fit: BoxFit.cover,
          width: double.infinity,
          height: double.infinity,
          placeholder: (_, __) => Container(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [
                  fallbackColor.withOpacity(0.3),
                  fallbackColor.withOpacity(0.1),
                ],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: const Center(
              child: CircularProgressIndicator(
                color: Colors.white,
                strokeWidth: 2,
              ),
            ),
          ),
          errorWidget: (_, __, ___) => Container(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [
                  fallbackColor.withOpacity(0.4),
                  fallbackColor.withOpacity(0.2),
                ],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.image_outlined,
                    size: 60,
                    color: Colors.white.withOpacity(0.7),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    'NumNam Baby Food',
                    style: GoogleFonts.baloo2(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: Colors.white,
                    ),
                  ),
                  Text(
                    'Nutritious & Delicious',
                    style: GoogleFonts.poppins(
                      fontSize: 14,
                      color: Colors.white.withOpacity(0.9),
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

  Widget _buildSectionTitle(String title, IconData icon) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: kCoral.withOpacity(0.1),
            borderRadius: BorderRadius.circular(10),
          ),
          child: Icon(icon, color: kCoral, size: 20),
        ),
        const SizedBox(width: 12),
        Text(
          title,
          style: GoogleFonts.baloo2(
            fontSize: 22,
            fontWeight: FontWeight.w800,
            color: kNavy,
          ),
        ),
      ],
    );
  }

  Widget _buildCategoryCard(String label, IconData icon, Color color) {
    return GestureDetector(
      onTap: () {
        Navigator.of(context).push(
          MaterialPageRoute(
            builder: (_) => const ShopScreen(),
          ),
        );
      },
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 22, horizontal: 12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: color.withOpacity(0.2), width: 1.5),
          boxShadow: [
            BoxShadow(
              color: color.withOpacity(0.08),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: color.withOpacity(0.12),
                borderRadius: BorderRadius.circular(12),
              ),
              child: FaIcon(
                icon,
                size: 26,
                color: color,
              ),
            ),
            const SizedBox(height: 10),
            Text(
              label,
              textAlign: TextAlign.center,
              style: GoogleFonts.poppins(
                fontSize: 12,
                fontWeight: FontWeight.w600,
                color: kNavy,
                height: 1.3,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTrustBadge(IconData icon, String label, Color color) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: color.withOpacity(0.12),
            shape: BoxShape.circle,
          ),
          child: FaIcon(
            icon,
            size: 24,
            color: color,
          ),
        ),
        const SizedBox(height: 8),
        Text(
          label,
          style: GoogleFonts.poppins(
            fontSize: 12,
            fontWeight: FontWeight.w600,
            color: kNavy,
          ),
        ),
      ],
    );
  }

  Widget _buildModernProductCard(Product product) {
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
                  borderRadius:
                      const BorderRadius.vertical(top: Radius.circular(18)),
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
                        right: 8,
                        child: Container(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 8, vertical: 4),
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

  Widget _buildSubscriptionCard(PricingPlan plan) {
    return Container(
      width: 260,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: plan.isPopular
            ? LinearGradient(
                colors: [kCoral, kYellow],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              )
            : null,
        color: plan.isPopular ? null : Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: plan.isPopular ? Colors.transparent : const Color(0xFFFFD6E5),
          width: 2,
        ),
        boxShadow: plan.isPopular
            ? [
                BoxShadow(
                  color: kCoral.withOpacity(0.3),
                  blurRadius: 12,
                  offset: const Offset(0, 6),
                ),
              ]
            : null,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (plan.isPopular)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                'POPULAR',
                style: GoogleFonts.poppins(
                  fontSize: 10,
                  fontWeight: FontWeight.w800,
                  color: kCoral,
                ),
              ),
            ),
          const SizedBox(height: 12),
          Text(
            plan.name,
            style: GoogleFonts.baloo2(
              fontSize: 22,
              fontWeight: FontWeight.w800,
              color: plan.isPopular ? Colors.white : kNavy,
            ),
          ),
          const SizedBox(height: 4),
          Row(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(
                '₹${plan.price.toStringAsFixed(0)}',
                style: GoogleFonts.baloo2(
                  fontSize: 28,
                  fontWeight: FontWeight.w900,
                  color: plan.isPopular ? Colors.white : kCoral,
                ),
              ),
              Text(
                '/${plan.billingCycle ?? 'month'}',
                style: GoogleFonts.poppins(
                  fontSize: 12,
                  color: plan.isPopular
                      ? Colors.white.withOpacity(0.9)
                      : kNavy.withOpacity(0.6),
                ),
              ),
            ],
          ),
          const Spacer(),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: () {
                Navigator.of(context).push(
                  MaterialPageRoute(
                      builder: (_) => const SubscriptionsScreen()),
                );
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: plan.isPopular ? Colors.white : kCoral,
                foregroundColor: plan.isPopular ? kCoral : Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
                elevation: 0,
              ),
              child: Text(
                'Subscribe',
                style: GoogleFonts.poppins(
                  fontSize: 14,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCompactProductCard(Product product) {
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
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: Container(
                decoration: BoxDecoration(
                  color: kCream,
                  borderRadius:
                      const BorderRadius.vertical(top: Radius.circular(14)),
                ),
                child: Center(
                  child: product.imageUrl != null
                      ? CachedNetworkImage(
                          imageUrl: product.imageUrl!,
                          fit: BoxFit.cover,
                          width: double.infinity,
                          placeholder: (_, __) =>
                              const CircularProgressIndicator(
                            color: kCoral,
                            strokeWidth: 2,
                          ),
                          errorWidget: (_, __, ___) => Icon(
                            Icons.fastfood_rounded,
                            size: 36,
                            color: kNavy.withOpacity(0.3),
                          ),
                        )
                      : Icon(
                          Icons.fastfood_rounded,
                          size: 36,
                          color: kNavy.withOpacity(0.3),
                        ),
                ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(10),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    product.name,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: GoogleFonts.poppins(
                      fontSize: 11,
                      fontWeight: FontWeight.w600,
                      color: kNavy,
                      height: 1.2,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    '₹${product.effectivePrice.toStringAsFixed(0)}',
                    style: GoogleFonts.baloo2(
                      fontSize: 16,
                      fontWeight: FontWeight.w800,
                      color: kCoral,
                    ),
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
