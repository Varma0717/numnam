import 'package:flutter_dotenv/flutter_dotenv.dart';

class AppConfig {
  const AppConfig._();

  static String get apiBaseUrl {
    final fromEnv = dotenv.env['API_BASE_URL'];
    if (fromEnv != null && fromEnv.isNotEmpty) {
      return fromEnv;
    }
    return 'https://numnam.pmratnam.com/api/v1';
  }

  static String get siteBaseUrl {
    final base = Uri.parse(apiBaseUrl);
    return '${base.scheme}://${base.host}';
  }

  static String get razorpayKeyId {
    return dotenv.env['RAZORPAY_KEY_ID'] ?? '';
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
