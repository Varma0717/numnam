import 'package:flutter_dotenv/flutter_dotenv.dart';

class AppConfig {
  const AppConfig._();

  static const String _defaultApiBase = 'https://numnam.com/api/v1';

  static String? _envValue(String key) {
    try {
      final value = dotenv.env[key];
      if (value != null && value.isNotEmpty) {
        return value;
      }
    } catch (_) {
      // Dotenv may not be initialized in tests; use safe fallbacks.
    }
    return null;
  }

  static String get apiBaseUrl {
    final fromEnv = _envValue('API_BASE_URL');
    if (fromEnv != null) {
      return fromEnv.replaceAll(RegExp(r'/$'), '');
    }
    return _defaultApiBase;
  }

  static String get siteBaseUrl {
    final base = Uri.parse(apiBaseUrl);
    return '${base.scheme}://${base.host}';
  }

  static String get razorpayKeyId {
    return _envValue('RAZORPAY_KEY_ID') ?? '';
  }

  static String get healthEndpoint {
    final baseUri = Uri.parse(apiBaseUrl);
    return baseUri.replace(path: '/api/health').toString();
  }

  static Duration get apiTimeout => const Duration(seconds: 20);

  static String imageUrl(String? path) {
    if (path == null || path.isEmpty) return '';
    if (path.startsWith('http')) return path;
    // Strip leading /storage/ or storage/ to avoid double-pathing
    if (path.startsWith('/storage/')) path = path.substring(9);
    if (path.startsWith('storage/')) path = path.substring(8);
    if (path.startsWith('/')) path = path.substring(1);
    return '$siteBaseUrl/storage/$path';
  }
}
