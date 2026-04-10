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
      final resp = await _api.dio.get(ApiEndpoints.cart);
      _cart =
          CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
    } catch (_) {
      // keep current state
    }
    _loading = false;
    _fetching = false;
    notifyListeners();
  }

  Future<void> addItem(int productId, [int qty = 1]) async {
    final resp = await _api.dio.post(ApiEndpoints.cart, data: {
      'product_id': productId,
      'qty': qty,
    });
    _cart = CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
    notifyListeners();
  }

  Future<void> updateQty(int productId, int qty) async {
    final resp = await _api.dio.patch('${ApiEndpoints.cart}/$productId', data: {
      'qty': qty,
    });
    _cart = CartResponse.fromJson(resp.data['data'] as Map<String, dynamic>);
    notifyListeners();
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
