class Order {
  final int id;
  final String orderNumber;
  final String status;
  final double subtotal;
  final double discount;
  final double shippingFee;
  final double total;
  final String? paymentMethod;
  final String? paymentStatus;
  final String? couponCode;
  final String? shipName;
  final String? shipPhone;
  final String? shipAddress;
  final String? shipCity;
  final String? shipState;
  final String? shipPincode;
  final String? trackingNumber;
  final String? notes;
  final List<OrderItem> items;
  final String? createdAt;

  const Order({
    required this.id,
    required this.orderNumber,
    required this.status,
    required this.subtotal,
    this.discount = 0,
    this.shippingFee = 0,
    required this.total,
    this.paymentMethod,
    this.paymentStatus,
    this.couponCode,
    this.shipName,
    this.shipPhone,
    this.shipAddress,
    this.shipCity,
    this.shipState,
    this.shipPincode,
    this.trackingNumber,
    this.notes,
    this.items = const [],
    this.createdAt,
  });

  factory Order.fromJson(Map<String, dynamic> json) {
    return Order(
      id: json['id'] as int,
      orderNumber: json['order_number'] as String? ?? '',
      status: json['status'] as String? ?? 'pending',
      subtotal: _d(json['subtotal']),
      discount: _d(json['discount']),
      shippingFee: _d(json['shipping_fee']),
      total: _d(json['total']),
      paymentMethod: json['payment_method'] as String?,
      paymentStatus: json['payment_status'] as String?,
      couponCode: json['coupon_code'] as String?,
      shipName: json['ship_name'] as String?,
      shipPhone: json['ship_phone'] as String?,
      shipAddress: json['ship_address'] as String?,
      shipCity: json['ship_city'] as String?,
      shipState: json['ship_state'] as String?,
      shipPincode: json['ship_pincode'] as String?,
      trackingNumber: json['tracking_number'] as String?,
      notes: json['notes'] as String?,
      items: (json['items'] as List<dynamic>?)
              ?.map((e) => OrderItem.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      createdAt: json['created_at'] as String?,
    );
  }

  static double _d(dynamic v) {
    if (v is double) return v;
    if (v is int) return v.toDouble();
    if (v is String) return double.tryParse(v) ?? 0;
    return 0;
  }
}

class OrderItem {
  final int id;
  final int productId;
  final String productName;
  final double unitPrice;
  final int quantity;
  final double lineTotal;

  const OrderItem({
    required this.id,
    required this.productId,
    required this.productName,
    required this.unitPrice,
    required this.quantity,
    required this.lineTotal,
  });

  factory OrderItem.fromJson(Map<String, dynamic> json) {
    return OrderItem(
      id: json['id'] as int? ?? 0,
      productId: json['product_id'] as int,
      productName: json['product_name'] as String? ?? '',
      unitPrice: _d(json['unit_price']),
      quantity: json['quantity'] as int? ?? 1,
      lineTotal: _d(json['line_total']),
    );
  }

  static double _d(dynamic v) {
    if (v is double) return v;
    if (v is int) return v.toDouble();
    if (v is String) return double.tryParse(v) ?? 0;
    return 0;
  }
}
