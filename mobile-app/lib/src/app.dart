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
