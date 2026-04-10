import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/inner_page_nav.dart';

class StaticPageScreen extends StatelessWidget {
  static const routeName = '/static-page';

  final String title;
  final List<Map<String, String>> sections;

  const StaticPageScreen({
    super.key,
    required this.title,
    required this.sections,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(title)),
      bottomNavigationBar: const InnerPageNav(),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: sections.length,
        itemBuilder: (context, index) {
          final section = sections[index];
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
                Text(
                  section['heading'] ?? '',
                  style: GoogleFonts.poppins(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                    color: kNavy,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  section['text'] ?? '',
                  style: GoogleFonts.poppins(
                    fontSize: 13,
                    height: 1.6,
                    color: Colors.grey.shade700,
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}

/// Pre-built static page data for all policies
class StaticPages {
  static const termsTitle = 'Terms & Conditions';
  static const termsSections = [
    {'heading': '1. Use of Website', 'text': 'NumNam grants you a limited, non-exclusive, and revocable license to use this website for personal and non-commercial purposes. You must not use this site for any unlawful or fraudulent activity, to interfere with or damage the website, to upload viruses or spam, or to scrape data without written consent.'},
    {'heading': '2. Intellectual Property', 'text': 'Unless otherwise stated, all content on this website — including text, images, logos, and product descriptions — is owned by or licensed to NumNam (Kikudu Corp). All rights are reserved. You may not reuse, republish, or distribute content without written permission.'},
    {'heading': '3. User Accounts', 'text': 'If you create an account, you are responsible for maintaining the confidentiality of your login information. You agree to notify us immediately of any unauthorized use. We reserve the right to suspend or terminate accounts at our discretion.'},
    {'heading': '4. Product Orders & Payments', 'text': 'All products listed remain the property of NumNam until full payment is received. Orders are processed via third-party payment gateways. Prices, availability, and specifications are subject to change without notice.'},
    {'heading': '5. User Submissions', 'text': 'Any content submitted by users (such as reviews or feedback) may be used by NumNam for promotional or informational purposes. You confirm that your submission is original and does not violate the rights of others.'},
    {'heading': '6. No Warranties', 'text': 'This website is provided on an "as is" and "as available" basis. NumNam does not guarantee that the website will be error-free or uninterrupted.'},
    {'heading': '7. Limitation of Liability', 'text': 'To the fullest extent permitted by law, NumNam shall not be liable for any indirect, incidental, or consequential damages arising out of your use of the website or products.'},
    {'heading': '8. Indemnity', 'text': 'You agree to indemnify and hold harmless NumNam, its parent company Kikudu Corp, employees, and affiliates from any claims arising from your breach of these Terms.'},
    {'heading': '9. Governing Law', 'text': 'These Terms are governed by the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts located in Hyderabad, India.'},
    {'heading': '10. Changes to Terms', 'text': 'NumNam reserves the right to revise these Terms at any time. Continued use of the website following changes constitutes acceptance.'},
    {'heading': '11. Contact', 'text': 'For any questions: NumNam (Kikudu Corp) — Email: customercare@numnam.com — Website: www.numnam.com'},
  ];

  static const privacyTitle = 'Privacy Policy';
  static const privacySections = [
    {'heading': 'Scope & Consent', 'text': 'This Privacy Policy applies to all users. By visiting or using our site, you consent to the collection, use, and disclosure of your personal information. Effective Date: 01.04.2025.'},
    {'heading': 'Information We Collect', 'text': 'We collect: name, email, phone number, shipping & billing addresses, order and transaction details, communications via email/forms/social media, and site usage data via cookies and analytics.'},
    {'heading': 'How We Use Your Information', 'text': 'To process and deliver orders, send order confirmations, respond to service requests, send promotional messages (if opted in), improve website functionality, and prevent fraud.'},
    {'heading': 'Data Security', 'text': 'We take reasonable technical and organizational precautions to prevent unauthorized access. Your information is stored on secure servers. No digital platform is completely secure.'},
    {'heading': 'Cookies & Tracking', 'text': 'We use cookies to track activity and preferences, enhance browsing, and analyze user behavior. You can manage cookie settings through your browser.'},
    {'heading': 'Sharing of Information', 'text': 'We do not sell or rent your personal data. We may share with trusted third parties for payments, deliveries, legal compliance, or business transfers.'},
    {'heading': 'Communication', 'text': 'You may receive order emails, product updates (if subscribed), and service announcements. You may opt-out of promotional communications at any time.'},
    {'heading': 'Contact Us', 'text': 'NumNam (Kikudu Corp) — Email: customercare@numnam.com — Website: www.numnam.com'},
  ];

  static const shippingTitle = 'Shipping Policy';
  static const shippingSections = [
    {'heading': 'Shipping Destination', 'text': 'We currently ship across India — from big cities to tiny towns. International shipping is not available yet.'},
    {'heading': 'Order Processing Time', 'text': 'Orders are processed within 1–2 business days (excluding weekends and public holidays).'},
    {'heading': 'Delivery Estimates', 'text': 'Metro Cities: 3–5 business days. Tier 2 & 3 Cities: 5–7 business days. Remote Locations: 7–10 business days.'},
    {'heading': 'Shipping Charges', 'text': 'Orders above ₹499: Free Shipping! Orders ₹499 & below: Standard shipping fee of ₹85 applies.'},
    {'heading': 'Order Tracking', 'text': 'Once shipped, you\'ll receive an email with tracking details to follow your order\'s journey.'},
    {'heading': 'Shipping Partners', 'text': 'We use Shiprocket, which works with India\'s most trusted courier services.'},
    {'heading': 'Damaged or Missing Items', 'text': 'If your order arrives damaged, email us within 24 hours with photos. If anything is missing, report within 48 hours.'},
    {'heading': 'Contact Us', 'text': 'Email: customercare@numnam.com — Phone: +91-9014252278'},
  ];

  static const refundTitle = 'Refund Policy';
  static const refundSections = [
    {'heading': 'Order Cancellation by Customer', 'text': 'Orders can be cancelled within 12 hours of placing the order or before shipment, whichever comes first. Once dispatched, it cannot be cancelled. Email customercare@numnam.com with your Order ID.'},
    {'heading': 'Order Cancellation by NumNam', 'text': 'We may cancel orders due to out-of-stock items, logistical limitations, or operational issues. Full refund within 7–10 business days.'},
    {'heading': 'Non-Cancellable Orders', 'text': 'Ready-to-Eat or pureed food pouches and customized or bulk orders cannot be cancelled once processed or dispatched.'},
    {'heading': 'Refund Processing', 'text': 'If cancelled before shipment, a full refund will be processed within 7–10 business days.'},
    {'heading': 'Need Help?', 'text': 'Email: customercare@numnam.com — Phone: +91-9014252278'},
  ];
}
