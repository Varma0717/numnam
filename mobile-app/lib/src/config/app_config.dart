import 'package:flutter_dotenv/flutter_dotenv.dart';

class AppConfig {
  const AppConfig._();

  static String get apiBaseUrl {
    final fromEnv = dotenv.env['API_BASE_URL'];
    if (fromEnv != null && fromEnv.isNotEmpty) {
      return fromEnv;
    }
    return 'http://10.0.2.2/numnam-api/public/api/v1';
  }

  static Duration get apiTimeout => const Duration(seconds: 20);
}
