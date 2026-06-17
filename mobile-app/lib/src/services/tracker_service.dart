import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:addmagpro_mobile/src/config/app_config.dart';

class TrackerService {
  static const String _baseUrl = AppConfig.apiBaseUrl;
  static const String _endpoint = '/api/v1/tracker';

  /// Fetch tracker data for current user
  static Future<TrackerData> fetchTrackerData() async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl$_endpoint'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body) as Map<String, dynamic>;
        return TrackerData.fromJson(json['data'] ?? json);
      } else if (response.statusCode == 401) {
        throw 'Unauthorized. Please log in again.';
      } else {
        throw 'Failed to load tracker data: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Network error: $e';
    }
  }

  /// Save baby age to server
  static Future<void> saveBabyAge(int age) async {
    try {
      final response = await http
          .post(
            Uri.parse('$_baseUrl$_endpoint/baby-age'),
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: jsonEncode({'age': age}),
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode != 200 && response.statusCode != 201) {
        throw 'Failed to save baby age: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error saving baby age: $e';
    }
  }

  /// Add feeding log entry
  static Future<void> addFeedLog(FeedLogRequest log) async {
    try {
      final response = await http
          .post(
            Uri.parse('$_baseUrl$_endpoint/logs'),
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: jsonEncode(log.toJson()),
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode != 200 && response.statusCode != 201) {
        throw 'Failed to save log: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error saving feed log: $e';
    }
  }

  /// Fetch tracker logs for date range
  static Future<List<FeedLog>> fetchLogs({
    required DateTime startDate,
    required DateTime endDate,
  }) async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl$_endpoint/logs').replace(
          queryParameters: {
            'start_date': startDate.toIso8601String().split('T')[0],
            'end_date': endDate.toIso8601String().split('T')[0],
          },
        ),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

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
      final response = await http.post(
        Uri.parse('$_baseUrl$_endpoint/recipes/$recipeId/heart'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

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
      final response = await http.get(
        Uri.parse('$_baseUrl$_endpoint/recipes')
            .replace(queryParameters: {'min_age': babyAge.toString()}),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

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
      'volume': volume,
      'label': label,
      if (milkType != null) 'milk_type': milkType,
      if (food != null) 'food': food,
      if (poopType != null) 'poop_type': poopType,
      'timestamp': DateTime.now().toIso8601String(),
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
