import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../config/app_config.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/blog.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/error_view.dart';
import '../../shared/widgets/loading_indicator.dart';

class BlogDetailScreen extends StatefulWidget {
  const BlogDetailScreen({super.key});
  static const routeName = '/blog-detail';

  @override
  State<BlogDetailScreen> createState() => _BlogDetailScreenState();
}

class _BlogDetailScreenState extends State<BlogDetailScreen> {
  Blog? _blog;
  bool _loading = true;
  String? _error;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_blog == null && _loading) {
      final slug = ModalRoute.of(context)!.settings.arguments as String;
      _load(slug);
    }
  }

  Future<void> _load(String slug) async {
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio.get('${ApiEndpoints.blogs}/$slug');
      final data = resp.data['data'] as Map<String, dynamic>;
      setState(() {
        _blog = Blog.fromJson(data);
        _loading = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Failed to load article';
        _loading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(_blog?.title ?? 'Article')),
      body: _loading
          ? const LoadingIndicator()
          : _error != null
              ? ErrorView(message: _error!)
              : _buildDetail(),
    );
  }

  Widget _buildDetail() {
    final b = _blog!;
    return ListView(
      children: [
        if (b.image != null)
          AspectRatio(
            aspectRatio: 16 / 9,
            child: CachedNetworkImage(
              imageUrl: AppConfig.imageUrl(b.image!),
              fit: BoxFit.cover,
            ),
          ),
        Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              if (b.category != null)
                Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                  margin: const EdgeInsets.only(bottom: 10),
                  decoration: BoxDecoration(
                    color: kLavender.withOpacity(0.15),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(b.category!.name,
                      style: GoogleFonts.poppins(
                          fontSize: 10,
                          fontWeight: FontWeight.w600,
                          color: kLavender)),
                ),
              Text(b.title,
                  style: GoogleFonts.baloo2(
                      fontSize: 24,
                      fontWeight: FontWeight.w900,
                      height: 1.2,
                      color: kNavy)),
              const SizedBox(height: 10),
              Row(
                children: [
                  if (b.author != null)
                    Text('By ${b.author!.name}',
                        style: GoogleFonts.poppins(
                            fontSize: 12, color: const Color(0xFF6B6B8A))),
                  const Spacer(),
                  if (b.readTime != null)
                    Row(
                      children: [
                        const Icon(Icons.schedule_rounded,
                            size: 14, color: Color(0xFF9E9EBE)),
                        const SizedBox(width: 4),
                        Text('${b.readTime} min',
                            style: GoogleFonts.poppins(
                                fontSize: 12,
                                color: const Color(0xFF9E9EBE))),
                      ],
                    ),
                ],
              ),
              const SizedBox(height: 16),
              if (b.content != null)
                Html(
                  data: b.content!,
                  style: {
                    'body': Style(
                      fontSize: FontSize(14),
                      color: const Color(0xFF4A4A6A),
                      lineHeight: LineHeight.number(1.7),
                      margin: Margins.zero,
                      padding: HtmlPaddings.zero,
                    ),
                    'h2': Style(
                      fontSize: FontSize(18),
                      fontWeight: FontWeight.w700,
                      color: kNavy,
                    ),
                    'h3': Style(
                      fontSize: FontSize(16),
                      fontWeight: FontWeight.w600,
                      color: kNavy,
                    ),
                    'a': Style(color: kCoral),
                  },
                ),
              const SizedBox(height: 32),
            ],
          ),
        ),
      ],
    );
  }
}
