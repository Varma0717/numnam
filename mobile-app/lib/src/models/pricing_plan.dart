import 'product.dart';

class PricingPlan {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final double price;
  final String? duration;
  final String? billingCycle;
  final List<String> features;
  final bool isActive;
  final bool isPopular;
  final List<Product> products;

  const PricingPlan({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    required this.price,
    this.duration,
    this.billingCycle,
    this.features = const [],
    this.isActive = true,
    this.isPopular = false,
    this.products = const [],
  });

  factory PricingPlan.fromJson(Map<String, dynamic> json) {
    return PricingPlan(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      description: json['description'] as String?,
      price: _d(json['price']),
      duration: json['duration'] as String?,
      billingCycle: json['billing_cycle'] as String?,
      features: (json['features'] as List<dynamic>?)
              ?.map((e) => e.toString())
              .toList() ??
          const [],
      isActive: json['is_active'] as bool? ?? true,
      isPopular: json['is_popular'] as bool? ?? false,
      products: (json['products'] as List<dynamic>?)
              ?.map((e) => Product.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
    );
  }

  static double _d(dynamic v) {
    if (v is double) return v;
    if (v is int) return v.toDouble();
    if (v is String) return double.tryParse(v) ?? 0;
    return 0;
  }
}
