import 'feed_log.dart';

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
    final logsData = json['logs'] as List?;
    final logs = logsData
            ?.map((e) => FeedLog.fromJson(e as Map<String, dynamic>))
            .toList() ??
        [];

    final heartedRecipesData = json['hearted_recipes'] as List?;
    final heartedRecipes = (heartedRecipesData ?? []).cast<int>().toSet();

    return TrackerData(
      babyAge: json['baby_age'] as int? ?? 0,
      logs: logs,
      heartedRecipes: heartedRecipes,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'baby_age': babyAge,
      'logs': logs.map((e) => e.toJson()).toList(),
      'hearted_recipes': heartedRecipes.toList(),
    };
  }
}
