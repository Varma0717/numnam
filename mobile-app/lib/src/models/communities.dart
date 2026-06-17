class Room {
  final int id;
  final String name;
  final String emoji;
  final String? description;
  final int messageCount;
  final DateTime createdAt;

  Room({
    required this.id,
    required this.name,
    required this.emoji,
    this.description,
    required this.messageCount,
    required this.createdAt,
  });

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'] as int,
      name: json['name'] as String,
      emoji: json['emoji'] as String? ?? '💬',
      description: json['description'] as String?,
      messageCount: json['message_count'] as int? ?? 0,
      createdAt: DateTime.parse(json['created_at'] as String),
    );
  }
}

class Message {
  final int id;
  final int roomId;
  final int userId;
  final String userName;
  final String? userAvatar;
  final String content;
  final int likes;
  final bool userLiked;
  final int commentCount;
  final DateTime createdAt;

  Message({
    required this.id,
    required this.roomId,
    required this.userId,
    required this.userName,
    this.userAvatar,
    required this.content,
    required this.likes,
    required this.userLiked,
    required this.commentCount,
    required this.createdAt,
  });

  factory Message.fromJson(Map<String, dynamic> json) {
    return Message(
      id: json['id'] as int,
      roomId: json['room_id'] as int,
      userId: json['user_id'] as int,
      userName: json['user_name'] as String,
      userAvatar: json['user_avatar'] as String?,
      content: json['content'] as String,
      likes: json['likes'] as int? ?? 0,
      userLiked: json['user_liked'] as bool? ?? false,
      commentCount: json['comment_count'] as int? ?? 0,
      createdAt: DateTime.parse(json['created_at'] as String),
    );
  }
}

class Comment {
  final int id;
  final int messageId;
  final int userId;
  final String userName;
  final String? userAvatar;
  final String content;
  final int likes;
  final bool userLiked;
  final DateTime createdAt;

  Comment({
    required this.id,
    required this.messageId,
    required this.userId,
    required this.userName,
    this.userAvatar,
    required this.content,
    required this.likes,
    required this.userLiked,
    required this.createdAt,
  });

  factory Comment.fromJson(Map<String, dynamic> json) {
    return Comment(
      id: json['id'] as int,
      messageId: json['message_id'] as int,
      userId: json['user_id'] as int,
      userName: json['user_name'] as String,
      userAvatar: json['user_avatar'] as String?,
      content: json['content'] as String,
      likes: json['likes'] as int? ?? 0,
      userLiked: json['user_liked'] as bool? ?? false,
      createdAt: DateTime.parse(json['created_at'] as String),
    );
  }
}
