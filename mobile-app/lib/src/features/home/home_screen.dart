import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/product.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/product_card.dart';
import '../shop/product_detail_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  List<Product> _featured = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get(ApiEndpoints.products, queryParameters: {
        'per_page': 6,
        'featured': 1,
      });
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
          _featured =
              list.map((e) => Product.fromJson(e as Map<String, dynamic>)).toList();
          _loading = false;
        });
      }
    } catch (_) {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      color: kCoral,
      onRefresh: _load,
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          const _HeroBanner(),
          const SizedBox(height: 24),
          const _SectionHeader(title: 'Shop by Stage', emoji: '🎯'),
          const SizedBox(height: 12),
          const _StageCards(),
          const SizedBox(height: 24),
          const _SectionHeader(title: 'Why NumNam', emoji: '⭐'),
          const SizedBox(height: 12),
          const _TrustCards(),
          const SizedBox(height: 24),
          const _SectionHeader(title: 'Popular Picks', emoji: '🛒'),
          const SizedBox(height: 12),
          if (_loading)
            const Padding(
              padding: EdgeInsets.all(32),
              child: Center(child: CircularProgressIndicator(color: kCoral)),
            )
          else if (_featured.isEmpty)
            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 16),
              child: _ComingSoonBanner(
                message: 'No featured products yet — check back soon!',
              ),
            )
          else
            SizedBox(
              height: 220,
              child: ListView.separated(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                scrollDirection: Axis.horizontal,
                itemCount: _featured.length,
                separatorBuilder: (_, __) => const SizedBox(width: 12),
                itemBuilder: (_, i) => SizedBox(
                  width: 160,
                  child: ProductCard(
                    product: _featured[i],
                    onTap: () => Navigator.of(context).pushNamed(
                      ProductDetailScreen.routeName,
                      arguments: _featured[i].slug,
                    ),
                  ),
                ),
              ),
            ),
          const SizedBox(height: 32),
        ],
      ),
    );
  }
}

// ── Hero banner ──────────────────────────────────────────────────────────
class _HeroBanner extends StatelessWidget {
  const _HeroBanner();

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      padding: const EdgeInsets.fromLTRB(20, 24, 20, 24),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(28),
        gradient: const LinearGradient(
          colors: [Color(0xFFFFF0F5), Color(0xFFFFFBE6), Color(0xFFE8FCF8)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        border: Border.all(color: const Color(0xFFFFD6E5), width: 2),
        boxShadow: [
          BoxShadow(
            color: kCoral.withOpacity(0.14),
            blurRadius: 28,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Tag pill
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
            decoration: BoxDecoration(
              color: kCoral.withOpacity(0.12),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: kCoral.withOpacity(0.30)),
            ),
            child: Text(
              'Doctor-Founded  ·  Clean Label',
              style: GoogleFonts.poppins(
                fontSize: 10,
                fontWeight: FontWeight.w700,
                color: kCoral,
                letterSpacing: 0.8,
              ),
            ),
          ),
          const SizedBox(height: 14),
          Text(
            'Yummy Nutrition\nfor Tiny Tummies 🍼',
            style: GoogleFonts.baloo2(
              fontSize: 26,
              fontWeight: FontWeight.w900,
              color: kNavy,
              height: 1.15,
            ),
          ),
          const SizedBox(height: 10),
          Text(
            'Stage-wise baby foods with transparent ingredients — built by paediatricians for busy parents.',
            style: GoogleFonts.poppins(
              fontSize: 13,
              fontWeight: FontWeight.w500,
              color: const Color(0xFF4A4A6A),
              height: 1.5,
            ),
          ),
          const SizedBox(height: 18),
          Row(
            children: [
              Expanded(
                child: ElevatedButton(
                  onPressed: () {},
                  style: ElevatedButton.styleFrom(
                    backgroundColor: kCoral,
                    foregroundColor: Colors.white,
                    textStyle: GoogleFonts.poppins(fontWeight: FontWeight.w700, fontSize: 13),
                    shape: const StadiumBorder(),
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    elevation: 0,
                  ),
                  child: const Text('Shop Now'),
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: OutlinedButton(
                  onPressed: () {},
                  style: OutlinedButton.styleFrom(
                    foregroundColor: kNavy,
                    side: const BorderSide(color: Color(0xFFFFD6E5), width: 2),
                    textStyle: GoogleFonts.poppins(fontWeight: FontWeight.w700, fontSize: 13),
                    shape: const StadiumBorder(),
                    padding: const EdgeInsets.symmetric(vertical: 14),
                  ),
                  child: const Text('Subscribe'),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

// ── Section header ───────────────────────────────────────────────────────
class _SectionHeader extends StatelessWidget {
  const _SectionHeader({required this.title, required this.emoji});
  final String title;
  final String emoji;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        children: [
          Text(emoji, style: const TextStyle(fontSize: 20)),
          const SizedBox(width: 8),
          Text(
            title,
            style: GoogleFonts.baloo2(
              fontSize: 20,
              fontWeight: FontWeight.w800,
              color: kNavy,
            ),
          ),
        ],
      ),
    );
  }
}

// ── Stage cards ──────────────────────────────────────────────────────────
class _StageCards extends StatelessWidget {
  const _StageCards();

  static const _stages = [
    _Stage('Stage 1', '4-6 months', 'First Tastes', kCoral,    Color(0xFFFFF0F5)),
    _Stage('Stage 2', '6-8 months', 'Exploring',    kYellow,   Color(0xFFFFFBE6)),
    _Stage('Stage 3', '8-12 months','Textured',     kMint,     Color(0xFFE8FCF8)),
    _Stage('Stage 4', '12+ months', 'Family Foods', kLavender, Color(0xFFF5EFFF)),
  ];

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 130,
      child: ListView.separated(
        padding: const EdgeInsets.symmetric(horizontal: 16),
        scrollDirection: Axis.horizontal,
        itemCount: _stages.length,
        separatorBuilder: (_, __) => const SizedBox(width: 12),
        itemBuilder: (context, i) => _StageCard(stage: _stages[i]),
      ),
    );
  }
}

class _Stage {
  const _Stage(this.stage, this.age, this.label, this.colour, this.bg);
  final String stage, age, label;
  final Color colour, bg;
}

class _StageCard extends StatelessWidget {
  const _StageCard({required this.stage});
  final _Stage stage;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {},
      child: Container(
        width: 120,
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: stage.bg,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: stage.colour.withOpacity(0.35), width: 2),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(stage.stage, style: GoogleFonts.baloo2(fontSize: 15, fontWeight: FontWeight.w800, color: stage.colour)),
            const SizedBox(height: 4),
            Text(stage.age,   style: GoogleFonts.poppins(fontSize: 10, fontWeight: FontWeight.w600, color: kNavy)),
            const SizedBox(height: 6),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: stage.colour.withOpacity(0.15),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text(stage.label, style: GoogleFonts.poppins(fontSize: 9, fontWeight: FontWeight.w700, color: stage.colour)),
            ),
          ],
        ),
      ),
    );
  }
}

// ── Trust cards ──────────────────────────────────────────────────────────
class _TrustCards extends StatelessWidget {
  const _TrustCards();

  static const _items = [
    _Trust('🩺', 'Doctor-Founded',    'Backed by European Nutrition research'),
    _Trust('🥦', 'Vegetable Forward', 'Rich in veggies, naturally sweet'),
    _Trust('🚫', 'No Added Sugar',    'Clean ingredients only'),
    _Trust('✅', 'No Preservatives',  'Totally clean label'),
  ];

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Column(
        children: _items.map((t) => _TrustRow(trust: t)).toList(),
      ),
    );
  }
}

class _Trust {
  const _Trust(this.emoji, this.title, this.subtitle);
  final String emoji, title, subtitle;
}

class _TrustRow extends StatelessWidget {
  const _TrustRow({required this.trust});
  final _Trust trust;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
      ),
      child: Row(
        children: [
          Text(trust.emoji, style: const TextStyle(fontSize: 22)),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(trust.title,    style: GoogleFonts.poppins(fontSize: 13, fontWeight: FontWeight.w700, color: kNavy)),
                Text(trust.subtitle, style: GoogleFonts.poppins(fontSize: 11, color: const Color(0xFF6B6B8A))),
              ],
            ),
          ),
          Icon(Icons.chevron_right_rounded, color: kCoral, size: 20),
        ],
      ),
    );
  }
}

// ── Coming soon banner ───────────────────────────────────────────────────
class _ComingSoonBanner extends StatelessWidget {
  const _ComingSoonBanner({required this.message});
  final String message;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: const Color(0xFFFFFBE6),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFFFD93D), width: 2),
      ),
      child: Row(
        children: [
          const Text('🚀', style: TextStyle(fontSize: 28)),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              message,
              style: GoogleFonts.poppins(
                fontSize: 12.5,
                fontWeight: FontWeight.w500,
                color: const Color(0xFF4A4A00),
                height: 1.5,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
