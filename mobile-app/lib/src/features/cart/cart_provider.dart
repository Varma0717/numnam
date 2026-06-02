import 'package:flutter/foundation.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/cart.dart';

class CartProvider extends ChangeNotifier {
  final ApiClient _api;
  CartResponse _cart = CartResponse.empty;
  bool _loading = false;
  bool _fetching = false;

  CartProvider(this._api);

  CartResponse get cart => _cart;
  int get itemCount => _cart.itemCount;
  bool get isLoading => _loading;
  bool get isEmpty => _cart.items.isEmpty;

  Future<void> loadCart() async {
    if (_fetching) return; // prevent concurrent loads
    _fetching = true;
    _loading = true;
    notifyListeners();
    try {
      debugPrint('🛒 Loading cart...');
      final resp = await _api.dio.get(ApiEndpoints.cart);
      debugPrint('✅ Cart response received');
      debugPrint('Cart data: ${resp.data}');
      _cart = CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
      debugPrint('✅ Cart loaded: ${_cart.items.length} items');
    } catch (e) {
      debugPrint('❌ Cart load error: $e');
      // keep current state
    }
    _loading = false;
    _fetching = false;
    notifyListeners();
  }

  Future<void> addItem(int productId, [int qty = 1]) async {
    try {
      debugPrint('🛒 Adding to cart: Product ID=$productId, Qty=$qty');
      final resp = await _api.dio.post(ApiEndpoints.cart, data: {
        'product_id': productId,
        'qty': qty,
      });
      debugPrint('✅ Add to cart response: ${resp.data}');
      if (resp.data != null && resp.data['data'] != null) {
        _cart =
            CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
        debugPrint('✅ Cart updated: ${_cart.items.length} items');
      }
      notifyListeners();
    } catch (e) {
      debugPrint('❌ Add item error: $e');
      rethrow;
    }
  }

  Future<void> updateQty(int productId, int qty) async {
    try {
      debugPrint(
          '🛒 Updating cart quantity: Product ID=$productId, New Qty=$qty');
      final resp =
          await _api.dio.patch('${ApiEndpoints.cart}/$productId', data: {
        'qty': qty,
      });
      debugPrint('✅ Update quantity response: ${resp.data}');
      if (resp.data != null && resp.data['data'] != null) {
        _cart =
            CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
        debugPrint('✅ Cart updated successfully: ${_cart.items.length} items');
      } else {
        // If response doesn't contain updated cart, reload it
        await loadCart();
      }
      notifyListeners();
    } catch (e) {
      debugPrint('❌ Update quantity error: $e');
      rethrow;
    }
  }

  Future<void> removeItem(int productId) async {
    try {
      debugPrint('🛒 Removing item: Product ID=$productId');
      final resp = await _api.dio.delete('${ApiEndpoints.cart}/$productId');
      debugPrint('✅ Remove item response: ${resp.data}');
      if (resp.data != null && resp.data['data'] != null) {
        _cart =
            CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
      } else {
        // If response doesn't contain updated cart, reload it
        await loadCart();
      }
      debugPrint('✅ Item removed successfully');
      notifyListeners();
    } catch (e) {
      debugPrint('❌ Remove item error: $e');
      rethrow;
    }
  }

  Future<void> clearCart() async {
    try {
      debugPrint('🛒 Clearing cart');
      await _api.dio.delete(ApiEndpoints.cart);
      _cart = CartResponse.empty;
      debugPrint('✅ Cart cleared successfully');
      notifyListeners();
    } catch (e) {
      debugPrint('❌ Clear cart error: $e');
      rethrow;
    }
  }

  void reset() {
    _cart = CartResponse.empty;
    notifyListeners();
  }
}
