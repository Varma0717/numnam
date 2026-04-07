import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';

import 'core/api_client.dart';
import 'features/home/home_screen.dart';

class NumNamApp extends StatelessWidget {
  const NumNamApp({super.key});

  @override
  Widget build(BuildContext context) {
    return Provider<ApiClient>(
      create: (_) => ApiClient(),
      child: MaterialApp(
        title: 'NumNam',
        debugShowCheckedModeBanner: false,
        theme: ThemeData(
          colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xFFF59E0B)),
          scaffoldBackgroundColor: const Color(0xFFFFFBF2),
          textTheme: GoogleFonts.baloo2TextTheme(),
        ),
        home: const _Shell(),
      ),
    );
  }
}
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';

import 'core/api_client.dart';
import 'features/home/home_screen.dart';

// ── Kids brand colours ─────────────────────────────────────────────────────
const _kCoral     = Color(0xFFFF6B8A);
const _kYellow    = Color(0xFFFFD93D);
const _kMint      = Color(0xFF4ECDC4);
const _kLavender  = Color(0xFF9B8EC4);
const _kCream     = Color(0xFFFFFCF5);
const _kNavy      = Color(0xFF1A1A2E);

class NumNamApp extends StatelessWidget {
  const NumNamApp({super.key});

  @override
  Widget build(BuildContext context) {
    final base = GoogleFonts.baloo2TextTheme();
    final bodyBase = GoogleFonts.poppinsTextTheme();

    return Provider<ApiClient>(
      create: (_) => ApiClient(),
      child: MaterialApp(
        title: 'NumNam Kids Store',
        debugShowCheckedModeBanner: false,
        theme: ThemeData(
          useMaterial3: true,
          colorScheme: ColorScheme(
            brightness: Brightness.light,
            primary:          _kCoral,
            onPrimary:        Colors.white,
            primaryContainer: const Color(0xFFFFF0F5),
            onPrimaryContainer: const Color(0xFFDD3259),
            secondary:        _kYellow,
            onSecondary:      _kNavy,
            secondaryContainer: const Color(0xFFFFFBE6),
            onSecondaryContainer: const Color(0xFF8B6800),
            tertiary:         _kMint,
            onTertiary:       Colors.white,
            tertiaryContainer: const Color(0xFFE8FCF8),
            onTertiaryContainer: const Color(0xFF00706A),
            error:            const Color(0xFFEF4444),
            onError:          Colors.white,
            errorContainer:   const Color(0xFFFFEDED),
            onErrorContainer: const Color(0xFFB91C1C),
            surface:          _kCream,
            onSurface:        _kNavy,
            surfaceContainerHighest: const Color(0xFFFFF5F8),
            outline:          const Color(0xFFFFD6E5),
          ),
          scaffoldBackgroundColor: _kCream,
          // ── Typography ────────────────────────────────────────────────────
          textTheme: base.copyWith(
            displayLarge:  base.displayLarge?.copyWith(color: _kNavy, fontWeight: FontWeight.w900),
            displayMedium: base.displayMedium?.copyWith(color: _kNavy, fontWeight: FontWeight.w800),
            displaySmall:  base.displaySmall?.copyWith(color: _kNavy, fontWeight: FontWeight.w800),
            headlineLarge: base.headlineLarge?.copyWith(color: _kNavy, fontWeight: FontWeight.w800),
            headlineMedium:base.headlineMedium?.copyWith(color: _kNavy, fontWeight: FontWeight.w700),
            headlineSmall: base.headlineSmall?.copyWith(color: _kNavy, fontWeight: FontWeight.w700),
            titleLarge:    base.titleLarge?.copyWith(color: _kNavy, fontWeight: FontWeight.w700),
            titleMedium:   bodyBase.titleMedium?.copyWith(color: _kNavy, fontWeight: FontWeight.w600),
            bodyLarge:     bodyBase.bodyLarge?.copyWith(color: _kNavy),
            bodyMedium:    bodyBase.bodyMedium?.copyWith(color: const Color(0xFF4A4A6A)),
            bodySmall:     bodyBase.bodySmall?.copyWith(color: const Color(0xFF6B6B8A)),
          ),
          // ── AppBar ────────────────────────────────────────────────────────
          appBarTheme: AppBarTheme(
            backgroundColor: Colors.white,
            foregroundColor: _kNavy,
            elevation: 0,
            scrolledUnderElevation: 2,
            shadowColor: _kCoral.withOpacity(0.12),
            titleTextStyle: GoogleFonts.baloo2(
              fontSize: 22,
              fontWeight: FontWeight.w800,
              color: _kNavy,
            ),
            systemOverlayStyle: SystemUiOverlayStyle.dark,
          ),
          // ── Cards ─────────────────────────────────────────────────────────
          cardTheme: CardThemeData(
            color: Colors.white,
            elevation: 0,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(20),
              side: const BorderSide(color: Color(0xFFFFD6E5), width: 2),
            ),
          ),
          // ── Bottom nav ────────────────────────────────────────────────────
          navigationBarTheme: NavigationBarThemeData(
            backgroundColor: Colors.white,
            indicatorColor: _kCoral.withOpacity(0.18),
            iconTheme: WidgetStateProperty.resolveWith((states) {
              if (states.contains(WidgetState.selected)) {
                return const IconThemeData(color: _kCoral, size: 26);
              }
              return const IconThemeData(color: Color(0xFF9E9EBE), size: 24);
            }),
            labelTextStyle: WidgetStateProperty.resolveWith((states) {
              final base = GoogleFonts.poppins(fontSize: 11, fontWeight: FontWeight.w700);
              if (states.contains(WidgetState.selected)) {
                return base.copyWith(color: _kCoral);
              }
              return base.copyWith(color: const Color(0xFF9E9EBE));
            }),
          ),
          // ── Buttons ──────────────────────────────────────────────────────
          filledButtonTheme: FilledButtonThemeData(
            style: FilledButton.styleFrom(
              backgroundColor: _kCoral,
              foregroundColor: Colors.white,
              textStyle: GoogleFonts.poppins(fontWeight: FontWeight.w700),
              shape: const StadiumBorder(),
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
            ),
          ),
          elevatedButtonTheme: ElevatedButtonThemeData(
            style: ElevatedButton.styleFrom(
              backgroundColor: _kYellow,
              foregroundColor: _kNavy,
              textStyle: GoogleFonts.poppins(fontWeight: FontWeight.w700),
              shape: const StadiumBorder(),
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
              elevation: 0,
            ),
          ),
          // ── Input / Forms ─────────────────────────────────────────────────
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
              borderSide: const BorderSide(color: _kCoral, width: 2),
            ),
          ),
        ),
        home: const _Shell(),
      ),
    );
  }
}

class _Shell extends StatefulWidget {
  const _Shell();

  @override
  State<_Shell> createState() => _ShellState();
}

class _ShellState extends State<_Shell> {
  int _currentIndex = 0;

  final List<Widget> _screens = const [
    HomeScreen(),
    _PlaceholderScreen(title: 'Shop'),
    _PlaceholderScreen(title: 'Cart'),
    _PlaceholderScreen(title: 'Account'),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('NumNam Kids Store'),
        centerTitle: false,
      ),
      body: _screens[_currentIndex],
      bottomNavigationBar: NavigationBar(
        selectedIndex: _currentIndex,
        onDestinationSelected: (index) => setState(() => _currentIndex = index),
        destinations: const [
          NavigationDestination(icon: Icon(Icons.home_outlined), selectedIcon: Icon(Icons.home), label: 'Home'),
          NavigationDestination(icon: Icon(Icons.storefront_outlined), selectedIcon: Icon(Icons.storefront), label: 'Shop'),
          NavigationDestination(icon: Icon(Icons.shopping_bag_outlined), selectedIcon: Icon(Icons.shopping_bag), label: 'Cart'),
          NavigationDestination(icon: Icon(Icons.person_outline), selectedIcon: Icon(Icons.person), label: 'Account'),
        ],
      ),
    );
  }
}

class _PlaceholderScreen extends StatelessWidget {
  const _PlaceholderScreen({required this.title});

  final String title;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Text(
        '$title screen will be connected to API in next milestone.',
        style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600),
        textAlign: TextAlign.center,
      ),
    );
  }
}
class _Shell extends StatefulWidget {
  const _Shell();

  @override
  State<_Shell> createState() => _ShellState();
}

class _ShellState extends State<_Shell> {
  int _currentIndex = 0;

  final List<Widget> _screens = const [
    HomeScreen(),
    _PlaceholderScreen(title: 'Shop',    icon: Icons.storefront),
    _PlaceholderScreen(title: 'Cart',    icon: Icons.shopping_bag),
    _PlaceholderScreen(title: 'Account', icon: Icons.person),
  ];

  static const _titles = ['Home', 'Shop', 'Cart', 'Account'];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Text(
              'NumNam',
              style: GoogleFonts.baloo2(
                fontSize: 24,
                fontWeight: FontWeight.w900,
                color: _kCoral,
                letterSpacing: -0.5,
              ),
            ),
            const SizedBox(width: 6),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: _kYellow,
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text(
                _titles[_currentIndex],
                style: GoogleFonts.poppins(
                  fontSize: 11,
                  fontWeight: FontWeight.w700,
                  color: _kNavy,
                ),
              ),
            ),
          ],
        ),
        centerTitle: false,
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(3),
          child: Container(
            height: 3,
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                colors: [_kCoral, _kYellow, _kMint, _kLavender],
              ),
            ),
          ),
        ),
      ),
      body: _screens[_currentIndex],
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          border: const Border(
            top: BorderSide(color: Color(0xFFFFD6E5), width: 2),
          ),
          boxShadow: [
            BoxShadow(
              color: _kCoral.withOpacity(0.12),
              blurRadius: 24,
              offset: const Offset(0, -6),
            ),
          ],
        ),
        child: NavigationBar(
          selectedIndex: _currentIndex,
          onDestinationSelected: (index) => setState(() => _currentIndex = index),
          backgroundColor: Colors.transparent,
          elevation: 0,
          destinations: const [
            NavigationDestination(
              icon: Icon(Icons.home_rounded),
              selectedIcon: Icon(Icons.home_rounded),
              label: 'Home',
            ),
            NavigationDestination(
              icon: Icon(Icons.storefront_outlined),
              selectedIcon: Icon(Icons.storefront_rounded),
              label: 'Shop',
            ),
            NavigationDestination(
              icon: Icon(Icons.shopping_bag_outlined),
              selectedIcon: Icon(Icons.shopping_bag_rounded),
              label: 'Cart',
            ),
            NavigationDestination(
              icon: Icon(Icons.person_outline_rounded),
              selectedIcon: Icon(Icons.person_rounded),
              label: 'Account',
            ),
          ],
        ),
      ),
    );
  }
}

class _PlaceholderScreen extends StatelessWidget {
  const _PlaceholderScreen({required this.title, required this.icon});

  final String title;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 96,
            height: 96,
            decoration: BoxDecoration(
              color: _kCoral.withOpacity(0.10),
              borderRadius: BorderRadius.circular(28),
            ),
            child: Icon(icon, size: 48, color: _kCoral),
          ),
          const SizedBox(height: 16),
          Text(
            title,
            style: GoogleFonts.baloo2(
              fontSize: 22,
              fontWeight: FontWeight.w800,
              color: _kNavy,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Coming soon — connecting to API!',
            style: GoogleFonts.poppins(fontSize: 13, color: const Color(0xFF9E9EBE)),
          ),
        ],
      ),
    );
  }
}
