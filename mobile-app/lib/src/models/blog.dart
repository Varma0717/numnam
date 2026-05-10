class Blog {
  final int id;
  final String title;
  final String slug;
  final String? excerpt;
  final String? content;
  final String? featuredImage;
  final String? featuredImageUrl;
  final String? publishedAt;
  final BlogCategory? category;
  final BlogAuthor? author;

  const Blog({
    required this.id,
    required this.title,
    required this.slug,
    this.excerpt,
    this.content,
    this.featuredImage,
    this.featuredImageUrl,
    this.publishedAt,
    this.category,
    this.author,
  });

  factory Blog.fromJson(Map<String, dynamic> json) {
    return Blog(
      id: json['id'] as int,
      title: json['title'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      excerpt: json['excerpt'] as String?,
      content: json['content'] as String?,
      featuredImage: json['featured_image'] as String?,
      featuredImageUrl: json['featured_image_url'] as String?,
      publishedAt: json['published_at'] as String?,
      category: json['category'] != null
          ? BlogCategory.fromJson(json['category'] as Map<String, dynamic>)
          : null,
      author: json['author'] != null
          ? BlogAuthor.fromJson(json['author'] as Map<String, dynamic>)
          : null,
    );
  }
}

class BlogCategory {
  final int id;
  final String name;
  final String slug;

  const BlogCategory(
      {required this.id, required this.name, required this.slug});

  factory BlogCategory.fromJson(Map<String, dynamic> json) {
    return BlogCategory(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
    );
  }
}

class BlogAuthor {
  final int id;
  final String name;

  const BlogAuthor({required this.id, required this.name});

  factory BlogAuthor.fromJson(Map<String, dynamic> json) {
    return BlogAuthor(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
    );
  }
}
