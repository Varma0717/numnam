class FeedLog {
  final int id;
  final int? userId;
  final String type; // milk, solid, water, poop
  final String? milkType;
  final double? volumeMl;
  final String? foodName;
  final String? foodType;
  final String? texture;
  final String? finishLevel;
  final String? poopType;
  final String? notes;
  final DateTime loggedAt;

  const FeedLog({
    required this.id,
    required this.type,
    required this.loggedAt,
    this.userId,
    this.milkType,
    this.volumeMl,
    this.foodName,
    this.foodType,
    this.texture,
    this.finishLevel,
    this.poopType,
    this.notes,
  });

  int get volume => (volumeMl ?? 0).round();
  DateTime get timestamp => loggedAt;

  String get time {
    final h = loggedAt.hour % 12 == 0 ? 12 : loggedAt.hour % 12;
    final m = loggedAt.minute.toString().padLeft(2, '0');
    final suffix = loggedAt.hour >= 12 ? 'PM' : 'AM';
    return '$h:$m $suffix';
  }

  String get label {
    if (type == 'milk') {
      final milk = (milkType ?? 'Milk').toString();
      return '${_titleCase(milk)} - $volume ml';
    }
    if (type == 'solid') {
      final name = (foodName == null || foodName!.isEmpty) ? 'Solid Food' : foodName!;
      return '$name ($volume ml)';
    }
    if (type == 'water') {
      return 'Water - $volume ml';
    }
    if (type == 'poop') {
      return 'Poop: ${poopType ?? '-'}';
    }
    return type;
  }

  static String _titleCase(String value) {
    if (value.isEmpty) return value;
    final normalized = value.replaceAll('_', ' ').toLowerCase();
    return normalized
        .split(' ')
        .map((w) => w.isEmpty ? w : '${w[0].toUpperCase()}${w.substring(1)}')
        .join(' ');
  }

  static double _toDouble(dynamic value) {
    if (value is double) return value;
    if (value is int) return value.toDouble();
    if (value is String) return double.tryParse(value) ?? 0;
    return 0;
  }

  factory FeedLog.fromJson(Map<String, dynamic> json) {
    final rawType = (json['type'] ?? json['log_type'] ?? '').toString();
    final rawTimestamp =
        json['logged_at'] ?? json['created_at'] ?? DateTime.now().toIso8601String();

    return FeedLog(
      id: (json['id'] as num?)?.toInt() ?? 0,
      userId: (json['user_id'] as num?)?.toInt(),
      type: rawType,
      milkType: json['milk_type']?.toString(),
      volumeMl: json['volume_ml'] != null
          ? _toDouble(json['volume_ml'])
          : (json['volume'] != null ? _toDouble(json['volume']) : null),
      foodName: json['food_name']?.toString(),
      foodType: json['food_type']?.toString(),
      texture: json['texture']?.toString(),
      finishLevel: json['finish_level']?.toString(),
      poopType: json['poop_type']?.toString(),
      notes: json['notes']?.toString(),
      loggedAt: DateTime.tryParse(rawTimestamp.toString()) ?? DateTime.now(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'type': type,
      'milk_type': milkType,
      'volume_ml': volumeMl,
      'food_name': foodName,
      'food_type': foodType,
      'texture': texture,
      'finish_level': finishLevel,
      'poop_type': poopType,
      'notes': notes,
      'logged_at': loggedAt.toIso8601String(),
    };
  }
}

class FeedLogRequest {
  final String type;
  final int? volumeMl;
  final String? milkType;
  final String? foodName;
  final String? foodType;
  final String? texture;
  final String? finishLevel;
  final String? poopType;
  final String? notes;
  final DateTime? loggedAt;

  const FeedLogRequest({
    required this.type,
    this.volumeMl,
    this.milkType,
    this.foodName,
    this.foodType,
    this.texture,
    this.finishLevel,
    this.poopType,
    this.notes,
    this.loggedAt,
  });

  Map<String, dynamic> toJson() {
    final payload = <String, dynamic>{
      'type': type,
      if (volumeMl != null) 'volume_ml': volumeMl,
      if (milkType != null && milkType!.isNotEmpty) 'milk_type': milkType,
      if (foodName != null && foodName!.isNotEmpty) 'food_name': foodName,
      if (foodType != null && foodType!.isNotEmpty) 'food_type': foodType,
      if (texture != null && texture!.isNotEmpty) 'texture': texture,
      if (finishLevel != null && finishLevel!.isNotEmpty) 'finish_level': finishLevel,
      if (poopType != null && poopType!.isNotEmpty) 'poop_type': poopType,
      if (notes != null && notes!.isNotEmpty) 'notes': notes,
      if (loggedAt != null) 'logged_at': loggedAt!.toIso8601String(),
    };

    return payload;
  }
}
