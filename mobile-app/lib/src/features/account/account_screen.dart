import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/auth_provider.dart';
import '../../shared/theme/colors.dart';
import '../auth/auth_gate.dart';
import '../orders/orders_screen.dart';
import '../wishlist/wishlist_screen.dart';
import 'edit_profile_screen.dart';
import 'contact_form_screen.dart';
import '../static/about_screen.dart';
import '../static/faq_screen.dart';
import '../static/static_page_screen.dart';

class AccountScreen extends StatelessWidget {
  const AccountScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return AuthGate(
      child: Consumer<AuthProvider>(
        builder: (ctx, auth, _) {
          final user = auth.user;
          return ListView(
            padding: const EdgeInsets.all(20),
            children: [
              const SizedBox(height: 12),
              Center(
                child: CircleAvatar(
                  radius: 40,
                  backgroundColor: const Color(0xFFFFF0F5),
                  backgroundImage: user?.avatar != null
                      ? CachedNetworkImageProvider(user!.avatar!)
                      : null,
                  child: user?.avatar == null
                      ? Text(
                          (user?.name ?? 'U').substring(0, 1).toUpperCase(),
                          style: GoogleFonts.baloo2(
                              fontSize: 32,
                              fontWeight: FontWeight.w900,
                              color: kCoral),
                        )
                      : null,
                ),
              ),
              const SizedBox(height: 12),
              Center(
                child: Text(user?.name ?? 'User',
                    style: GoogleFonts.baloo2(
                        fontSize: 22,
                        fontWeight: FontWeight.w800,
                        color: kNavy)),
              ),
              Center(
                child: Text(user?.email ?? '',
                    style: GoogleFonts.poppins(
                        fontSize: 13, color: const Color(0xFF6B6B8A))),
              ),
              if (user?.phone != null && user!.phone!.isNotEmpty)
                Center(
                  child: Padding(
                    padding: const EdgeInsets.only(top: 4),
                    child: Text(user.phone!,
                        style: GoogleFonts.poppins(
                            fontSize: 12, color: const Color(0xFF6B6B8A))),
                  ),
                ),
              if (user?.referralCode != null && user!.referralCode!.isNotEmpty)
                Center(
                  child: Container(
                    margin: const EdgeInsets.only(top: 8),
                    padding:
                        const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                    decoration: BoxDecoration(
                      color: kMint.withOpacity(0.12),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text('Referral: ${user.referralCode}',
                        style: GoogleFonts.poppins(
                            fontSize: 11,
                            fontWeight: FontWeight.w600,
                            color: kMint)),
                  ),
                ),
              const SizedBox(height: 28),

              _tile(context, Icons.person_outline_rounded, 'Edit Profile', () {
                Navigator.of(context).pushNamed(EditProfileScreen.routeName);
              }),
              _tile(context, Icons.receipt_long_rounded, 'My Orders', () {
                Navigator.of(context).pushNamed(OrdersScreen.routeName);
              }),
              _tile(context, Icons.favorite_border_rounded, 'Wishlist', () {
                Navigator.of(context).pushNamed(WishlistScreen.routeName);
              }),
              _tile(context, Icons.mail_outline_rounded, 'Contact Us', () {
                Navigator.of(context).pushNamed(ContactFormScreen.routeName);
              }),

              const Divider(height: 32),

              _tile(context, Icons.info_outline_rounded, 'About NumNam', () {
                Navigator.of(context).pushNamed(AboutScreen.routeName);
              }),
              _tile(context, Icons.help_outline_rounded, 'FAQ', () {
                Navigator.of(context).pushNamed(FaqScreen.routeName);
              }),
              _tile(context, Icons.description_outlined, 'Terms & Conditions', () {
                Navigator.of(context).push(MaterialPageRoute(
                  builder: (_) => StaticPageScreen(
                    title: StaticPages.termsTitle,
                    sections: StaticPages.termsSections,
                  ),
                ));
              }),
              _tile(context, Icons.privacy_tip_outlined, 'Privacy Policy', () {
                Navigator.of(context).push(MaterialPageRoute(
                  builder: (_) => StaticPageScreen(
                    title: StaticPages.privacyTitle,
                    sections: StaticPages.privacySections,
                  ),
                ));
              }),
              _tile(context, Icons.local_shipping_outlined, 'Shipping Policy', () {
                Navigator.of(context).push(MaterialPageRoute(
                  builder: (_) => StaticPageScreen(
                    title: StaticPages.shippingTitle,
                    sections: StaticPages.shippingSections,
                  ),
                ));
              }),
              _tile(context, Icons.assignment_return_outlined, 'Refund Policy', () {
                Navigator.of(context).push(MaterialPageRoute(
                  builder: (_) => StaticPageScreen(
                    title: StaticPages.refundTitle,
                    sections: StaticPages.refundSections,
                  ),
                ));
              }),

              const SizedBox(height: 32),
              SizedBox(
                height: 48,
                child: OutlinedButton.icon(
                  onPressed: () async {
                    await auth.logout();
                  },
                  icon: const Icon(Icons.logout_rounded),
                  label: const Text('Logout'),
                  style: OutlinedButton.styleFrom(
                    foregroundColor: kCoral,
                    side: const BorderSide(color: kCoral),
                  ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }

  Widget _tile(
      BuildContext context, IconData icon, String label, VoidCallback onTap) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Container(
          width: 40,
          height: 40,
          decoration: BoxDecoration(
            color: const Color(0xFFFFF0F5),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(icon, color: kCoral, size: 20),
        ),
        title: Text(label,
            style: GoogleFonts.poppins(
                fontSize: 14, fontWeight: FontWeight.w500, color: kNavy)),
        trailing:
            const Icon(Icons.chevron_right_rounded, color: Color(0xFFCCC)),
        shape:
            RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        tileColor: Colors.white,
        onTap: onTap,
      ),
    );
  }
}
