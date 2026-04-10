class Product {
  final int id;
  final String name;
  final String slug;
  final String? sku;
  final String? description;
  final String? shortDescription;
  final String? content;
  final String? ingredients;
  final String? ageGroup;
  final String? type;
  final double price;
  final double? salePrice;
  final int stock;
  final String? image;
  final String? imageUrl;
  final List<String> gallery;
  final List<String> galleryUrls;
  final bool isActive;
  final bool isFeatured;
  final List<String> badges;
  final Map<String, dynamic>? nutritionFacts;
  final Map<String, dynamic>? nutritionInfo;
  final ProductCategory? productCategory;

  const Product({
    required this.id,
    required this.name,
    required this.slug,
    this.sku,
    this.description,
    this.shortDescription,
    this.content,
    this.ingredients,
    this.ageGroup,
    this.type,
    required this.price,
    this.salePrice,
    this.stock = 0,
    this.image,
    this.imageUrl,
    this.gallery = const [],
    this.galleryUrls = const [],
    this.isActive = true,
    this.isFeatured = false,
    this.badges = const [],
    this.nutritionFacts,
    this.nutritionInfo,
    this.productCategory,
  });

  double get effectivePrice => salePrice ?? price;
  bool get isOnSale => salePrice != null && salePrice! < price;
  bool get inStock => stock > 0;

  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      sku: json['sku'] as String?,
      description: json['description'] as String?,
      shortDescription: json['short_description'] as String?,
      content: json['content'] as String?,
      ingredients: json['ingredients'] as String?,
      ageGroup: json['age_group'] as String?,
      type: json['type'] as String?,
      price: _toDouble(json['price']),
      salePrice: json['sale_price'] != null ? _toDouble(json['sale_price']) : null,
      stock: json['stock'] as int? ?? 0,
      image: json['image'] as String?,
      imageUrl: json['image_url'] as String?,
      gallery: (json['gallery'] as List<dynamic>?)
              ?.map((e) => e.toString())
              .toList() ??
          const [],
      galleryUrls: (json['gallery_urls'] as List<dynamic>?)
              ?.map((e) => e.toString())
              .toList() ??
          const [],
      isActive: json['is_active'] as bool? ?? true,
      isFeatured: json['is_featured'] as bool? ?? false,
      badges: (json['badges'] as List<dynamic>?)
              ?.map((e) => e.toString())
              .toList() ??
          const [],
      nutritionFacts: json['nutrition_facts'] as Map<String, dynamic>?,
      nutritionInfo: json['nutrition_info'] as Map<String, dynamic>?,
      productCategory: json['product_category'] != null
          ? ProductCategory.fromJson(
              json['product_category'] as Map<String, dynamic>)
          : null,
    );
  }

  static double _toDouble(dynamic v) {
    if (v is double) return v;
    if (v is int) return v.toDouble();
    if (v is String) return double.tryParse(v) ?? 0;
    return 0;
  }
}

class ProductCategory {
  final int id;
  final String name;
  final String slug;

  const ProductCategory(
      {required this.id, required this.name, required this.slug});

  factory ProductCategory.fromJson(Map<String, dynamic> json) {
    return ProductCategory(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
    );
  }
}
