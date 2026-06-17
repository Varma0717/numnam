class FeedLog {
  final int id;
  final int userId;
  final String logType; // 'milk', 'food', 'water', 'poop'
  final String? milkType;
  final double? volume;
  final String? foodName;
  final String? poopType;
  final String? notes;
  final DateTime timestamp;

  FeedLog({
    required this.id,
    required this.userId,
    required this.logType,
    this.milkType,
    this.volume,
    this.foodName,
    this.poopType,
    this.notes,
    required this.timestamp,
  });

  factory FeedLog.fromJson(Map<String, dynamic> json) {
    return FeedLog(
      id: json['id'] as int,
      userId: json['user_id'] as int,
      logType: json['log_type'] as String,
      milkType: json['milk_type'] as String?,
      volume:
          json['volume'] != null ? (json['volume'] as num).toDouble() : null,
      foodName: json['food_name'] as String?,
      poopType: json['poop_type'] as String?,
      notes: json['notes'] as String?,
      timestamp: DateTime.parse(json['created_at'] as String),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'log_type': logType,
      'milk_type': milkType,
      'volume': volume,
      'food_name': foodName,
      'poop_type': poopType,
      'notes': notes,
      'created_at': timestamp.toIso8601String(),
    };
  }
}

class FeedLogRequest {
  final String logType;
  final String? milkType;
  final double? volume;
  final String? foodName;
  final String? poopType;
  final String? notes;

  FeedLogRequest({
    required this.logType,
    this.milkType,
    this.volume,
    this.foodName,
    this.poopType,
    this.notes,
  });

  Map<String, dynamic> toJson() {
    return {
      'log_type': logType,
      'milk_type': milkType,
      'volume': volume,
      'food_name': foodName,
      'poop_type': poopType,
      'notes': notes,
    };
  }
}
