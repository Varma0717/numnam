import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';

import 'core/api_client.dart';
import 'core/auth_provider.dart';
import 'core/storage_service.dart';
import 'features/cart/cart_provider.dart';
import 'features/home/home_screen.dart';
import 'features/shop/shop_screen.dart';
import 'features/shop/product_detail_screen.dart';
import 'features/cart/cart_screen.dart';
import 'features/checkout/checkout_screen.dart';
import 'features/checkout/order_success_screen.dart';
import 'features/orders/orders_screen.dart';
import 'features/orders/order_detail_screen.dart';
import 'features/wishlist/wishlist_screen.dart';
import 'features/account/account_screen.dart';
import 'features/account/edit_profile_screen.dart';
import 'features/account/contact_form_screen.dart';
import 'features/auth/login_screen.dart';
import 'features/auth/register_screen.dart';
import 'features/subscriptions/subscriptions_screen.dart';
import 'features/blog/blog_list_screen.dart';
import 'features/blog/blog_detail_screen.dart';
import 'features/static/about_screen.dart';
import 'features/static/faq_screen.dart';
import 'shared/theme/colors.dart';

class NumNamApp extends StatelessWidget {
  const NumNamApp({super.key});

  @override
  Widget build(BuildContext context) {
    final storage = StorageService();
    final apiClient = ApiClient(storage: storage);

    return MultiProvider(
      providers: [
        Provider<StorageService>.value(value: storage),
        Provider<ApiClient>.value(value: apiClient),
        ChangeNotifierProvider<AuthProvider>(
          create: (_) => AuthProvider(apiClient, storage)..loadStoredAuth(),
        ),
        ChangeNotifierProvider<CartProvider>(
          create: (_) => CartProvider(apiClient),
        ),
      ],
      child: MaterialApp(
        title: 'NumNam',
        debugShowCheckedModeBanner: false,
        theme: _buildTheme(),
        home: Consumer<AuthProvider>(
          builder: (context, auth, _) {
            if (auth.isLoading) {
              return const Scaffold(
                backgroundColor: Colors.white,
                body: Center(child: CircularProgressIndicator()),
              );
            }
            if (auth.isAuthenticated) {
              return _Shell(key: _Shell.shellKey);
            }
            return const _AuthFlow();
          },
        ),
        routes: {
          LoginScreen.routeName: (_) => const LoginScreen(),
          RegisterScreen.routeName: (_) => const RegisterScreen(),
          ProductDetailScreen.routeName: (_) => const ProductDetailScreen(),
          CheckoutScreen.routeName: (_) => const CheckoutScreen(),
          OrderSuccessScreen.routeName: (_) => const OrderSuccessScreen(),
          OrdersScreen.routeName: (_) => const OrdersScreen(),
          OrderDetailScreen.routeName: (_) => const OrderDetailScreen(),
          WishlistScreen.routeName: (_) => const WishlistScreen(),
          EditProfileScreen.routeName: (_) => const EditProfileScreen(),
          ContactFormScreen.routeName: (_) => const ContactFormScreen(),
          SubscriptionsScreen.routeName: (_) => const SubscriptionsScreen(),
          BlogListScreen.routeName: (_) => const BlogListScreen(),
          BlogDetailScreen.routeName: (_) => const BlogDetailScreen(),
          AboutScreen.routeName: (_) => const AboutScreen(),
          FaqScreen.routeName: (_) => const FaqScreen(),
        },
      ),
    );
  }

  ThemeData _buildTheme() {
    final base = GoogleFonts.baloo2TextTheme();
    final bodyBase = GoogleFonts.poppinsTextTheme();

    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme(
        brightness: Brightness.light,
        primary: kCoral,
        onPrimary: Colors.white,
        primaryContainer: const Color(0xFFFFF0F5),
        onPrimaryContainer: const Color(0xFFDD3259),
        secondary: kYellow,
        onSecondary: kNavy,
        secondaryContainer: const Color(0xFFFFFBE6),
        onSecondaryContainer: const Color(0xFF8B6800),
        tertiary: kMint,
        onTertiary: Colors.white,
        tertiaryContainer: const Color(0xFFE8FCF8),
        onTertiaryContainer: const Color(0xFF00706A),
        error: const Color(0xFFEF4444),
        onError: Colors.white,
        errorContainer: const Color(0xFFFFEDED),
        onErrorContainer: const Color(0xFFB91C1C),
        surface: kCream,
        onSurface: kNavy,
        surfaceContainerHighest: const Color(0xFFFFF5F8),
        outline: const Color(0xFFFFD6E5),
      ),
      scaffoldBackgroundColor: kCream,
      textTheme: base.copyWith(
        displayLarge: base.displayLarge?.copyWith(color: kNavy, fontWeight: FontWeight.w900),
        displayMedium: base.displayMedium?.copyWith(color: kNavy, fontWeight: FontWeight.w800),
        displaySmall: base.displaySmall?.copyWith(color: kNavy, fontWeight: FontWeight.w800),
        headlineLarge: base.headlineLarge?.copyWith(color: kNavy, fontWeight: FontWeight.w800),
        headlineMedium: base.headlineMedium?.copyWith(color: kNavy, fontWeight: FontWeight.w700),
        headlineSmall: base.headlineSmall?.copyWith(color: kNavy, fontWeight: FontWeight.w700),
        titleLarge: base.titleLarge?.copyWith(color: kNavy, fontWeight: FontWeight.w700),
        titleMedium: bodyBase.titleMedium?.copyWith(color: kNavy, fontWeight: FontWeight.w600),
        bodyLarge: bodyBase.bodyLarge?.copyWith(color: kNavy),
        bodyMedium: bodyBase.bodyMedium?.copyWith(color: const Color(0xFF4A4A6A)),
        bodySmall: bodyBase.bodySmall?.copyWith(color: const Color(0xFF6B6B8A)),
      ),
      appBarTheme: AppBarTheme(
        backgroundColor: Colors.white,
        foregroundColor: kNavy,
        elevation: 0,
        scrolledUnderElevation: 2,
        shadowColor: kCoral.withOpacity(0.12),
        titleTextStyle: GoogleFonts.baloo2(fontSize: 22, fontWeight: FontWeight.w800, color: kNavy),
        systemOverlayStyle: SystemUiOverlayStyle.dark,
      ),
      cardTheme: CardThemeData(
        color: Colors.white,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
          side: const BorderSide(color: Color(0xFFFFD6E5), width: 2),
        ),
      ),
      navigationBarTheme: NavigationBarThemeData(
        backgroundColor: Colors.white,
        indicatorColor: kCoral.withOpacity(0.18),
        iconTheme: WidgetStateProperty.resolveWith((states) {
          if (states.contains(WidgetState.selected)) {
            return const IconThemeData(color: kCoral, size: 26);
          }
          return const IconThemeData(color: Color(0xFF9E9EBE), size: 24);
        }),
        labelTextStyle: WidgetStateProperty.resolveWith((states) {
          final style = GoogleFonts.poppins(fontSize: 11, fontWeight: FontWeight.w700);
          if (states.contains(WidgetState.selected)) {
            return style.copyWith(color: kCoral);
          }
          return style.copyWith(color: const Color(0xFF9E9EBE));
        }),
      ),
      filledButtonTheme: FilledButtonThemeData(
        style: FilledButton.styleFrom(
          backgroundColor: kCoral,
          foregroundColor: Colors.white,
          textStyle: GoogleFonts.poppins(fontWeight: FontWeight.w700),
          shape: const StadiumBorder(),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: kYellow,
          foregroundColor: kNavy,
          textStyle: GoogleFonts.poppins(fontWeight: FontWeight.w700),
          shape: const StadiumBorder(),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          elevation: 0,
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: const Color(0xFFFFF5F8),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFFFD6E5)),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: Color(0xFFFFD6E5)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(16),
          borderSide: const BorderSide(color: kCoral, width: 2),
        ),
      ),
    );
  }
}

// ── Public helper to switch Shell tabs from inner pages ───────────────
void switchToShellTab(BuildContext context, int index) {
  _Shell.switchTab(context, index);
}

// ── Auth flow (login ↔ register toggle without Navigator) ─────────────
class _AuthFlow extends StatefulWidget {
  const _AuthFlow();

  @override
  State<_AuthFlow> createState() => _AuthFlowState();
}

class _AuthFlowState extends State<_AuthFlow> {
  bool _showLogin = true;

  void _toggle() {
    context.read<AuthProvider>().clearError();
    setState(() => _showLogin = !_showLogin);
  }

  @override
  Widget build(BuildContext context) {
    return _showLogin
        ? LoginScreen(onToggle: _toggle)
        : RegisterScreen(onToggle: _toggle);
  }
}

// ── Main shell with persistent bottom nav ─────────────────────────────
class _Shell extends StatefulWidget {
  const _Shell({super.key});

  /// Global key to access Shell state for tab switching from inner pages.
  static final GlobalKey<_ShellState> shellKey = GlobalKey<_ShellState>();

  /// Pop all routes back to Shell and switch to the given tab index.
  static void switchTab(BuildContext context, int index) {
    Navigator.of(context).popUntil((route) => route.isFirst);
    shellKey.currentState?._setTab(index);
  }

  @override
  State<_Shell> createState() => _ShellState();
}

class _ShellState extends State<_Shell> {
  int _currentIndex = 0;

  void _setTab(int index) => setState(() => _currentIndex = index);

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final auth = context.read<AuthProvider>();
      if (auth.isAuthenticated) {
        context.read<CartProvider>().loadCart();
      }
      auth.addListener(() {
        if (auth.isAuthenticated) {
          context.read<CartProvider>().loadCart();
        } else {
          context.read<CartProvider>().reset();
        }
      });
    });
  }

  Widget _screenAt(int i) {
    switch (i) {
      case 0:
        return const HomeScreen();
      case 1:
        return const ShopScreen();
      case 2:
        return const CartScreen();
      case 3:
        return const AccountScreen();
      default:
        return const HomeScreen();
    }
  }

  @override
  Widget build(BuildContext context) {
    final cartCount = context.watch<CartProvider>().cart.items.length;
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Image.asset('assets/images/logo.png', height: 36),
            const SizedBox(width: 8),
          ],
        ),
        centerTitle: false,
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(3),
          child: Container(
            height: 3,
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                colors: [kCoral, kYellow, kMint, kLavender],
              ),
            ),
          ),
        ),
      ),
      body: _screenAt(_currentIndex),
      bottomNavigationBar: AppBottomNav(
        currentIndex: _currentIndex,
        cartCount: cartCount,
        onTap: (i) => setState(() => _currentIndex = i),
      ),
    );
  }
}

/// Reusable bottom navigation bar used by Shell and inner screens.
class AppBottomNav extends StatelessWidget {
  const AppBottomNav({
    super.key,
    required this.currentIndex,
    required this.cartCount,
    required this.onTap,
  });
  final int currentIndex;
  final int cartCount;
  final ValueChanged<int> onTap;

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        border: const Border(
          top: BorderSide(color: Color(0xFFFFD6E5), width: 2),
        ),
        boxShadow: [
          BoxShadow(
            color: kCoral.withOpacity(0.12),
            blurRadius: 24,
            offset: const Offset(0, -6),
          ),
        ],
      ),
      child: NavigationBar(
        selectedIndex: currentIndex,
        onDestinationSelected: onTap,
        backgroundColor: Colors.transparent,
        elevation: 0,
        destinations: [
          const NavigationDestination(
            icon: Icon(Icons.home_rounded),
            selectedIcon: Icon(Icons.home_rounded),
            label: 'Home',
          ),
          const NavigationDestination(
            icon: Icon(Icons.storefront_outlined),
            selectedIcon: Icon(Icons.storefront_rounded),
            label: 'Shop',
          ),
          NavigationDestination(
            icon: Badge(
              isLabelVisible: cartCount > 0,
              label: Text('$cartCount'),
              backgroundColor: kCoral,
              child: const Icon(Icons.shopping_bag_outlined),
            ),
            selectedIcon: Badge(
              isLabelVisible: cartCount > 0,
              label: Text('$cartCount'),
              backgroundColor: kCoral,
              child: const Icon(Icons.shopping_bag_rounded),
            ),
            label: 'Cart',
          ),
          const NavigationDestination(
            icon: Icon(Icons.person_outline_rounded),
            selectedIcon: Icon(Icons.person_rounded),
            label: 'Account',
          ),
        ],
      ),
    );
  }
}
