import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile_app/src/config/app_config.dart';
import 'package:mobile_app/src/core/constants.dart';

class TrackerConfigService {
  static const String _endpoint = 'tracker/config';

  /// Fetch tracker configuration from API
  static Future<TrackerConfig> fetchConfig() async {
    try {
      final response = await http.get(
        Uri.parse('${AppConfig.apiBaseUrl}/$_endpoint'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        print('DEBUG: Tracker config response type: ${json.runtimeType}');

        final dataField = json is Map ? json['data'] : null;
        print('DEBUG: Config data field type: ${dataField.runtimeType}');

        if (dataField is! Map<String, dynamic>) {
          print('ERROR: Expected Map for config data');
          throw 'Invalid tracker config response format';
        }

        try {
          final config = TrackerConfig.fromJson(dataField);
          print('✓ Successfully parsed tracker config');
          return config;
        } catch (e) {
          print('ERROR parsing TrackerConfig: $e');
          throw 'Failed to parse tracker config: $e';
        }
      } else {
        throw 'Failed to fetch tracker config: ${response.statusCode}';
      }
    } catch (e) {
      print('ERROR fetching tracker config: $e');
      throw 'Error fetching tracker config: $e';
    }
  }
}

class TrackerConfig {
  final List<MilkType> milkTypes;
  final List<PoopType> poopTypes;
  final List<FeedType> feedTypes;
  final List<Milestone> milestones;
  final List<SafetyRule> safetyRules;

  TrackerConfig({
    required this.milkTypes,
    required this.poopTypes,
    required this.feedTypes,
    required this.milestones,
    required this.safetyRules,
  });

  factory TrackerConfig.fromJson(Map<String, dynamic> json) {
    return TrackerConfig(
      milkTypes: (json['milk_types'] as List?)
              ?.map((e) => MilkType.fromJson(e))
              .toList() ??
          [],
      poopTypes: (json['poop_types'] as List?)
              ?.map((e) => PoopType.fromJson(e))
              .toList() ??
          [],
      feedTypes: (json['feed_types'] as List?)
              ?.map((e) => FeedType.fromJson(e))
              .toList() ??
          [],
      milestones: (json['milestones'] as List?)
              ?.map((e) => Milestone.fromJson(e))
              .toList() ??
          [],
      safetyRules: (json['safety_rules'] as List?)
              ?.map((e) => SafetyRule.fromJson(e))
              .toList() ??
          [],
    );
  }
}

class MilkType {
  final String id;
  final String name;
  final String emoji;
  final String description;
  final int defaultVolume;
  final int minVolume;
  final int maxVolume;

  MilkType({
    required this.id,
    required this.name,
    required this.emoji,
    required this.description,
    required this.defaultVolume,
    required this.minVolume,
    required this.maxVolume,
  });

  factory MilkType.fromJson(Map<String, dynamic> json) {
    return MilkType(
      id: json['id'] ?? '',
      name: json['name'] ?? '',
      emoji: json['emoji'] ?? '🍼',
      description: json['description'] ?? '',
      defaultVolume: json['default_volume'] ?? 180,
      minVolume: json['min_volume'] ?? 0,
      maxVolume: json['max_volume'] ?? 300,
    );
  }
}

class PoopType {
  final String type;
  final String emoji;
  final String appearance;
  final String meaning;
  final String severity;
  final String color;

  PoopType({
    required this.type,
    required this.emoji,
    required this.appearance,
    required this.meaning,
    required this.severity,
    required this.color,
  });

  factory PoopType.fromJson(Map<String, dynamic> json) {
    return PoopType(
      type: json['type'] ?? '',
      emoji: json['emoji'] ?? '💩',
      appearance: json['appearance'] ?? '',
      meaning: json['meaning'] ?? '',
      severity: json['severity'] ?? 'normal',
      color: json['color'] ?? '#ffffff',
    );
  }
}

class FeedType {
  final String id;
  final String label;
  final String emoji;
  final String description;
  final List<String>? typeOptions;
  final String? placeholder;
  final int? defaultVolume;
  final int? minVolume;
  final int? maxVolume;

  FeedType({
    required this.id,
    required this.label,
    required this.emoji,
    required this.description,
    this.typeOptions,
    this.placeholder,
    this.defaultVolume,
    this.minVolume,
    this.maxVolume,
  });

  factory FeedType.fromJson(Map<String, dynamic> json) {
    return FeedType(
      id: json['id'] ?? '',
      label: json['label'] ?? '',
      emoji: json['emoji'] ?? '📝',
      description: json['description'] ?? '',
      typeOptions: (json['type_options'] as List?)?.cast<String>(),
      placeholder: json['placeholder'],
      defaultVolume: json['default_volume'],
      minVolume: json['min_volume'],
      maxVolume: json['max_volume'],
    );
  }
}

class Milestone {
  final int age;
  final String title;
  final String description;

  Milestone({
    required this.age,
    required this.title,
    required this.description,
  });

  factory Milestone.fromJson(Map<String, dynamic> json) {
    return Milestone(
      age: json['age'] ?? 6,
      title: json['title'] ?? '',
      description: json['description'] ?? '',
    );
  }
}

class SafetyRule {
  final String icon;
  final String title;
  final String description;

  SafetyRule({
    required this.icon,
    required this.title,
    required this.description,
  });

  factory SafetyRule.fromJson(Map<String, dynamic> json) {
    return SafetyRule(
      icon: json['icon'] ?? '⚠️',
      title: json['title'] ?? '',
      description: json['description'] ?? '',
    );
  }
}
