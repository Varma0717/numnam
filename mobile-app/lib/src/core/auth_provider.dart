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
      final resp = await _api.dio.post(ApiEndpoints.login, data: {
        'email': email,
        'password': password,
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
        'avatar': await MultipartFile.fromFile(filePath, filename: 'avatar.jpg'),
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

  String _extractError(DioException e) {
    final data = e.response?.data;
    if (data is Map<String, dynamic>) {
      if (data['message'] != null) return data['message'].toString();
      if (data['errors'] is Map) {
        final errors = data['errors'] as Map;
        final first = errors.values.first;
        if (first is List && first.isNotEmpty) return first.first.toString();
      }
    }
    return 'Something went wrong. Please try again.';
  }
}
