import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:mobile_app/src/config/app_config.dart';
import 'package:mobile_app/src/core/storage_service.dart';
import '../models/communities.dart';

export '../models/communities.dart';

class CommunitiesService {
  static final String _baseUrl = AppConfig.apiBaseUrl;
  static const String _endpoint = '/numnam/community';
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

  /// Fetch all available community rooms/channels
  static Future<List<Room>> fetchRooms() async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .get(
            Uri.parse('$_baseUrl$_endpoint/rooms'),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        print('DEBUG: Rooms response type: ${json.runtimeType}');

        final dataField = json is Map ? json['data'] : null;
        print('DEBUG: Data field type: ${dataField.runtimeType}');

        if (dataField is! List) {
          print('WARN: Expected List, got ${dataField.runtimeType}');
          return [];
        }

        final rooms = <Room>[];
        for (int i = 0; i < (dataField as List).length; i++) {
          try {
            final item = dataField[i];
            if (item is! Map<String, dynamic>) {
              print('WARN: Room item $i is not a Map, got ${item.runtimeType}');
              continue;
            }
            rooms.add(Room.fromJson(item as Map<String, dynamic>));
          } catch (e) {
            print('ERROR parsing room $i: $e');
          }
        }
        print('✓ Successfully parsed ${rooms.length} rooms');
        return rooms;
      } else if (response.statusCode == 401) {
        throw 'Unauthorized. Please log in again.';
      } else {
        throw 'Failed to load rooms: ${response.statusCode}';
      }
    } catch (e) {
      print('ERROR fetching rooms: $e');
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
      final headers = await _getHeaders();
      final response = await http
          .get(
            Uri.parse('$_baseUrl$_endpoint/rooms/$roomId/messages').replace(
              queryParameters: {
                'page': page.toString(),
                'per_page': perPage.toString(),
              },
            ),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        print('DEBUG: Messages response type: ${json.runtimeType}');

        final dataField = json is Map ? json['data'] : null;
        print('DEBUG: Data field type: ${dataField.runtimeType}');

        if (dataField is! List) {
          print('WARN: Expected List, got ${dataField.runtimeType}');
          return [];
        }

        final messages = <Message>[];
        for (int i = 0; i < (dataField as List).length; i++) {
          try {
            final item = dataField[i];
            if (item is! Map<String, dynamic>) {
              print(
                  'WARN: Message item $i is not a Map, got ${item.runtimeType}');
              continue;
            }
            messages.add(Message.fromJson(item as Map<String, dynamic>));
          } catch (e) {
            print('ERROR parsing message $i: $e');
          }
        }
        print('✓ Successfully parsed ${messages.length} messages');
        return messages;
      } else {
        throw 'Failed to fetch messages: ${response.statusCode}';
      }
    } catch (e) {
      print('ERROR fetching messages: $e');
      throw 'Error fetching messages: $e';
    }
  }

  /// Post a new message to a room
  static Future<Message> postMessage(int roomId, String content) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .post(
            Uri.parse('$_baseUrl$_endpoint/rooms/$roomId/messages'),
            headers: headers,
            body: jsonEncode({'message': content}),
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode == 200 || response.statusCode == 201) {
        final json = jsonDecode(response.body);
        print('DEBUG: Post message response type: ${json.runtimeType}');

        final dataField = json is Map ? json['data'] : null;
        print('DEBUG: Data field type: ${dataField.runtimeType}');
        print('DEBUG: Data field: $dataField');

        try {
          if (dataField is! Map<String, dynamic>) {
            print('ERROR: Expected Map, got ${dataField.runtimeType}');
            throw 'Invalid message response format';
          }
          final message = Message.fromJson(dataField as Map<String, dynamic>);
          print('✓ Successfully parsed posted message');
          return message;
        } catch (e) {
          print('ERROR parsing Message: $e');
          throw 'Failed to parse message response: $e';
        }
      } else {
        throw 'Failed to post message: ${response.statusCode}';
      }
    } catch (e) {
      print('ERROR posting message: $e');
      throw 'Error posting message: $e';
    }
  }

  /// Like/unlike a message
  static Future<void> toggleMessageLike(int messageId) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .post(
            Uri.parse('$_baseUrl$_endpoint/messages/$messageId/like'),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

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
      final headers = await _getHeaders();
      final response = await http
          .post(
            Uri.parse('$_baseUrl$_endpoint/messages/$messageId/comments'),
            headers: headers,
            body: jsonEncode({'comment': content}),
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode == 200 || response.statusCode == 201) {
        final json = jsonDecode(response.body);
        print('DEBUG: Post comment response type: ${json.runtimeType}');

        final dataField = json is Map ? json['data'] : null;
        print('DEBUG: Data field type: ${dataField.runtimeType}');

        try {
          if (dataField is! Map<String, dynamic>) {
            print('ERROR: Expected Map, got ${dataField.runtimeType}');
            throw 'Invalid comment response format';
          }
          final comment = Comment.fromJson(dataField as Map<String, dynamic>);
          print('✓ Successfully parsed posted comment');
          return comment;
        } catch (e) {
          print('ERROR parsing Comment: $e');
          throw 'Failed to parse comment response: $e';
        }
      } else {
        throw 'Failed to post comment: ${response.statusCode}';
      }
    } catch (e) {
      print('ERROR posting comment: $e');
      throw 'Error posting comment: $e';
    }
  }

  /// Fetch comments for a message
  static Future<List<Comment>> fetchMessageComments(int messageId) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .get(
            Uri.parse('$_baseUrl$_endpoint/messages/$messageId/comments'),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        print('DEBUG: Comments response type: ${json.runtimeType}');

        final dataField = json is Map ? json['data'] : null;
        print('DEBUG: Data field type: ${dataField.runtimeType}');

        if (dataField is! List) {
          print('WARN: Expected List, got ${dataField.runtimeType}');
          return [];
        }

        final comments = <Comment>[];
        for (int i = 0; i < (dataField as List).length; i++) {
          try {
            final item = dataField[i];
            if (item is! Map<String, dynamic>) {
              print(
                  'WARN: Comment item $i is not a Map, got ${item.runtimeType}');
              continue;
            }
            comments.add(Comment.fromJson(item as Map<String, dynamic>));
          } catch (e) {
            print('ERROR parsing comment $i: $e');
          }
        }
        print('✓ Successfully parsed ${comments.length} comments');
        return comments;
      } else {
        throw 'Failed to fetch comments: ${response.statusCode}';
      }
    } catch (e) {
      print('ERROR fetching comments: $e');
      throw 'Error fetching comments: $e';
    }
  }

  /// Like/unlike a comment
  static Future<void> toggleCommentLike(int commentId) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .post(
            Uri.parse('$_baseUrl$_endpoint/comments/$commentId/like'),
            headers: headers,
          )
          .timeout(const Duration(seconds: 10));

      if (response.statusCode != 200) {
        throw 'Failed to update like: ${response.statusCode}';
      }
    } catch (e) {
      throw 'Error liking comment: $e';
    }
  }

  /// Join a room as a member
  static Future<void> joinRoom(int roomId) async {
    // Backend currently has no join/leave endpoints; rooms are open for authenticated posting.
    return;
  }

  /// Leave a room as a member
  static Future<void> leaveRoom(int roomId) async {
    return;
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
    final rawName = (json['name'] ?? '').toString();
    final cleanName = rawName
        .replaceAll(RegExp(r'[^\x00-\x7F]+'), '')
        .replaceAll(RegExp(r'\s+'), ' ')
        .trim();

    return Room(
      id: (json['id'] as num?)?.toInt() ?? 0,
      name: cleanName,
      description: (json['description'] ?? '').toString(),
      emoji: (json['emoji'] ?? json['icon'] ?? '💬').toString(),
      memberCount: (json['member_count'] as num?)?.toInt() ?? 0,
      messageCount: (json['message_count'] as num?)?.toInt() ?? 0,
      isMember: true,
      createdAt: DateTime.tryParse((json['created_at'] ?? '').toString()) ??
          DateTime.now(),
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
    final user = json['user'] as Map<String, dynamic>?;
    return Message(
      id: (json['id'] as num?)?.toInt() ?? 0,
      roomId: (json['room_id'] as num?)?.toInt() ?? 0,
      userId: ((json['user_id'] ?? user?['id']) ?? '').toString(),
      userName: (json['user_name'] ?? user?['name'] ?? 'Unknown').toString(),
      userAvatar: (json['user_avatar'] ?? user?['avatar'] ?? '').toString(),
      content: (json['content'] ?? json['message'] ?? '').toString(),
      likeCount: (json['like_count'] as num?)?.toInt() ??
          (json['likes_count'] as num?)?.toInt() ??
          (json['likes'] as num?)?.toInt() ??
          0,
      isLiked:
          (json['is_liked'] as bool?) ?? (json['user_liked'] as bool?) ?? false,
      commentCount: (json['comment_count'] as num?)?.toInt() ??
          (json['comments_count'] as num?)?.toInt() ??
          0,
      createdAt: DateTime.tryParse((json['created_at'] ?? '').toString()) ??
          DateTime.now(),
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
    final user = json['user'] as Map<String, dynamic>?;
    return Comment(
      id: (json['id'] as num?)?.toInt() ?? 0,
      messageId: (json['message_id'] as num?)?.toInt() ?? 0,
      userId: ((json['user_id'] ?? user?['id']) ?? '').toString(),
      userName: (json['user_name'] ?? user?['name'] ?? 'Unknown').toString(),
      userAvatar: (json['user_avatar'] ?? user?['avatar'] ?? '').toString(),
      content: (json['content'] ?? json['comment'] ?? '').toString(),
      likeCount: (json['like_count'] as num?)?.toInt() ??
          (json['likes_count'] as num?)?.toInt() ??
          0,
      isLiked:
          (json['is_liked'] as bool?) ?? (json['user_liked'] as bool?) ?? false,
      createdAt: DateTime.tryParse((json['created_at'] ?? '').toString()) ??
          DateTime.now(),
    );
  }
}
