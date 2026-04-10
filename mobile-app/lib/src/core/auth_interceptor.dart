import 'package:dio/dio.dart';
import 'storage_service.dart';
import 'constants.dart';

class AuthInterceptor extends Interceptor {
  final StorageService _storage;
  final Dio _dio;
  bool _isRefreshing = false;

  AuthInterceptor(this._storage, this._dio);

  @override
  void onRequest(
      RequestOptions options, RequestInterceptorHandler handler) async {
    final token = await _storage.getToken();
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    handler.next(options);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    if (err.response?.statusCode == 401 && !_isRefreshing) {
      final token = await _storage.getToken();
      if (token == null) {
        handler.next(err);
        return;
      }
      _isRefreshing = true;
      try {
        final refreshDio = Dio(BaseOptions(
          baseUrl: _dio.options.baseUrl,
          headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer $token',
          },
        ));
        final resp = await refreshDio.post(ApiEndpoints.refreshToken);
        final newToken = resp.data['data']?['access_token'] as String?;
        if (newToken != null) {
          await _storage.saveToken(newToken);
          final opts = err.requestOptions;
          opts.headers['Authorization'] = 'Bearer $newToken';
          final retryResponse = await _dio.fetch(opts);
          _isRefreshing = false;
          handler.resolve(retryResponse);
          return;
        }
      } catch (_) {
        await _storage.clearAll();
      }
      _isRefreshing = false;
    }
    handler.next(err);
  }
}
