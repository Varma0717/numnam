import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../shared/theme/colors.dart';

class AboutScreen extends StatelessWidget {
  static const routeName = '/about';

  const AboutScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('About NumNam')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // Hero
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: kCoral.withValues(alpha: 0.08),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: kCoral.withValues(alpha: 0.2)),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    'OUR STORY',
                    style: GoogleFonts.poppins(
                      fontSize: 10,
                      fontWeight: FontWeight.w700,
                      letterSpacing: 1.2,
                      color: kCoral,
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  'From Scrubs to Saucepan:\nOur Journey Home.',
                  style: GoogleFonts.poppins(
                    fontSize: 22,
                    fontWeight: FontWeight.w800,
                    color: kNavy,
                    height: 1.3,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'How a 12-month parental leave and a trip across continents changed the way we think about feeding our children.',
                  style: GoogleFonts.poppins(
                    fontSize: 13,
                    color: Colors.grey.shade700,
                    height: 1.5,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // Story timeline
          _StoryCard(
            title: '2021: The Turning Point',
            text: 'After years of focusing on medical residency and career progression in Germany, our son Kian was born. In Germany, the system allows for a 12-month parental leave — a precious window of time we decided to use to reconnect with our roots in India.',
          ),
          _StoryCard(
            title: 'The Culture Shock',
            text: 'In Germany, we were used to the "privilege of convenience." We could walk into any local supermarket and find diverse, vegetable-rich, and scientifically balanced baby food. Returning to India was a wake-up call — we struggled to find products that met the nutritional standards we practiced as doctors.',
          ),
          _StoryCard(
            title: 'The Home-Kitchen Solution',
            text: 'For months, we went back to basics — cooking every meal from scratch to ensure Kian got the nutrients he needed. But we realized that most busy Indian parents don\'t have that luxury of time.',
          ),
          _StoryCard(
            title: 'The Birth of NumNam',
            text: 'The idea hit us: Why should healthy, high-quality baby food be a privilege found only in European supermarkets? We decided to bring that same rigor, science, and convenience to fellow parents in India.',
          ),
          const SizedBox(height: 20),

          // Pillars
          Text(
            'What Sets Us Apart',
            style: GoogleFonts.poppins(
              fontSize: 18,
              fontWeight: FontWeight.w700,
              color: kNavy,
            ),
          ),
          const SizedBox(height: 12),
          _PillarTile(
            icon: Icons.shield_outlined,
            title: 'European Standards',
            text: 'Developed in Germany by doctor-parents, our recipes meet the world\'s strictest safety and nutritional guidelines for infants.',
          ),
          _PillarTile(
            icon: Icons.eco_outlined,
            title: 'Palate Training',
            text: 'Early exposure to savory flavors is the "secret weapon" against picky eating, helping your child love veggies for life.',
          ),
          _PillarTile(
            icon: Icons.favorite_outline,
            title: '40%+ Real Vegetables',
            text: 'We prioritize greens over sugar. With only 8g of natural fruit sugars per 100g, we keep sweetness gentle and nutrition high.',
          ),
          const SizedBox(height: 20),

          // Founders
          Text(
            'Meet The Founders',
            style: GoogleFonts.poppins(
              fontSize: 18,
              fontWeight: FontWeight.w700,
              color: kNavy,
            ),
          ),
          const SizedBox(height: 12),
          _FounderCard(
            initials: 'SR',
            name: 'Dr. Donuru, Srinath Reddy',
            role: 'Managing Director & Research Lead',
            bio: 'Cardio-thoracic Surgeon (Germany). Clinical Research Graduate (Harvard Medical School). Bringing German medical precision to infant nutrition.',
          ),
          _FounderCard(
            initials: 'MK',
            name: 'Dr. Kodeboina, Monika',
            role: 'Co-Founder & Head of Recipes',
            bio: 'Cardiologist (Germany). MBA (Frankfurt Business School). MSc: Lifestyle Medicine (Europe). The bridge between European standards and Indian palates.',
          ),
          _FounderCard(
            initials: 'K',
            name: 'Kian',
            role: 'CIO: Chief Inspiration Officer',
            bio: 'The little one who started it all.',
          ),
          _FounderCard(
            initials: 'S',
            name: 'Smiti',
            role: 'CHH: Chief of Happy Hearts',
            bio: 'Spreading joy in every pouch.',
          ),
          const SizedBox(height: 20),

          // Quote
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: kCoral.withValues(alpha: 0.06),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: kCoral.withValues(alpha: 0.15)),
            ),
            child: Column(
              children: [
                Icon(Icons.format_quote, color: kCoral, size: 32),
                const SizedBox(height: 8),
                Text(
                  '"As doctors, we see the results of poor nutrition later in life. As parents, we want to prevent it from the first bite."',
                  textAlign: TextAlign.center,
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    fontStyle: FontStyle.italic,
                    color: kNavy,
                    height: 1.5,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  '— Srinath & Monika',
                  style: GoogleFonts.poppins(
                    fontSize: 12,
                    color: Colors.grey.shade600,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 24),
        ],
      ),
    );
  }
}

class _StoryCard extends StatelessWidget {
  final String title;
  final String text;
  const _StoryCard({required this.title, required this.text});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.shade200),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: GoogleFonts.poppins(fontSize: 15, fontWeight: FontWeight.w600, color: kNavy)),
          const SizedBox(height: 6),
          Text(text, style: GoogleFonts.poppins(fontSize: 13, height: 1.6, color: Colors.grey.shade700)),
        ],
      ),
    );
  }
}

class _PillarTile extends StatelessWidget {
  final IconData icon;
  final String title;
  final String text;
  const _PillarTile({required this.icon, required this.title, required this.text});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: Colors.grey.shade200),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: kCoral.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(icon, color: kCoral, size: 20),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: GoogleFonts.poppins(fontSize: 14, fontWeight: FontWeight.w600, color: kNavy)),
                const SizedBox(height: 4),
                Text(text, style: GoogleFonts.poppins(fontSize: 12, height: 1.5, color: Colors.grey.shade600)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _FounderCard extends StatelessWidget {
  final String initials;
  final String name;
  final String role;
  final String bio;
  const _FounderCard({required this.initials, required this.name, required this.role, required this.bio});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: Colors.grey.shade200),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CircleAvatar(
            radius: 22,
            backgroundColor: kCoral,
            child: Text(initials, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14)),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(name, style: GoogleFonts.poppins(fontSize: 14, fontWeight: FontWeight.w600, color: kNavy)),
                Text(role, style: GoogleFonts.poppins(fontSize: 11, fontWeight: FontWeight.w500, color: kCoral)),
                const SizedBox(height: 4),
                Text(bio, style: GoogleFonts.poppins(fontSize: 12, height: 1.4, color: Colors.grey.shade600)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
