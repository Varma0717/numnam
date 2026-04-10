import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../config/app_config.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/blog.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/empty_state.dart';
import '../../shared/widgets/inner_page_nav.dart';
import '../../shared/widgets/error_view.dart';
import '../../shared/widgets/loading_indicator.dart';
import 'blog_detail_screen.dart';

class BlogListScreen extends StatefulWidget {
  const BlogListScreen({super.key});
  static const routeName = '/blogs';

  @override
  State<BlogListScreen> createState() => _BlogListScreenState();
}

class _BlogListScreenState extends State<BlogListScreen> {
  List<Blog> _blogs = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get(ApiEndpoints.blogs);
      final data = resp.data['data'];
      List<dynamic> list;
      if (data is List) {
        list = data;
      } else if (data is Map && data['data'] != null) {
        list = data['data'] as List;
      } else {
        list = [];
      }
      setState(() {
        _blogs = list.map((e) => Blog.fromJson(e as Map<String, dynamic>)).toList();
        _loading = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Failed to load articles';
        _loading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Blog')),
      bottomNavigationBar: const InnerPageNav(),
      body: _loading
          ? const LoadingIndicator(message: 'Loading articles...')
          : _error != null
              ? ErrorView(message: _error!, onRetry: _load)
              : _blogs.isEmpty
                  ? const EmptyState(
                      icon: Icons.article_outlined,
                      title: 'No Articles Yet',
                      subtitle: 'Check back soon for tasty reads!',
                    )
                  : RefreshIndicator(
                      color: kCoral,
                      onRefresh: _load,
                      child: ListView.separated(
                        padding: const EdgeInsets.all(16),
                        itemCount: _blogs.length,
                        separatorBuilder: (_, __) =>
                            const SizedBox(height: 14),
                        itemBuilder: (_, i) => _BlogTile(
                          blog: _blogs[i],
                          onTap: () => Navigator.of(context).pushNamed(
                            BlogDetailScreen.routeName,
                            arguments: _blogs[i].slug,
                          ),
                        ),
                      ),
                    ),
    );
  }
}

class _BlogTile extends StatelessWidget {
  const _BlogTile({required this.blog, this.onTap});
  final Blog blog;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(18),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
        ),
        clipBehavior: Clip.antiAlias,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (blog.featuredImage != null)
              AspectRatio(
                aspectRatio: 16 / 9,
                child: CachedNetworkImage(
                  imageUrl: AppConfig.imageUrl(blog.featuredImage!),
                  fit: BoxFit.cover,
                  placeholder: (_, __) => Container(color: const Color(0xFFFFF0F5)),
                  errorWidget: (_, __, ___) =>
                      Container(color: const Color(0xFFFFF0F5)),
                ),
              ),
            Padding(
              padding: const EdgeInsets.all(14),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  if (blog.category != null)
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 10, vertical: 3),
                      margin: const EdgeInsets.only(bottom: 8),
                      decoration: BoxDecoration(
                        color: kLavender.withOpacity(0.15),
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Text(blog.category!.name,
                          style: GoogleFonts.poppins(
                              fontSize: 10,
                              fontWeight: FontWeight.w600,
                              color: kLavender)),
                    ),
                  Text(blog.title,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: GoogleFonts.baloo2(
                          fontSize: 17,
                          fontWeight: FontWeight.w700,
                          height: 1.2,
                          color: kNavy)),
                  if (blog.excerpt != null) ...[
                    const SizedBox(height: 6),
                    Text(blog.excerpt!,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: GoogleFonts.poppins(
                            fontSize: 12, color: const Color(0xFF6B6B8A))),
                  ],
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      if (blog.author != null)
                        Text('By ${blog.author!.name}',
                            style: GoogleFonts.poppins(
                                fontSize: 11, color: const Color(0xFF9E9EBE))),
                      const Spacer(),
                      if (blog.publishedAt != null)
                        Text(blog.publishedAt!,
                            style: GoogleFonts.poppins(
                                fontSize: 11, color: const Color(0xFF9E9EBE))),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
