import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:mobile_app/src/config/app_config.dart';
import 'package:mobile_app/src/core/storage_service.dart';

import '../models/feed_log.dart';
import '../models/tracker_data.dart';

class TrackerService {
  static final String _baseUrl = AppConfig.apiBaseUrl;
  static final _storage = StorageService();

  static Future<Map<String, String>> _getHeaders() async {
    final token = await _storage.getToken();
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  static Future<TrackerData> fetchTrackerData() async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .get(Uri.parse('$_baseUrl/numnam/tracker/data'), headers: headers)
          .timeout(const Duration(seconds: 12));

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);
        final payload = decoded is Map<String, dynamic>
            ? (decoded['data'] as Map<String, dynamic>? ?? decoded)
            : <String, dynamic>{};
        return TrackerData.fromJson(payload);
      }

      if (response.statusCode == 401) {
        throw 'Unauthorized. Please log in again.';
      }

      throw 'Failed to load tracker data: ${response.statusCode}';
    } catch (e) {
      throw 'Network error: $e';
    }
  }

  static Future<void> saveBabyAge(int age) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .post(
            Uri.parse('$_baseUrl/numnam/baby/profile'),
            headers: headers,
            body: jsonEncode({
              'age_months': age,
              'baby_name': 'My Baby',
              'milk_type': 'breast',
            }),
          )
          .timeout(const Duration(seconds: 12));

      if (response.statusCode != 200 && response.statusCode != 201) {
        throw 'Failed to save baby age: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error saving baby age: $e';
    }
  }

  static Future<void> addFeedLog(FeedLogRequest log) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .post(
            Uri.parse('$_baseUrl/numnam/logs'),
            headers: headers,
            body: jsonEncode(log.toJson()),
          )
          .timeout(const Duration(seconds: 12));

      if (response.statusCode != 200 && response.statusCode != 201) {
        throw 'Failed to save log: ${response.statusCode} - ${response.body}';
      }
    } catch (e) {
      throw 'Error saving feed log: $e';
    }
  }

  static Future<List<FeedLog>> fetchTodayLogs() async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .get(Uri.parse('$_baseUrl/numnam/logs/today'), headers: headers)
          .timeout(const Duration(seconds: 12));

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);
        final list = decoded is Map<String, dynamic>
            ? (decoded['data'] as List<dynamic>? ?? <dynamic>[])
            : <dynamic>[];

        return list
            .whereType<Map<String, dynamic>>()
            .map(FeedLog.fromJson)
            .toList();
      }

      throw 'Failed to fetch logs: ${response.statusCode}';
    } catch (e) {
      throw 'Error fetching logs: $e';
    }
  }

  static Future<void> toggleRecipeHeart(int recipeId) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .post(
            Uri.parse('$_baseUrl/numnam/recipes/$recipeId/like'),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode != 200) {
        throw 'Failed to update recipe: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error updating recipe: $e';
    }
  }

  static Future<List<Recipe>> fetchRecipes(int babyAge) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .get(
            Uri.parse('$_baseUrl/numnam/recipes')
                .replace(queryParameters: {'min_age': babyAge.toString()}),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);
        final list = decoded is Map<String, dynamic>
            ? (decoded['data'] as List<dynamic>? ?? <dynamic>[])
            : <dynamic>[];

        return list
            .whereType<Map<String, dynamic>>()
            .map((e) => Recipe.fromJson(e))
            .toList();
      }

      throw 'Failed to fetch recipes: ${response.statusCode}';
    } catch (e) {
      throw 'Error fetching recipes: $e';
    }
  }
}

class Recipe {
  final int id;
  final String emoji;
  final String name;
  final int minAge;
  final String texture;
  final int hearts;
  final bool isHearted;

  const Recipe({
    required this.id,
    required this.emoji,
    required this.name,
    required this.minAge,
    required this.texture,
    required this.hearts,
    required this.isHearted,
  });

  factory Recipe.fromJson(Map<String, dynamic> json) {
    return Recipe(
      id: (json['id'] as num?)?.toInt() ?? 0,
      emoji: (json['emoji'] ?? '??').toString(),
      name: (json['name'] ?? '').toString(),
      minAge: (json['min_age_months'] as num?)?.toInt() ?? 0,
      texture: (json['texture'] ?? '').toString(),
      hearts: (json['hearts_count'] as num?)?.toInt() ?? 0,
      isHearted: (json['is_hearted'] as bool?) ?? false,
    );
  }
}
