import '../config/app_config.dart';

class CartResponse {
  final List<CartItem> items;
  final CartTotals totals;

  const CartResponse({required this.items, required this.totals});

  int get itemCount => items.fold(0, (sum, i) => sum + i.qty);

  factory CartResponse.fromJson(Map<String, dynamic> json) {
    return CartResponse(
      items: (json['items'] as List<dynamic>?)
              ?.map((e) => CartItem.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      totals: json['totals'] != null
          ? CartTotals.fromJson(json['totals'] as Map<String, dynamic>)
          : const CartTotals(subtotal: 0, shippingFee: 0, total: 0),
    );
  }

  static const empty = CartResponse(
      items: [], totals: CartTotals(subtotal: 0, shippingFee: 0, total: 0));
}

class CartItem {
  final int productId;
  final String name;
  final String slug;
  final String? image;
  final String? imageUrl;
  final int qty;
  final double unitPrice;
  final double lineTotal;

  const CartItem({
    required this.productId,
    required this.name,
    required this.slug,
    this.image,
    this.imageUrl,
    required this.qty,
    required this.unitPrice,
    required this.lineTotal,
  });

  String? get displayImageUrl => _normalizeImageUrl(imageUrl ?? image);

  factory CartItem.fromJson(Map<String, dynamic> json) {
    return CartItem(
      productId: json['product_id'] as int,
      name: json['name'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      image: json['image'] as String?,
      imageUrl: json['image_url'] as String?,
      qty: json['qty'] as int? ?? 1,
      unitPrice: _d(json['unit_price']),
      lineTotal: _d(json['line_total']),
    );
  }

  static double _d(dynamic v) {
    if (v is double) return v;
    if (v is int) return v.toDouble();
    if (v is String) return double.tryParse(v) ?? 0;
    return 0;
  }

  static String? _normalizeImageUrl(String? raw) {
    if (raw == null || raw.trim().isEmpty) return null;

    final value = raw.trim();
    if (value.startsWith('http://') || value.startsWith('https://')) {
      return value;
    }

    if (value.startsWith('/')) {
      return '${AppConfig.siteBaseUrl}$value';
    }

    if (value.startsWith('storage/') ||
        value.startsWith('assets/') ||
        value.startsWith('cms-media/')) {
      return '${AppConfig.siteBaseUrl}/$value';
    }

    return '${AppConfig.siteBaseUrl}/storage/$value';
  }
}

class CartTotals {
  final double subtotal;
  final double shippingFee;
  final double total;

  const CartTotals({
    required this.subtotal,
    required this.shippingFee,
    required this.total,
  });

  factory CartTotals.fromJson(Map<String, dynamic> json) {
    return CartTotals(
      subtotal: _d(json['subtotal']),
      shippingFee: _d(json['shipping_fee']),
      total: _d(json['total']),
    );
  }

  static double _d(dynamic v) {
    if (v is double) return v;
    if (v is int) return v.toDouble();
    if (v is String) return double.tryParse(v) ?? 0;
    return 0;
  }
}
