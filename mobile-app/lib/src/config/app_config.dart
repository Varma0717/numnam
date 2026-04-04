import 'package:flutter_dotenv/flutter_dotenv.dart';

class AppConfig {
  const AppConfig._();

  static String get apiBaseUrl {
    final fromEnv = dotenv.env['API_BASE_URL'];
    if (fromEnv != null && fromEnv.isNotEmpty) {
      return fromEnv;
    }
    return 'https://numnum.pmratnam.com/api/v1';
  }

  static String get healthEndpoint {
    final baseUri = Uri.parse(apiBaseUrl);
    return baseUri.replace(path: '/api/health').toString();
  }

  static Duration get apiTimeout => const Duration(seconds: 20);
}
