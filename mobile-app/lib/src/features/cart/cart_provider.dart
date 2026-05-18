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
    debugPrint('🛒 Adding to cart: Product ID=$productId, Qty=$qty');
    final resp = await _api.dio.post(ApiEndpoints.cart, data: {
      'product_id': productId,
      'qty': qty,
    });
    debugPrint('✅ Add to cart response: ${resp.data}');
    _cart = CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
    debugPrint('✅ Cart updated: ${_cart.items.length} items');
    notifyListeners();
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
      _cart = CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
      debugPrint('✅ Cart updated successfully');
      notifyListeners();
    } catch (e) {
      debugPrint('❌ Update quantity error: $e');
      // Re-throw to allow UI to handle the error
      rethrow;
    }
  }

  Future<void> removeItem(int productId) async {
    final resp = await _api.dio.delete('${ApiEndpoints.cart}/$productId');
    _cart = CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
    notifyListeners();
  }

  Future<void> clearCart() async {
    await _api.dio.delete(ApiEndpoints.cart);
    _cart = CartResponse.empty;
    notifyListeners();
  }

  void reset() {
    _cart = CartResponse.empty;
    notifyListeners();
  }
}
