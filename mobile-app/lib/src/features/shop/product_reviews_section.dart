import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/api_client.dart';
import '../../core/auth_provider.dart';
import '../../core/constants.dart';
import '../../models/review.dart';
import '../../shared/theme/colors.dart';

class ProductReviewsSection extends StatefulWidget {
  const ProductReviewsSection({super.key, required this.productId});
  final int productId;

  @override
  State<ProductReviewsSection> createState() => _ProductReviewsSectionState();
}

class _ProductReviewsSectionState extends State<ProductReviewsSection> {
  ReviewSummary? _summary;
  List<Review> _reviews = [];
  bool _loading = true;
  bool _showForm = false;
  final _ratingCtrl = ValueNotifier<int>(5);
  final _bodyCtrl = TextEditingController();
  bool _submitting = false;

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void dispose() {
    _bodyCtrl.dispose();
    _ratingCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    final auth = context.read<AuthProvider>();
    if (!auth.isAuthenticated) {
      setState(() => _loading = false);
      return;
    }
    try {
      final api = context.read<ApiClient>();
      final resp = await api.dio
          .get(ApiEndpoints.productReviews(widget.productId));
      final data = resp.data['data'] as Map<String, dynamic>;
      setState(() {
        _summary =
            ReviewSummary.fromJson(data['summary'] as Map<String, dynamic>);
        _reviews = (data['reviews'] as List<dynamic>)
            .map((e) => Review.fromJson(e as Map<String, dynamic>))
            .toList();
        _loading = false;
      });
    } catch (_) {
      setState(() => _loading = false);
    }
  }

  Future<void> _submitReview() async {
    if (_bodyCtrl.text.trim().length < 10) return;
    setState(() => _submitting = true);
    try {
      final api = context.read<ApiClient>();
      await api.dio.post(
        ApiEndpoints.productReviews(widget.productId),
        data: {
          'rating': _ratingCtrl.value,
          'body': _bodyCtrl.text.trim(),
        },
      );
      _bodyCtrl.clear();
      setState(() {
        _showForm = false;
        _submitting = false;
      });
      _load();
    } catch (_) {
      setState(() => _submitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Text('Reviews',
                style: GoogleFonts.baloo2(
                    fontSize: 18, fontWeight: FontWeight.w700, color: kNavy)),
            const Spacer(),
            if (_summary != null) ...[
              Icon(Icons.star_rounded, size: 18, color: kYellow),
              const SizedBox(width: 4),
              Text(
                '${_summary!.averageRating} (${_summary!.approvedReviewsCount})',
                style: GoogleFonts.poppins(
                    fontSize: 13, fontWeight: FontWeight.w600, color: kNavy),
              ),
            ],
          ],
        ),
        const SizedBox(height: 12),
        if (_loading)
          const Padding(
            padding: EdgeInsets.symmetric(vertical: 16),
            child: Center(
                child: CircularProgressIndicator(strokeWidth: 2, color: kCoral)),
          ),
        if (!_loading && _reviews.isNotEmpty)
          ..._reviews.map((r) => _ReviewTile(review: r)),
        if (!_loading && _reviews.isEmpty && auth.isAuthenticated)
          Text('No reviews yet. Be the first!',
              style: GoogleFonts.poppins(
                  fontSize: 13, color: const Color(0xFF9E9EBE))),
        if (auth.isAuthenticated && !_showForm) ...[
          const SizedBox(height: 12),
          OutlinedButton.icon(
            onPressed: () => setState(() => _showForm = true),
            icon: const Icon(Icons.rate_review_outlined, size: 18),
            label: const Text('Write a Review'),
            style: OutlinedButton.styleFrom(
              foregroundColor: kCoral,
              side: const BorderSide(color: kCoral),
            ),
          ),
        ],
        if (_showForm) ...[
          const SizedBox(height: 16),
          _buildReviewForm(),
        ],
      ],
    );
  }

  Widget _buildReviewForm() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFFFFF5F8),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFFFD6E5)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Your Rating',
              style: GoogleFonts.poppins(
                  fontSize: 13, fontWeight: FontWeight.w600)),
          const SizedBox(height: 8),
          ValueListenableBuilder<int>(
            valueListenable: _ratingCtrl,
            builder: (_, rating, __) => Row(
              children: List.generate(
                5,
                (i) => GestureDetector(
                  onTap: () => _ratingCtrl.value = i + 1,
                  child: Icon(
                    i < rating
                        ? Icons.star_rounded
                        : Icons.star_border_rounded,
                    size: 32,
                    color: kYellow,
                  ),
                ),
              ),
            ),
          ),
          const SizedBox(height: 12),
          TextField(
            controller: _bodyCtrl,
            maxLines: 3,
            decoration: const InputDecoration(
              hintText: 'Write your review (min 10 chars)...',
            ),
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: FilledButton(
                  onPressed: _submitting ? null : _submitReview,
                  child: _submitting
                      ? const SizedBox(
                          width: 18,
                          height: 18,
                          child: CircularProgressIndicator(
                              strokeWidth: 2, color: Colors.white))
                      : const Text('Submit'),
                ),
              ),
              const SizedBox(width: 8),
              TextButton(
                onPressed: () => setState(() => _showForm = false),
                child: const Text('Cancel'),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _ReviewTile extends StatelessWidget {
  const _ReviewTile({required this.review});
  final Review review;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFFFD6E5), width: 1),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              ...List.generate(
                5,
                (i) => Icon(
                  i < review.rating
                      ? Icons.star_rounded
                      : Icons.star_border_rounded,
                  size: 16,
                  color: kYellow,
                ),
              ),
              const SizedBox(width: 8),
              Text(
                review.user?.name ?? 'Anonymous',
                style: GoogleFonts.poppins(
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                    color: kNavy),
              ),
            ],
          ),
          if (review.body != null) ...[
            const SizedBox(height: 6),
            Text(review.body!,
                style: GoogleFonts.poppins(
                    fontSize: 13, color: const Color(0xFF4A4A6A))),
          ],
        ],
      ),
    );
  }
}
