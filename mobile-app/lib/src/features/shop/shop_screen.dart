import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/product.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/error_view.dart';
import '../../shared/widgets/loading_indicator.dart';
import '../../shared/widgets/product_card.dart';
import 'product_detail_screen.dart';

class ShopScreen extends StatefulWidget {
  const ShopScreen({super.key});

  @override
  State<ShopScreen> createState() => _ShopScreenState();
}

class _ShopScreenState extends State<ShopScreen> {
  final _searchCtrl = TextEditingController();
  final _scrollCtrl = ScrollController();
  final List<Product> _products = [];
  bool _loading = true;
  bool _loadingMore = false;
  String? _error;
  int _page = 1;
  int _lastPage = 1;
  String? _search;

  @override
  void initState() {
    super.initState();
    _load();
    _scrollCtrl.addListener(_onScroll);
  }

  @override
  void dispose() {
    _searchCtrl.dispose();
    _scrollCtrl.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollCtrl.position.pixels >=
            _scrollCtrl.position.maxScrollExtent - 200 &&
        !_loadingMore &&
        _page < _lastPage) {
      _loadMore();
    }
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
      _page = 1;
      _products.clear();
    });
    await _fetch();
  }

  Future<void> _loadMore() async {
    setState(() => _loadingMore = true);
    _page++;
    await _fetch();
    setState(() => _loadingMore = false);
  }

  Future<void> _fetch() async {
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get(ApiEndpoints.products, queryParameters: {
        'page': _page,
        'per_page': 12,
        if (_search != null && _search!.isNotEmpty) 'search': _search,
      });
      final data = resp.data;
      final items = (data['data'] as List<dynamic>?)
              ?.map((e) => Product.fromJson(e as Map<String, dynamic>))
              .toList() ??
          [];
      final meta = data['meta'] as Map<String, dynamic>?;
      setState(() {
        _products.addAll(items);
        _lastPage = meta?['last_page'] as int? ?? 1;
        _loading = false;
      });
    } catch (e) {
      setState(() {
        _error = 'Failed to load products';
        _loading = false;
      });
    }
  }

  void _onSearch(String value) {
    _search = value.trim().isEmpty ? null : value.trim();
    _load();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 8),
          child: TextField(
            controller: _searchCtrl,
            onSubmitted: _onSearch,
            textInputAction: TextInputAction.search,
            decoration: InputDecoration(
              hintText: 'Search products...',
              hintStyle: GoogleFonts.poppins(fontSize: 13),
              prefixIcon: const Icon(Icons.search_rounded, size: 20),
              suffixIcon: _searchCtrl.text.isNotEmpty
                  ? IconButton(
                      icon: const Icon(Icons.clear, size: 18),
                      onPressed: () {
                        _searchCtrl.clear();
                        _onSearch('');
                      },
                    )
                  : null,
              isDense: true,
              contentPadding: const EdgeInsets.symmetric(vertical: 12),
            ),
          ),
        ),
        Expanded(child: _buildBody()),
      ],
    );
  }

  Widget _buildBody() {
    if (_loading) return const LoadingIndicator(message: 'Loading products...');
    if (_error != null) return ErrorView(message: _error!, onRetry: _load);
    if (_products.isEmpty) {
      return Center(
        child: Text('No products found',
            style: GoogleFonts.poppins(fontSize: 14, color: kNavy)),
      );
    }
    return RefreshIndicator(
      color: kCoral,
      onRefresh: _load,
      child: GridView.builder(
        controller: _scrollCtrl,
        padding: const EdgeInsets.fromLTRB(16, 4, 16, 24),
        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 2,
          mainAxisSpacing: 14,
          crossAxisSpacing: 14,
          childAspectRatio: 0.68,
        ),
        itemCount: _products.length + (_loadingMore ? 1 : 0),
        itemBuilder: (context, i) {
          if (i == _products.length) {
            return const Center(
                child: Padding(
              padding: EdgeInsets.all(16),
              child: CircularProgressIndicator(strokeWidth: 2, color: kCoral),
            ));
          }
          final p = _products[i];
          return ProductCard(
            product: p,
            onTap: () => Navigator.of(context).pushNamed(
              ProductDetailScreen.routeName,
              arguments: p.slug,
            ),
          );
        },
      ),
    );
  }
}
