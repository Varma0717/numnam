import 'package:flutter/foundation.dart';
import 'package:dio/dio.dart';
import '../models/user.dart';
import 'api_client.dart';
import 'constants.dart';
import 'storage_service.dart';

class AuthProvider extends ChangeNotifier {
  final ApiClient _api;
  final StorageService _storage;

  User? _user;
  bool _isLoading = false;
  String? _error;

  User? get user => _user;
  bool get isAuthenticated => _user != null;
  bool get isLoading => _isLoading;
  String? get error => _error;

  AuthProvider(this._api, this._storage);

  void clearError() {
    _error = null;
    notifyListeners();
  }

  Future<void> loadStoredAuth() async {
    _isLoading = true;
    notifyListeners();
    try {
      final token = await _storage.getToken();
      if (token == null) {
        _isLoading = false;
        notifyListeners();
        return;
      }
      final resp = await _api.dio.get(ApiEndpoints.me);
      final data = resp.data['data'] as Map<String, dynamic>?;
      if (data != null) {
        _user = User.fromJson(data);
        await _storage.saveUser(data);
      }
    } on DioException {
      await _storage.clearAll();
      _user = null;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      debugPrint('🔐 Attempting login for: $email');
      debugPrint('📡 API Endpoint: ${ApiEndpoints.login}');

      final resp = await _api.dio.post(ApiEndpoints.login, data: {
        'email': email,
        'password': password,
      });

      debugPrint('✅ Login response received');
      debugPrint('Response status: ${resp.statusCode}');
      debugPrint('Response data keys: ${resp.data?.keys}');

      final data = resp.data['data'] as Map<String, dynamic>;
      final token = data['access_token'] as String;
      await _storage.saveToken(token);
      _user = User.fromJson(data['user'] as Map<String, dynamic>);
      await _storage.saveUser(data['user'] as Map<String, dynamic>);

      debugPrint('✅ Login successful for: ${_user?.name}');

      _isLoading = false;
      notifyListeners();
      return true;
    } on DioException catch (e) {
      debugPrint('❌ Login DioException occurred');
      debugPrint('Error type: ${e.type}');
      debugPrint('Error message: ${e.message}');
      debugPrint('Response status: ${e.response?.statusCode}');
      debugPrint('Response data: ${e.response?.data}');

      _error = _extractError(e);
      debugPrint('Extracted error message: $_error');

      _isLoading = false;
      notifyListeners();
      return false;
    } catch (e) {
      debugPrint('❌ Unexpected error during login: $e');
      _error = 'An unexpected error occurred. Please try again.';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> register(
      String name, String email, String password, String confirm) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final resp = await _api.dio.post(ApiEndpoints.register, data: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': confirm,
      });
      final data = resp.data['data'] as Map<String, dynamic>;
      final token = data['access_token'] as String;
      await _storage.saveToken(token);
      _user = User.fromJson(data['user'] as Map<String, dynamic>);
      await _storage.saveUser(data['user'] as Map<String, dynamic>);
      _isLoading = false;
      notifyListeners();
      return true;
    } on DioException catch (e) {
      _error = _extractError(e);
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> updateProfile(Map<String, dynamic> fields) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final resp = await _api.dio.patch(ApiEndpoints.me, data: fields);
      final data = resp.data['data'] as Map<String, dynamic>;
      _user = User.fromJson(data);
      await _storage.saveUser(data);
    } on DioException catch (e) {
      _error = _extractError(e);
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> uploadAvatar(String filePath) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final formData = FormData.fromMap({
        'avatar':
            await MultipartFile.fromFile(filePath, filename: 'avatar.jpg'),
      });
      final resp = await _api.dio.post(ApiEndpoints.avatar, data: formData);
      final data = resp.data['data'] as Map<String, dynamic>;
      _user = User.fromJson(data);
      await _storage.saveUser(data);
      _isLoading = false;
      notifyListeners();
      return true;
    } on DioException catch (e) {
      _error = _extractError(e);
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> changePassword(
      String currentPassword, String newPassword, String confirm) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      await _api.dio.post(ApiEndpoints.changePassword, data: {
        'current_password': currentPassword,
        'password': newPassword,
        'password_confirmation': confirm,
      });
      _isLoading = false;
      notifyListeners();
      return true;
    } on DioException catch (e) {
      _error = _extractError(e);
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    await _storage.clearAll();
    _user = null;
    notifyListeners();
  }

  Future<bool> forgotPassword(String email) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      await _api.dio.post(ApiEndpoints.forgotPassword, data: {
        'email': email,
      });
      _isLoading = false;
      notifyListeners();
      return true;
    } on DioException catch (e) {
      _error = _extractError(e);
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> resetPassword(
      String email, String code, String password, String confirm) async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      final resp = await _api.dio.post(ApiEndpoints.resetPassword, data: {
        'email': email,
        'code': code,
        'password': password,
        'password_confirmation': confirm,
      });
      final data = resp.data['data'] as Map<String, dynamic>;
      final token = data['access_token'] as String;
      await _storage.saveToken(token);
      _user = User.fromJson(data['user'] as Map<String, dynamic>);
      await _storage.saveUser(data['user'] as Map<String, dynamic>);
      _isLoading = false;
      notifyListeners();
      return true;
    } on DioException catch (e) {
      _error = _extractError(e);
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  String _extractError(DioException e) {
    debugPrint('🔍 Extracting error details...');

    // Network/connectivity errors
    if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.sendTimeout ||
        e.type == DioExceptionType.receiveTimeout) {
      debugPrint('⏱️ Timeout error');
      return 'Connection timeout. Please check your internet connection.';
    }

    if (e.type == DioExceptionType.connectionError) {
      debugPrint('🌐 Connection error - cannot reach server');
      return 'Unable to connect to server. Please check your internet connection.';
    }

    final data = e.response?.data;
    debugPrint('Response data type: ${data.runtimeType}');
    debugPrint('Response data: $data');

    if (data is Map<String, dynamic>) {
      // Check for 'message' field
      if (data['message'] != null) {
        debugPrint('Found message field: ${data['message']}');
        return data['message'].toString();
      }

      // Check for 'errors' field (Laravel validation errors)
      if (data['errors'] is Map) {
        final errors = data['errors'] as Map;
        debugPrint('Found errors field: $errors');
        if (errors.isNotEmpty) {
          final first = errors.values.first;
          if (first is List && first.isNotEmpty) {
            debugPrint('Returning first validation error: ${first.first}');
            return first.first.toString();
          }
          debugPrint('Returning first error as string: $first');
          return first.toString();
        }
      }
    }

    // If we have a response code, include it in the error
    final statusCode = e.response?.statusCode;
    if (statusCode != null) {
      debugPrint('⚠️ HTTP $statusCode error with no parseable message');
      return 'Server error ($statusCode). Please try again.';
    }

    debugPrint('⚠️ Could not extract specific error, using generic message');
    return 'Something went wrong. Please try again.';
  }
}
