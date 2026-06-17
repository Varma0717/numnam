import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:mobile_app/src/config/app_config.dart';
import 'package:mobile_app/src/core/storage_service.dart';

export '../models/feed_log.dart';
export '../models/tracker_data.dart';

class TrackerService {
  static final String _baseUrl = AppConfig.apiBaseUrl;
  static const String _endpoint = '/numnam/tracker/data';
  static final _storage = StorageService();

  /// Get authorization headers with JWT token
  static Future<Map<String, String>> _getHeaders() async {
    final token = await _storage.getToken();
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  /// Fetch tracker data for current user
  static Future<TrackerData> fetchTrackerData() async {
    try {
      final headers = await _getHeaders();
      print('✓ Fetching tracker data from: $_baseUrl$_endpoint');
      print('✓ Headers: $headers');

      final response = await http
          .get(
            Uri.parse('$_baseUrl$_endpoint'),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

      print('✓ Tracker response: ${response.statusCode}');
      print('✓ Response body: ${response.body}');

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        print('DEBUG: Response type: ${json.runtimeType}');
        print('DEBUG: Response keys: ${(json is Map) ? json.keys : 'N/A'}');

        final dataField = json is Map ? json['data'] : null;
        print('DEBUG: Data field type: ${dataField.runtimeType}');
        print('DEBUG: Data field: $dataField');

        try {
          final result = TrackerData.fromJson(dataField ?? json);
          print('✓ Successfully parsed tracker data');
          return result;
        } catch (e) {
          print('ERROR parsing TrackerData: $e');
          throw 'Failed to parse tracker data: $e';
        }
      } else if (response.statusCode == 401) {
        throw 'Unauthorized. Please log in again.';
      } else if (response.statusCode == 422) {
        throw 'Validation error: ${response.body}';
      } else {
        throw 'Failed to load tracker data: ${response.statusCode} - ${response.body}';
      }
    } catch (e) {
      print('✗ Tracker fetch error: $e');
      throw 'Network error: $e';
    }
  }

  /// Save baby age to server
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
          .timeout(const Duration(seconds: 10));

      if (response.statusCode != 200 && response.statusCode != 201) {
        print(
            '✗ Save baby age error: ${response.statusCode} - ${response.body}');
        throw 'Failed to save baby age: ${response.statusCode}';
      }
      print('✓ Baby age saved successfully');
    } catch (e) {
      print('✗ Error saving baby age: $e');
      throw 'Error saving baby age: $e';
    }
  }

  /// Add feeding log entry
  static Future<void> addFeedLog(FeedLogRequest log) async {
    try {
      final headers = await _getHeaders();
      final body = jsonEncode(log.toJson());
      print('✓ Sending feed log: $body');

      final response = await http
          .post(
            Uri.parse('$_baseUrl/numnam/logs'),
            headers: headers,
            body: body,
          )
          .timeout(const Duration(seconds: 10));

      print('✓ Feed log response: ${response.statusCode} - ${response.body}');

      if (response.statusCode != 200 && response.statusCode != 201) {
        throw 'Failed to save log: ${response.statusCode} - ${response.body}';
      }
      print('✓ Feed log saved successfully');
    } catch (e) {
      print('✗ Error saving feed log: $e');
      throw 'Error saving feed log: $e';
    }
  }

  /// Fetch tracker logs for date range
  static Future<List<FeedLog>> fetchLogs({
    required DateTime startDate,
    required DateTime endDate,
  }) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .get(
            Uri.parse('$_baseUrl/numnam/logs').replace(
              queryParameters: {
                'start_date': startDate.toIso8601String().split('T')[0],
                'end_date': endDate.toIso8601String().split('T')[0],
              },
            ),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        final logs = (json['data'] as List?)
                ?.map((e) => FeedLog.fromJson(e as Map<String, dynamic>))
                .toList() ??
            [];
        return logs;
      } else {
        throw 'Failed to fetch logs: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error fetching logs: $e';
    }
  }

  /// Heart/favorite a recipe
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

  /// Fetch all recipes available for baby age
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
        final json = jsonDecode(response.body);
        final recipes = (json['data'] as List?)
                ?.map((e) => Recipe.fromJson(e as Map<String, dynamic>))
                .toList() ??
            [];
        return recipes;
      } else {
        throw 'Failed to fetch recipes: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error fetching recipes: $e';
    }
  }
}

// Models
class TrackerData {
  final int babyAge;
  final List<FeedLog> logs;
  final Set<int> heartedRecipes;

  TrackerData({
    required this.babyAge,
    required this.logs,
    required this.heartedRecipes,
  });

  factory TrackerData.fromJson(Map<String, dynamic> json) {
    return TrackerData(
      babyAge: json['baby_age'] ?? 0,
      logs: (json['logs'] as List?)
              ?.map((e) => FeedLog.fromJson(e as Map<String, dynamic>))
              .toList() ??
          [],
      heartedRecipes: Set<int>.from(
        (json['hearted_recipes'] as List<dynamic>?)?.cast<int>() ?? [],
      ),
    );
  }
}

class FeedLog {
  final int id;
  final String type; // milk, solid, water, poop
  final int volume;
  final String time;
  final String label;
  final DateTime timestamp;
  final String? milkType;
  final String? food;
  final String? poopType;

  FeedLog({
    required this.id,
    required this.type,
    required this.volume,
    required this.time,
    required this.label,
    required this.timestamp,
    this.milkType,
    this.food,
    this.poopType,
  });

  factory FeedLog.fromJson(Map<String, dynamic> json) {
    return FeedLog(
      id: json['id'] ?? 0,
      type: json['type'] ?? '',
      volume: json['volume'] ?? 0,
      time: json['time'] ?? '',
      label: json['label'] ?? '',
      timestamp:
          DateTime.parse(json['timestamp'] ?? DateTime.now().toIso8601String()),
      milkType: json['milk_type'],
      food: json['food'],
      poopType: json['poop_type'],
    );
  }
}

class FeedLogRequest {
  final String type;
  final int volume;
  final String label;
  final String? milkType;
  final String? food;
  final String? poopType;

  FeedLogRequest({
    required this.type,
    required this.volume,
    required this.label,
    this.milkType,
    this.food,
    this.poopType,
  });

  Map<String, dynamic> toJson() {
    return {
      'type': type,
      'volume_ml': volume,
      if (milkType != null) 'milk_type': milkType,
      if (food != null) 'food_name': food,
      if (food != null) 'food_type': 'mixed',
      if (food != null) 'texture': label,
      if (food != null) 'finish_level': 'all',
      if (poopType != null) 'poop_type': poopType,
    };
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

  Recipe({
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
      id: json['id'] ?? 0,
      emoji: json['emoji'] ?? '',
      name: json['name'] ?? '',
      minAge: json['min_age'] ?? 0,
      texture: json['texture'] ?? '',
      hearts: json['hearts'] ?? 0,
      isHearted: json['is_hearted'] ?? false,
    );
  }
}
