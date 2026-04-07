import 'package:flutter/material.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.fromLTRB(16, 18, 16, 22),
      children: [
        Container(
          padding: const EdgeInsets.all(18),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(24),
            gradient: const LinearGradient(
              colors: [Color(0xFFFDE68A), Color(0xFFF59E0B)],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
            boxShadow: const [
              BoxShadow(
                color: Color(0x332D2A4A),
                blurRadius: 24,
                offset: Offset(0, 10),
              ),
            ],
          ),
          child: const Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Yummy Nutrition for Tiny Tummies',
                style: TextStyle(
                  color: Color(0xFF1F2937),
                  fontSize: 24,
                  fontWeight: FontWeight.w800,
                  height: 1.15,
                ),
              ),
              SizedBox(height: 8),
              Text(
                'App foundation is live. Next steps: auth, catalog, cart, checkout, and orders.',
                style: TextStyle(
                  color: Color(0xFF374151),
                  fontSize: 13,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

// ── Kids brand colours (mirrors app.dart) ────────────────────────────────
const _kCoral    = Color(0xFFFF6B8A);
const _kYellow   = Color(0xFFFFD93D);
const _kMint     = Color(0xFF4ECDC4);
const _kLavender = Color(0xFF9B8EC4);
const _kNavy     = Color(0xFF1A1A2E);

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: EdgeInsets.zero,
      children: const [
        _HeroBanner(),
        SizedBox(height: 24),
        _SectionHeader(title: 'Shop by Stage', emoji: '🎯'),
        SizedBox(height: 12),
        _StageCards(),
        SizedBox(height: 24),
        _SectionHeader(title: 'Why NumNam', emoji: '⭐'),
        SizedBox(height: 12),
        _TrustCards(),
        SizedBox(height: 24),
        _SectionHeader(title: 'Popular Picks', emoji: '🛒'),
        SizedBox(height: 12),
        _ComingSoonBanner(
          message: 'Products coming once connected to the live NumNam API!',
        ),
        SizedBox(height: 32),
      ],
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
            color: _kCoral.withOpacity(0.14),
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
              color: _kCoral.withOpacity(0.12),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: _kCoral.withOpacity(0.30)),
            ),
            child: Text(
              'Doctor-Founded  ·  Clean Label',
              style: GoogleFonts.poppins(
                fontSize: 10,
                fontWeight: FontWeight.w700,
                color: _kCoral,
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
              color: _kNavy,
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
                    backgroundColor: _kCoral,
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
                    foregroundColor: _kNavy,
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
              color: _kNavy,
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
    _Stage('Stage 1', '4-6 months', 'First Tastes', _kCoral,    Color(0xFFFFF0F5)),
    _Stage('Stage 2', '6-8 months', 'Exploring',    _kYellow,   Color(0xFFFFFBE6)),
    _Stage('Stage 3', '8-12 months','Textured',     _kMint,     Color(0xFFE8FCF8)),
    _Stage('Stage 4', '12+ months', 'Family Foods', _kLavender, Color(0xFFF5EFFF)),
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
            Text(stage.age,   style: GoogleFonts.poppins(fontSize: 10, fontWeight: FontWeight.w600, color: _kNavy)),
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
                Text(trust.title,    style: GoogleFonts.poppins(fontSize: 13, fontWeight: FontWeight.w700, color: _kNavy)),
                Text(trust.subtitle, style: GoogleFonts.poppins(fontSize: 11, color: const Color(0xFF6B6B8A))),
              ],
            ),
          ),
          Icon(Icons.chevron_right_rounded, color: _kCoral, size: 20),
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
