import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/api_client.dart';
import '../../core/auth_provider.dart';
import '../../core/constants.dart';
import '../../models/pricing_plan.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/error_view.dart';
import '../../shared/widgets/loading_indicator.dart';
import '../auth/auth_gate.dart';

class SubscriptionsScreen extends StatefulWidget {
  const SubscriptionsScreen({super.key});
  static const routeName = '/subscriptions';

  @override
  State<SubscriptionsScreen> createState() => _SubscriptionsScreenState();
}

class _SubscriptionsScreenState extends State<SubscriptionsScreen> {
  List<PricingPlan> _plans = [];
  bool _loading = true;
  String? _error;
  int? _subscribingId;

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
      final resp = await api.dio.get(ApiEndpoints.pricingPlans);
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
        _plans =
            list.map((e) => PricingPlan.fromJson(e as Map<String, dynamic>)).toList();
        _loading = false;
      });
    } catch (_) {
      setState(() {
        _error = 'Failed to load plans';
        _loading = false;
      });
    }
  }

  Future<void> _subscribe(PricingPlan plan) async {
    final auth = context.read<AuthProvider>();
    if (!auth.isAuthenticated) return;
    setState(() => _subscribingId = plan.id);
    try {
      final api = context.read<ApiClient>();
      await api.dio.post(ApiEndpoints.subscriptions, data: {
        'pricing_plan_id': plan.id,
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Subscribed to ${plan.name}!'),
            backgroundColor: kMint,
          ),
        );
      }
    } catch (_) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Failed to subscribe. Please try again.'),
            backgroundColor: kCoral,
          ),
        );
      }
    }
    setState(() => _subscribingId = null);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Meal Plans')),
      body: AuthGate(
        child: _loading
            ? const LoadingIndicator(message: 'Loading plans...')
            : _error != null
                ? ErrorView(message: _error!, onRetry: _load)
                : RefreshIndicator(
                    color: kCoral,
                    onRefresh: _load,
                    child: ListView.separated(
                      padding: const EdgeInsets.all(16),
                      itemCount: _plans.length,
                      separatorBuilder: (_, __) => const SizedBox(height: 14),
                      itemBuilder: (_, i) => _PlanCard(
                        plan: _plans[i],
                        subscribing: _subscribingId == _plans[i].id,
                        onSubscribe: () => _subscribe(_plans[i]),
                      ),
                    ),
                  ),
      ),
    );
  }
}

class _PlanCard extends StatelessWidget {
  const _PlanCard(
      {required this.plan,
      this.subscribing = false,
      required this.onSubscribe});
  final PricingPlan plan;
  final bool subscribing;
  final VoidCallback onSubscribe;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: plan.isPopular ? kCoral : const Color(0xFFFFD6E5),
          width: plan.isPopular ? 2.5 : 1.5,
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (plan.isPopular)
            Container(
              margin: const EdgeInsets.only(bottom: 10),
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
              decoration: BoxDecoration(
                color: kCoral,
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text('POPULAR',
                  style: GoogleFonts.poppins(
                      fontSize: 10,
                      fontWeight: FontWeight.w700,
                      color: Colors.white)),
            ),
          Text(plan.name,
              style: GoogleFonts.baloo2(
                  fontSize: 22, fontWeight: FontWeight.w800, color: kNavy)),
          if (plan.description != null) ...[
            const SizedBox(height: 6),
            Text(plan.description!,
                style: GoogleFonts.poppins(
                    fontSize: 13, color: const Color(0xFF6B6B8A))),
          ],
          const SizedBox(height: 12),
          Row(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text('₹${plan.price.toStringAsFixed(0)}',
                  style: GoogleFonts.poppins(
                      fontSize: 28,
                      fontWeight: FontWeight.w800,
                      color: kCoral)),
              const SizedBox(width: 4),
              Padding(
                padding: const EdgeInsets.only(bottom: 4),
                child: Text('/ ${plan.duration}',
                    style: GoogleFonts.poppins(
                        fontSize: 13, color: const Color(0xFF9E9EBE))),
              ),
            ],
          ),
          if (plan.features.isNotEmpty) ...[
            const SizedBox(height: 14),
            ...plan.features.map((f) => Padding(
                  padding: const EdgeInsets.only(bottom: 6),
                  child: Row(
                    children: [
                      const Icon(Icons.check_circle_rounded,
                          size: 16, color: kMint),
                      const SizedBox(width: 8),
                      Expanded(
                          child: Text(f,
                              style: GoogleFonts.poppins(
                                  fontSize: 13, color: kNavy))),
                    ],
                  ),
                )),
          ],
          const SizedBox(height: 16),
          SizedBox(
            width: double.infinity,
            height: 44,
            child: FilledButton(
              onPressed: subscribing ? null : onSubscribe,
              child: subscribing
                  ? const SizedBox(
                      width: 18,
                      height: 18,
                      child: CircularProgressIndicator(
                          strokeWidth: 2, color: Colors.white))
                  : const Text('Subscribe'),
            ),
          ),
        ],
      ),
    );
  }
}
