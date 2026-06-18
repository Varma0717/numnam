import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import '../../core/api_client.dart';
import '../../core/constants.dart';
import '../../models/pricing_plan.dart';
import '../../shared/theme/colors.dart';
import '../../shared/widgets/inner_page_nav.dart';
import 'subscription_checkout_screen.dart';

class SubscriptionsScreenRedesign extends StatefulWidget {
  static const routeName = '/subscriptions-redesign';

  const SubscriptionsScreenRedesign({super.key});

  @override
  State<SubscriptionsScreenRedesign> createState() =>
      _SubscriptionsScreenRedesignState();
}

class _SubscriptionsScreenRedesignState
    extends State<SubscriptionsScreenRedesign> {
  List<PricingPlan> _plans = [];
  List<Map<String, dynamic>> _mySubscriptions = [];
  bool _loading = true;
  bool _loadingMySubs = true;
  bool _actionInProgress = false;
  String? _mySubsError;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _loadingMySubs = true;
      _mySubsError = null;
    });

    final api = context.read<ApiClient>();

    try {
      final resp = await api.dio.get(ApiEndpoints.pricingPlans);

      if (mounted) {
        setState(() {
          _plans = _parsePlans(resp.data);
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _loading = false);
    }

    try {
      final subsResp = await api.dio.get(ApiEndpoints.subscriptions);
      if (mounted) {
        setState(() {
          _mySubscriptions = _parseSubscriptions(subsResp.data);
          _loadingMySubs = false;
        });
      }
    } on DioException catch (e) {
      if (mounted) {
        setState(() {
          _loadingMySubs = false;
          if ((e.response?.statusCode ?? 0) != 401) {
            _mySubsError = e.response?.data?['message']?.toString() ??
                'Failed to load your subscription status.';
          }
        });
      }
    } catch (_) {
      if (mounted) {
        setState(() {
          _loadingMySubs = false;
          _mySubsError = 'Failed to load your subscription status.';
        });
      }
    }
  }

  List<PricingPlan> _parsePlans(dynamic data) {
    List<dynamic> list;
    if (data is Map && data['data'] != null) {
      list = data['data'] as List? ?? [];
    } else if (data is List) {
      list = data;
    } else {
      list = [];
    }
    return list
        .whereType<Map>()
        .map((e) => PricingPlan.fromJson(Map<String, dynamic>.from(e)))
        .toList();
  }

  List<Map<String, dynamic>> _parseSubscriptions(dynamic data) {
    final dataField = data is Map ? data['data'] : data;
    if (dataField is! List) return [];

    return dataField
        .whereType<Map>()
        .map((item) => Map<String, dynamic>.from(item))
        .toList();
  }

  Future<void> _pauseSubscription(int id) async {
    await _runSubscriptionAction(() => context
        .read<ApiClient>()
        .dio
        .patch('${ApiEndpoints.subscriptions}/$id/pause'));
  }

  Future<void> _resumeSubscription(int id) async {
    await _runSubscriptionAction(() => context
        .read<ApiClient>()
        .dio
        .patch('${ApiEndpoints.subscriptions}/$id/resume'));
  }

  Future<void> _cancelSubscription(int id) async {
    final confirmed = await showDialog<bool>(
          context: context,
          builder: (ctx) => AlertDialog(
            title: const Text('Cancel Subscription?'),
            content: const Text(
              'You can subscribe again anytime. Do you want to cancel this plan now?',
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.of(ctx).pop(false),
                child: const Text('No'),
              ),
              FilledButton(
                onPressed: () => Navigator.of(ctx).pop(true),
                child: const Text('Yes, Cancel'),
              ),
            ],
          ),
        ) ??
        false;

    if (!confirmed) return;

    await _runSubscriptionAction(() => context
        .read<ApiClient>()
        .dio
        .delete('${ApiEndpoints.subscriptions}/$id'));
  }

  Future<void> _runSubscriptionAction(Future<void> Function() action) async {
    setState(() => _actionInProgress = true);
    try {
      await action();
      await _load();
    } on DioException catch (e) {
      if (!mounted) return;
      final message = e.response?.data?['message']?.toString() ??
          'Unable to update subscription right now.';
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(message), behavior: SnackBarBehavior.floating),
      );
    } finally {
      if (mounted) setState(() => _actionInProgress = false);
    }
  }

  Color _statusColor(String status) {
    switch (status.toLowerCase()) {
      case 'active':
        return kMint;
      case 'paused':
        return kYellow;
      case 'cancelled':
      case 'expired':
        return Colors.redAccent;
      default:
        return kNavy;
    }
  }

  String _billingLabel(Map<String, dynamic> sub) {
    final price = (sub['price_per_cycle'] ?? 0).toString();
    final frequency = (sub['frequency'] ?? 'monthly').toString();
    return '₹$price / $frequency';
  }

  Map<String, dynamic>? _latestSubscriptionForPlan(String planName) {
    final normalizedPlan = planName.trim().toLowerCase();
    final matches = _mySubscriptions.where((sub) {
      return (sub['plan_name'] ?? '').toString().trim().toLowerCase() ==
          normalizedPlan;
    }).toList();

    if (matches.isEmpty) return null;

    matches.sort((a, b) {
      final ad = DateTime.tryParse((a['created_at'] ?? '').toString()) ??
          DateTime.fromMillisecondsSinceEpoch(0);
      final bd = DateTime.tryParse((b['created_at'] ?? '').toString()) ??
          DateTime.fromMillisecondsSinceEpoch(0);
      return bd.compareTo(ad);
    });

    return matches.first;
  }

  bool _hasAnotherActivePlan(String currentPlanName) {
    final normalizedCurrent = currentPlanName.trim().toLowerCase();
    return _mySubscriptions.any((sub) {
      final status = (sub['status'] ?? '').toString().toLowerCase();
      final name = (sub['plan_name'] ?? '').toString().trim().toLowerCase();
      return status == 'active' && name != normalizedCurrent;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: kCream,
      body: _loading
          ? const Center(child: CircularProgressIndicator(color: kCoral))
          : CustomScrollView(
              slivers: [
                SliverToBoxAdapter(
                  child: Padding(
                    padding: const EdgeInsets.fromLTRB(20, 20, 20, 0),
                    child: _buildMySubscriptionSection(),
                  ),
                ),

                // Hero Section
                SliverToBoxAdapter(
                  child: Container(
                    padding: const EdgeInsets.fromLTRB(20, 50, 20, 40),
                    decoration: const BoxDecoration(
                      color: Colors.white,
                    ),
                    child: Column(
                      children: [
                        Container(
                          padding: const EdgeInsets.all(20),
                          decoration: BoxDecoration(
                            color: kCoral.withOpacity(0.12),
                            shape: BoxShape.circle,
                          ),
                          child: const FaIcon(
                            FontAwesomeIcons.boxOpen,
                            size: 48,
                            color: kCoral,
                          ),
                        ),
                        const SizedBox(height: 24),
                        Text(
                          'Subscribe & Save',
                          style: GoogleFonts.baloo2(
                            fontSize: 32,
                            fontWeight: FontWeight.w900,
                            color: kNavy,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          'Get fresh baby food delivered to your door\nwith up to 25% savings!',
                          textAlign: TextAlign.center,
                          style: GoogleFonts.poppins(
                            fontSize: 15,
                            color: kNavy.withOpacity(0.7),
                            height: 1.5,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                // Benefits
                SliverToBoxAdapter(
                  child: Padding(
                    padding: const EdgeInsets.all(20),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Why Subscribe?',
                          style: GoogleFonts.baloo2(
                            fontSize: 24,
                            fontWeight: FontWeight.w800,
                            color: kNavy,
                          ),
                        ),
                        const SizedBox(height: 16),
                        _buildBenefit(
                          FontAwesomeIcons.piggyBank,
                          'Save More',
                          'Get up to 25% off on all products',
                          kCoral,
                        ),
                        _buildBenefit(
                          FontAwesomeIcons.truck,
                          'Free Delivery',
                          'Free shipping on all subscription orders',
                          kMint,
                        ),
                        _buildBenefit(
                          FontAwesomeIcons.leaf,
                          'Fresh & Organic',
                          'Delivered fresh to your doorstep monthly',
                          kYellow,
                        ),
                        _buildBenefit(
                          FontAwesomeIcons.clock,
                          'Flexible',
                          'Cancel, pause, or modify anytime',
                          kLavender,
                        ),
                      ],
                    ),
                  ),
                ),

                // Plans Section
                if (_plans.isEmpty)
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.all(40),
                      child: Center(
                        child: Column(
                          children: [
                            Icon(
                              Icons.inbox_outlined,
                              size: 60,
                              color: kNavy.withOpacity(0.3),
                            ),
                            const SizedBox(height: 16),
                            Text(
                              'No subscription plans available',
                              style: GoogleFonts.poppins(
                                fontSize: 16,
                                color: kNavy.withOpacity(0.5),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  )
                else
                  SliverPadding(
                    padding: const EdgeInsets.fromLTRB(20, 0, 20, 24),
                    sliver: SliverList(
                      delegate: SliverChildBuilderDelegate(
                        (context, index) => Padding(
                          padding: const EdgeInsets.only(bottom: 16),
                          child: _buildPlanCard(_plans[index]),
                        ),
                        childCount: _plans.length,
                      ),
                    ),
                  ),

                const SliverToBoxAdapter(child: SizedBox(height: 40)),
              ],
            ),
      bottomNavigationBar: const InnerPageNav(),
    );
  }

  Widget _buildMySubscriptionSection() {
    if (_loadingMySubs) {
      return const Padding(
        padding: EdgeInsets.symmetric(vertical: 20),
        child: Center(child: CircularProgressIndicator(color: kCoral)),
      );
    }

    if (_mySubsError != null) {
      return Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
        ),
        child: Text(
          _mySubsError!,
          style: GoogleFonts.poppins(fontSize: 13, color: kNavy),
        ),
      );
    }

    if (_mySubscriptions.isEmpty) {
      return Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
        ),
        child: Row(
          children: [
            const Icon(Icons.subscriptions_outlined, color: kCoral),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                'No active subscription yet. Pick a plan below to get started.',
                style: GoogleFonts.poppins(fontSize: 13, color: kNavy),
              ),
            ),
          ],
        ),
      );
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'My Subscription',
          style: GoogleFonts.baloo2(
            fontSize: 24,
            fontWeight: FontWeight.w800,
            color: kNavy,
          ),
        ),
        const SizedBox(height: 12),
        ..._mySubscriptions.map((sub) {
          final id = (sub['id'] as num?)?.toInt();
          final subId = id;
          final status = (sub['status'] ?? 'unknown').toString();
          final canPause = status == 'active' && id != null;
          final canResume = status == 'paused' && id != null;
          final canCancel =
              (status == 'active' || status == 'paused') && id != null;

          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFFFD6E5), width: 1.5),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        (sub['plan_name'] ?? 'Subscription Plan').toString(),
                        style: GoogleFonts.poppins(
                          fontSize: 15,
                          fontWeight: FontWeight.w700,
                          color: kNavy,
                        ),
                      ),
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 10, vertical: 5),
                      decoration: BoxDecoration(
                        color: _statusColor(status).withOpacity(0.14),
                        borderRadius: BorderRadius.circular(999),
                      ),
                      child: Text(
                        status.toUpperCase(),
                        style: GoogleFonts.poppins(
                          fontSize: 11,
                          fontWeight: FontWeight.w700,
                          color: _statusColor(status),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                Text(
                  _billingLabel(sub),
                  style: GoogleFonts.poppins(
                      fontSize: 13, color: kNavy.withOpacity(0.7)),
                ),
                if (sub['next_billing_date'] != null) ...[
                  const SizedBox(height: 4),
                  Text(
                    'Next billing: ${sub['next_billing_date']}',
                    style: GoogleFonts.poppins(
                        fontSize: 12, color: kNavy.withOpacity(0.55)),
                  ),
                ],
                if (canPause || canResume || canCancel) ...[
                  const SizedBox(height: 12),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: [
                      if (canPause && subId != null)
                        OutlinedButton(
                          onPressed: _actionInProgress
                              ? null
                              : () => _pauseSubscription(subId),
                          child: const Text('Pause'),
                        ),
                      if (canResume && subId != null)
                        FilledButton(
                          onPressed: _actionInProgress
                              ? null
                              : () => _resumeSubscription(subId),
                          child: const Text('Resume'),
                        ),
                      if (canCancel && subId != null)
                        TextButton(
                          onPressed: _actionInProgress
                              ? null
                              : () => _cancelSubscription(subId),
                          child: const Text('Cancel Plan'),
                        ),
                    ],
                  ),
                ],
              ],
            ),
          );
        }),
      ],
    );
  }

  Widget _buildBenefit(
      IconData icon, String title, String description, Color color) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: color.withOpacity(0.2), width: 1.5),
        boxShadow: [
          BoxShadow(
            color: color.withOpacity(0.08),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: color.withOpacity(0.12),
              borderRadius: BorderRadius.circular(12),
            ),
            child: FaIcon(
              icon,
              size: 24,
              color: color,
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: GoogleFonts.poppins(
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                    color: kNavy,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  description,
                  style: GoogleFonts.poppins(
                    fontSize: 13,
                    color: kNavy.withOpacity(0.6),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPlanCard(PricingPlan plan) {
    final planSub = _latestSubscriptionForPlan(plan.name);
    final planSubId = (planSub?['id'] as num?)?.toInt();
    final planSubStatus = (planSub?['status'] ?? '').toString().toLowerCase();
    final isCurrentActive = planSubStatus == 'active';
    final canResume = planSubStatus == 'paused' && planSubId != null;
    final canRenew =
        (planSubStatus == 'cancelled' || planSubStatus == 'expired') &&
            planSubId != null;
    final showUpgrade = !isCurrentActive &&
        !canResume &&
        !canRenew &&
        _hasAnotherActivePlan(plan.name);

    return Container(
      decoration: BoxDecoration(
        gradient: plan.isPopular
            ? LinearGradient(
                colors: [kCoral, kYellow],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              )
            : null,
        color: plan.isPopular ? null : Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(
          color: plan.isPopular ? Colors.transparent : const Color(0xFFFFD6E5),
          width: 2,
        ),
        boxShadow: plan.isPopular
            ? [
                BoxShadow(
                  color: kCoral.withOpacity(0.3),
                  blurRadius: 20,
                  offset: const Offset(0, 10),
                ),
              ]
            : null,
      ),
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Popular Badge
            if (plan.isPopular)
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Icon(Icons.star, size: 16, color: kCoral),
                    const SizedBox(width: 6),
                    Text(
                      'MOST POPULAR',
                      style: GoogleFonts.poppins(
                        fontSize: 11,
                        fontWeight: FontWeight.w800,
                        color: kCoral,
                      ),
                    ),
                  ],
                ),
              ),
            const SizedBox(height: 16),

            // Plan Name
            Text(
              plan.name,
              style: GoogleFonts.baloo2(
                fontSize: 28,
                fontWeight: FontWeight.w900,
                color: plan.isPopular ? Colors.white : kNavy,
              ),
            ),
            const SizedBox(height: 4),

            // Description
            if (plan.description != null)
              Text(
                plan.description!,
                style: GoogleFonts.poppins(
                  fontSize: 14,
                  color: plan.isPopular
                      ? Colors.white.withOpacity(0.9)
                      : kNavy.withOpacity(0.7),
                  height: 1.5,
                ),
              ),
            const SizedBox(height: 20),

            // Price
            Row(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(
                  '₹${plan.price.toStringAsFixed(0)}',
                  style: GoogleFonts.baloo2(
                    fontSize: 42,
                    fontWeight: FontWeight.w900,
                    color: plan.isPopular ? Colors.white : kCoral,
                    height: 1,
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.only(bottom: 8, left: 4),
                  child: Text(
                    '/${plan.billingCycle ?? 'month'}',
                    style: GoogleFonts.poppins(
                      fontSize: 16,
                      color: plan.isPopular
                          ? Colors.white.withOpacity(0.9)
                          : kNavy.withOpacity(0.6),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),

            // Features
            if (plan.features.isNotEmpty) ...[
              ...plan.features.map((feature) => Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: Row(
                      children: [
                        Icon(
                          Icons.check_circle,
                          size: 20,
                          color: plan.isPopular ? Colors.white : kMint,
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Text(
                            feature,
                            style: GoogleFonts.poppins(
                              fontSize: 14,
                              color: plan.isPopular
                                  ? Colors.white.withOpacity(0.95)
                                  : kNavy.withOpacity(0.8),
                            ),
                          ),
                        ),
                      ],
                    ),
                  )),
              const SizedBox(height: 8),
            ],

            // Subscribe Button
            SizedBox(
              width: double.infinity,
              height: 56,
              child: ElevatedButton(
                onPressed: _actionInProgress
                    ? null
                    : () {
                        if (isCurrentActive) return;

                        if (canResume) {
                          _resumeSubscription(planSubId!);
                          return;
                        }

                        if (canRenew) {
                          _resumeSubscription(planSubId!);
                          return;
                        }

                        Navigator.of(context).push(
                          MaterialPageRoute(
                            builder: (_) =>
                                SubscriptionCheckoutScreen(plan: plan),
                          ),
                        );
                      },
                style: ElevatedButton.styleFrom(
                  backgroundColor: plan.isPopular ? Colors.white : kCoral,
                  foregroundColor: plan.isPopular ? kCoral : Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 0,
                ),
                child: Text(
                  isCurrentActive
                      ? 'Active Plan'
                      : canResume
                          ? 'Resume Plan'
                          : canRenew
                              ? 'Renew Plan'
                              : showUpgrade
                                  ? 'Upgrade Plan'
                                  : 'Subscribe Now',
                  style: GoogleFonts.poppins(
                    fontSize: 16,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ),

            // Products Included
            if (plan.products.isNotEmpty) ...[
              const SizedBox(height: 20),
              const Divider(color: Colors.white24),
              const SizedBox(height: 16),
              Text(
                'Includes ${plan.products.length} Products',
                style: GoogleFonts.poppins(
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  color: plan.isPopular
                      ? Colors.white.withOpacity(0.9)
                      : kNavy.withOpacity(0.7),
                ),
              ),
              const SizedBox(height: 12),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: plan.products.take(5).map((product) {
                  return Container(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: plan.isPopular
                          ? Colors.white.withOpacity(0.2)
                          : kCream,
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(
                        color: plan.isPopular
                            ? Colors.white.withOpacity(0.3)
                            : const Color(0xFFFFD6E5),
                      ),
                    ),
                    child: Text(
                      product.name,
                      style: GoogleFonts.poppins(
                        fontSize: 11,
                        fontWeight: FontWeight.w500,
                        color: plan.isPopular ? Colors.white : kNavy,
                      ),
                    ),
                  );
                }).toList(),
              ),
            ],
          ],
        ),
      ),
    );
  }
}
