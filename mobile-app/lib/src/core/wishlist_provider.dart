import 'package:flutter/material.dart';
import 'api_client.dart';
import 'constants.dart';

class WishlistProvider extends ChangeNotifier {
  final ApiClient _api;
  Set<int> _wishlistProductIds = {};
  bool _loading = false;

  WishlistProvider(this._api);

  Set<int> get wishlistProductIds => _wishlistProductIds;
  bool get isLoading => _loading;

  bool isWished(int productId) => _wishlistProductIds.contains(productId);

  Future<void> loadWishlist() async {
    _loading = true;
    notifyListeners();
    try {
      final resp = await _api.dio.get(ApiEndpoints.wishlist);
      final data = resp.data['data'];
      List<dynamic> list;
      if (data is List) {
        list = data;
      } else if (data is Map && data['data'] != null) {
        list = data['data'] as List;
      } else {
        list = [];
      }

      _wishlistProductIds = list.map((e) {
        final product = e['product'] as Map<String, dynamic>?;
        return (e['product_id'] ?? product?['id'] ?? 0) as int;
      }).toSet();
      _wishlistProductIds.remove(0);
    } catch (e) {
      debugPrint('❌ Wishlist load error: $e');
    }
    _loading = false;
    notifyListeners();
  }

  Future<void> toggleWishlist(int productId) async {
    final isCurrentlyWished = isWished(productId);

    // Optimistic UI update
    if (isCurrentlyWished) {
      _wishlistProductIds.remove(productId);
    } else {
      _wishlistProductIds.add(productId);
    }
    notifyListeners();

    try {
      if (isCurrentlyWished) {
        await _api.dio.delete('${ApiEndpoints.wishlist}/$productId');
      } else {
        await _api.dio.post(ApiEndpoints.wishlist, data: {'product_id': productId});
      }
    } catch (e) {
      // Revert if failed
      if (isCurrentlyWished) {
        _wishlistProductIds.add(productId);
      } else {
        _wishlistProductIds.remove(productId);
      }
      notifyListeners();
      debugPrint('❌ Wishlist toggle error: $e');
    }
  }

  void reset() {
    _wishlistProductIds = {};
    notifyListeners();
  }
}
