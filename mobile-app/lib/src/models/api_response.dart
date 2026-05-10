class ApiResponse<T> {
  final bool success;
  final String message;
  final T? data;
  final PaginationMeta? meta;

  const ApiResponse({
    required this.success,
    required this.message,
    this.data,
    this.meta,
  });

  factory ApiResponse.fromJson(
    Map<String, dynamic> json,
    T Function(dynamic)? fromData,
  ) {
    return ApiResponse(
      success: json['success'] as bool? ?? false,
      message: json['message'] as String? ?? '',
      data: json['data'] != null && fromData != null
          ? fromData(json['data'])
          : json['data'] as T?,
      meta: json['meta'] != null
          ? PaginationMeta.fromJson(json['meta'] as Map<String, dynamic>)
          : null,
    );
  }
}

class PaginationMeta {
  final int currentPage;
  final int lastPage;
  final int perPage;
  final int total;
  final bool hasMorePages;

  const PaginationMeta({
    required this.currentPage,
    required this.lastPage,
    required this.perPage,
    required this.total,
    required this.hasMorePages,
  });

  factory PaginationMeta.fromJson(Map<String, dynamic> json) {
    return PaginationMeta(
      currentPage: json['current_page'] as int? ?? 1,
      lastPage: json['last_page'] as int? ?? 1,
      perPage: json['per_page'] as int? ?? 10,
      total: json['total'] as int? ?? 0,
      hasMorePages: json['has_more_pages'] as bool? ?? false,
    );
  }
}
