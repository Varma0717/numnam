import 'package:dio/dio.dart';

import '../config/app_config.dart';

class ApiClient {
  ApiClient()
      : dio = Dio(
          BaseOptions(
            baseUrl: AppConfig.apiBaseUrl,
            connectTimeout: AppConfig.apiTimeout,
            receiveTimeout: AppConfig.apiTimeout,
            headers: const {'Accept': 'application/json'},
          ),
        );

  final Dio dio;
}
