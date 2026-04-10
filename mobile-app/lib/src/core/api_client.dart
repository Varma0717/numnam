import 'package:dio/dio.dart';

import '../config/app_config.dart';
import 'auth_interceptor.dart';
import 'storage_service.dart';

class ApiClient {
  ApiClient({StorageService? storage})
      : dio = Dio(
          BaseOptions(
            baseUrl: AppConfig.apiBaseUrl,
            connectTimeout: AppConfig.apiTimeout,
            receiveTimeout: AppConfig.apiTimeout,
            headers: const {'Accept': 'application/json'},
          ),
        ) {
    final store = storage ?? StorageService();
    dio.interceptors.add(AuthInterceptor(store, dio));
  }

  final Dio dio;
}
