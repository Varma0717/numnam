class Review {
  final int id;
  final int rating;
  final String? title;
  final String? body;
  final bool isApproved;
  final String? createdAt;
  final ReviewUser? user;

  const Review({
    required this.id,
    required this.rating,
    this.title,
    this.body,
    this.isApproved = false,
    this.createdAt,
    this.user,
  });

  factory Review.fromJson(Map<String, dynamic> json) {
    return Review(
      id: json['id'] as int,
      rating: json['rating'] as int? ?? 0,
      title: json['title'] as String?,
      body: json['body'] as String?,
      isApproved: json['is_approved'] as bool? ?? false,
      createdAt: json['created_at'] as String?,
      user: json['user'] != null
          ? ReviewUser.fromJson(json['user'] as Map<String, dynamic>)
          : null,
    );
  }
}

class ReviewUser {
  final int id;
  final String name;

  const ReviewUser({required this.id, required this.name});

  factory ReviewUser.fromJson(Map<String, dynamic> json) {
    return ReviewUser(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? '',
    );
  }
}

class ReviewSummary {
  final double averageRating;
  final int approvedReviewsCount;

  const ReviewSummary(
      {required this.averageRating, required this.approvedReviewsCount});

  factory ReviewSummary.fromJson(Map<String, dynamic> json) {
    return ReviewSummary(
      averageRating: (json['average_rating'] as num?)?.toDouble() ?? 0,
      approvedReviewsCount: json['approved_reviews_count'] as int? ?? 0,
    );
  }
}
