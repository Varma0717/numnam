import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:addmagpro_mobile/src/config/app_config.dart';

class CommunitiesService {
  static const String _baseUrl = AppConfig.apiBaseUrl;
  static const String _endpoint = '/api/v1/communities';

  /// Fetch all available community rooms/channels
  static Future<List<Room>> fetchRooms() async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl$_endpoint/rooms'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        final rooms = (json['data'] as List?)
                ?.map((e) => Room.fromJson(e as Map<String, dynamic>))
                .toList() ??
            [];
        return rooms;
      } else if (response.statusCode == 401) {
        throw 'Unauthorized. Please log in again.';
      } else {
        throw 'Failed to load rooms: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Network error: $e';
    }
  }

  /// Fetch messages for a specific room
  static Future<List<Message>> fetchRoomMessages(
    int roomId, {
    int page = 1,
    int perPage = 20,
  }) async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl$_endpoint/rooms/$roomId/messages').replace(
          queryParameters: {
            'page': page.toString(),
            'per_page': perPage.toString(),
          },
        ),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        final messages = (json['data'] as List?)
                ?.map((e) => Message.fromJson(e as Map<String, dynamic>))
                .toList() ??
            [];
        return messages;
      } else {
        throw 'Failed to fetch messages: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error fetching messages: $e';
    }
  }

  /// Post a new message to a room
  static Future<Message> postMessage(int roomId, String content) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl$_endpoint/rooms/$roomId/messages'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({'content': content}),
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200 || response.statusCode == 201) {
        final json = jsonDecode(response.body);
        return Message.fromJson(json['data'] ?? json);
      } else {
        throw 'Failed to post message: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error posting message: $e';
    }
  }

  /// Like/unlike a message
  static Future<void> toggleMessageLike(int messageId) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl$_endpoint/messages/$messageId/like'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode != 200) {
        throw 'Failed to update like: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error liking message: $e';
    }
  }

  /// Post a comment on a message
  static Future<Comment> postComment(int messageId, String content) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl$_endpoint/messages/$messageId/comments'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({'content': content}),
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200 || response.statusCode == 201) {
        final json = jsonDecode(response.body);
        return Comment.fromJson(json['data'] ?? json);
      } else {
        throw 'Failed to post comment: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error posting comment: $e';
    }
  }

  /// Fetch comments for a message
  static Future<List<Comment>> fetchMessageComments(int messageId) async {
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl$_endpoint/messages/$messageId/comments'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        final comments = (json['data'] as List?)
                ?.map((e) => Comment.fromJson(e as Map<String, dynamic>))
                .toList() ??
            [];
        return comments;
      } else {
        throw 'Failed to fetch comments: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error fetching comments: $e';
    }
  }

  /// Like/unlike a comment
  static Future<void> toggleCommentLike(int commentId) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl$_endpoint/comments/$commentId/like'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode != 200) {
        throw 'Failed to update like: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error liking comment: $e';
    }
  }

  /// Join a room as a member
  static Future<void> joinRoom(int roomId) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl$_endpoint/rooms/$roomId/join'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode != 200) {
        throw 'Failed to join room: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error joining room: $e';
    }
  }

  /// Leave a room as a member
  static Future<void> leaveRoom(int roomId) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl$_endpoint/rooms/$roomId/leave'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode != 200) {
        throw 'Failed to leave room: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error leaving room: $e';
    }
  }
}

// Models
class Room {
  final int id;
  final String name;
  final String description;
  final String emoji;
  final int memberCount;
  final int messageCount;
  final bool isMember;
  final DateTime createdAt;

  Room({
    required this.id,
    required this.name,
    required this.description,
    required this.emoji,
    required this.memberCount,
    required this.messageCount,
    required this.isMember,
    required this.createdAt,
  });

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      emoji: json['emoji'] ?? '💬',
      memberCount: json['member_count'] ?? 0,
      messageCount: json['message_count'] ?? 0,
      isMember: json['is_member'] ?? false,
      createdAt: DateTime.parse(
          json['created_at'] ?? DateTime.now().toIso8601String()),
    );
  }
}

class Message {
  final int id;
  final int roomId;
  final String userId;
  final String userName;
  final String userAvatar;
  final String content;
  final int likeCount;
  final bool isLiked;
  final int commentCount;
  final DateTime createdAt;

  Message({
    required this.id,
    required this.roomId,
    required this.userId,
    required this.userName,
    required this.userAvatar,
    required this.content,
    required this.likeCount,
    required this.isLiked,
    required this.commentCount,
    required this.createdAt,
  });

  factory Message.fromJson(Map<String, dynamic> json) {
    return Message(
      id: json['id'] ?? 0,
      roomId: json['room_id'] ?? 0,
      userId: json['user_id'] ?? '',
      userName: json['user_name'] ?? 'Unknown',
      userAvatar: json['user_avatar'] ?? '',
      content: json['content'] ?? '',
      likeCount: json['like_count'] ?? 0,
      isLiked: json['is_liked'] ?? false,
      commentCount: json['comment_count'] ?? 0,
      createdAt: DateTime.parse(
          json['created_at'] ?? DateTime.now().toIso8601String()),
    );
  }
}

class Comment {
  final int id;
  final int messageId;
  final String userId;
  final String userName;
  final String userAvatar;
  final String content;
  final int likeCount;
  final bool isLiked;
  final DateTime createdAt;

  Comment({
    required this.id,
    required this.messageId,
    required this.userId,
    required this.userName,
    required this.userAvatar,
    required this.content,
    required this.likeCount,
    required this.isLiked,
    required this.createdAt,
  });

  factory Comment.fromJson(Map<String, dynamic> json) {
    return Comment(
      id: json['id'] ?? 0,
      messageId: json['message_id'] ?? 0,
      userId: json['user_id'] ?? '',
      userName: json['user_name'] ?? 'Unknown',
      userAvatar: json['user_avatar'] ?? '',
      content: json['content'] ?? '',
      likeCount: json['like_count'] ?? 0,
      isLiked: json['is_liked'] ?? false,
      createdAt: DateTime.parse(
          json['created_at'] ?? DateTime.now().toIso8601String()),
    );
  }
}
