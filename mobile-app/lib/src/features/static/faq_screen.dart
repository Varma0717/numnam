import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/inner_page_nav.dart';

class FaqScreen extends StatelessWidget {
  static const routeName = '/faq';

  const FaqScreen({super.key});

  static const _faqs = <Map<String, String>>[
    {
      'q': 'What makes NumNam different from other baby food brands?',
      'a': 'NumNam was founded by two doctors — a cardio-thoracic surgeon and a cardiologist — who moved from Germany back to India and were shocked by the gap in infant nutrition. Our products are developed using European nutritional standards, are 100% natural, and contain 40%+ real vegetable content to train your baby\'s palate from the very first bite.',
    },
    {
      'q': 'What age group are NumNam products suitable for?',
      'a': 'Our purees are designed for babies aged 6 months and above, and our puffs are great for babies 8+ months who are ready for finger foods.',
    },
    {
      'q': 'Are NumNam products 100% natural?',
      'a': 'Yes! All our products are made with 100% natural ingredients — no artificial preservatives, flavors, or colors.',
    },
    {
      'q': 'Do you use any added sugar or salt in your products?',
      'a': 'No. NumNam products contain no added sugar or salt. The natural sweetness comes from real fruits. We keep sugar content as low as ~8g per 100g using only the natural sugars found in fruit.',
    },
    {
      'q': 'Are NumNam products safe for babies with allergies?',
      'a': 'Our products are free from common allergens like gluten, dairy, nuts, and soy. However, we always recommend checking the ingredient list and consulting your paediatrician if your baby has known allergies.',
    },
    {
      'q': 'Where are NumNam products made?',
      'a': 'NumNam products are made in India under strict quality standards inspired by European food safety regulations.',
    },
    {
      'q': 'How should I store NumNam purees?',
      'a': 'Store unopened pouches in a cool, dry place. Once opened, refrigerate and consume within 24 hours.',
    },
    {
      'q': 'Do you offer subscriptions or bundles?',
      'a': 'Yes! We offer flexible subscription plans where you can save up to 10% on recurring deliveries. Visit our Subscription page for more details.',
    },
    {
      'q': 'What is your return/refund policy?',
      'a': 'Due to the perishable nature of our products, we do not accept returns. However, if you receive a damaged or incorrect item, we\'ll replace or refund it — no questions asked. Just contact us within 48 hours of delivery.',
    },
    {
      'q': 'How can I contact NumNam?',
      'a': 'You can reach us via:\nEmail: customercare@numnam.com\nPhone: +91-9014252278\nOr use the contact form on our website.',
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('FAQ')),
      bottomNavigationBar: const InnerPageNav(),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _faqs.length,
        itemBuilder: (context, index) {
          final faq = _faqs[index];
          return Container(
            margin: const EdgeInsets.only(bottom: 8),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(14),
              border: Border.all(color: Colors.grey.shade200),
            ),
            child: Theme(
              data: Theme.of(context).copyWith(dividerColor: Colors.transparent),
              child: ExpansionTile(
                tilePadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                childrenPadding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                collapsedShape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                title: Text(
                  faq['q']!,
                  style: GoogleFonts.poppins(
                    fontSize: 14,
                    fontWeight: FontWeight.w600,
                    color: kNavy,
                  ),
                ),
                iconColor: kCoral,
                collapsedIconColor: Colors.grey.shade500,
                children: [
                  Text(
                    faq['a']!,
                    style: GoogleFonts.poppins(
                      fontSize: 13,
                      height: 1.6,
                      color: Colors.grey.shade700,
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
