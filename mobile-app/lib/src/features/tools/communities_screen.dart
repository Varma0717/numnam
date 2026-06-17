import 'package:flutter/material.dart';
import 'package:addmagpro_mobile/src/services/communities_service.dart';

class CommunitiesScreen extends StatefulWidget {
  static const routeName = '/tools/communities';

  const CommunitiesScreen({super.key});

  @override
  State<CommunitiesScreen> createState() => _CommunitiesScreenState();
}

class _CommunitiesScreenState extends State<CommunitiesScreen>
    with TickerProviderStateMixin {
  late TabController _tabController;
  List<Room> rooms = [];
  bool _isLoadingRooms = true;
  String? _error;
  int _selectedRoomIndex = 0;

  @override
  void initState() {
    super.initState();
    _loadRooms();
  }

  Future<void> _loadRooms() async {
    setState(() {
      _isLoadingRooms = true;
      _error = null;
    });
    try {
      final fetchedRooms = await CommunitiesService.fetchRooms();
      setState(() {
        rooms = fetchedRooms;
        _isLoadingRooms = false;
        // Initialize TabController with number of rooms
        if (rooms.isNotEmpty) {
          _tabController = TabController(length: rooms.length, vsync: this);
          _tabController.addListener(() {
            setState(() => _selectedRoomIndex = _tabController.index);
          });
        }
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
        _isLoadingRooms = false;
      });
    }
  }

  @override
  void dispose() {
    if (rooms.isNotEmpty) {
      _tabController.dispose();
    }
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('NumNam Communities'),
        elevation: 0,
      ),
      body: _isLoadingRooms
          ? const Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  CircularProgressIndicator(),
                  SizedBox(height: 16),
                  Text('Loading communities...'),
                ],
              ),
            )
          : _error != null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(Icons.error_outline,
                          size: 48, color: Colors.red),
                      const SizedBox(height: 16),
                      Text('Error: $_error'),
                      const SizedBox(height: 16),
                      ElevatedButton(
                        onPressed: _loadRooms,
                        child: const Text('Retry'),
                      ),
                    ],
                  ),
                )
              : rooms.isEmpty
                  ? const Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.people_outline,
                              size: 48, color: Colors.grey),
                          SizedBox(height: 16),
                          Text('No communities available yet'),
                        ],
                      ),
                    )
                  : Column(
                      children: [
                        // Room tabs
                        Container(
                          color: Colors.grey[100],
                          child: TabBar(
                            controller: _tabController,
                            isScrollable: true,
                            tabs: rooms
                                .map((room) => Tab(
                                      child: Row(
                                        mainAxisSize: MainAxisSize.min,
                                        children: [
                                          Text(room.emoji,
                                              style: const TextStyle(
                                                  fontSize: 16)),
                                          const SizedBox(width: 4),
                                          Text(room.name),
                                        ],
                                      ),
                                    ))
                                .toList(),
                          ),
                        ),
                        // Room content
                        Expanded(
                          child: TabBarView(
                            controller: _tabController,
                            children: rooms
                                .map((room) => RoomDetailView(room: room))
                                .toList(),
                          ),
                        ),
                      ],
                    ),
    );
  }
}

class RoomDetailView extends StatefulWidget {
  final Room room;

  const RoomDetailView({
    required this.room,
    super.key,
  });

  @override
  State<RoomDetailView> createState() => _RoomDetailViewState();
}

class _RoomDetailViewState extends State<RoomDetailView> {
  late List<Message> messages = [];
  bool _isLoading = true;
  String? _error;
  bool _isMember = false;
  final TextEditingController _messageController = TextEditingController();
  bool _isSendingMessage = false;

  @override
  void initState() {
    super.initState();
    _isMember = widget.room.isMember;
    if (_isMember) {
      _loadMessages();
    }
  }

  Future<void> _loadMessages() async {
    if (!_isMember) return;

    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final fetchedMessages =
          await CommunitiesService.fetchRoomMessages(widget.room.id);
      setState(() {
        messages = fetchedMessages;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
        _isLoading = false;
      });
    }
  }

  Future<void> _joinRoom() async {
    try {
      await CommunitiesService.joinRoom(widget.room.id);
      setState(() {
        _isMember = true;
      });
      _loadMessages();
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('✓ You joined the community!'),
            duration: Duration(seconds: 2),
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  Future<void> _sendMessage() async {
    if (_messageController.text.isEmpty) return;

    final content = _messageController.text;
    _messageController.clear();

    setState(() {
      _isSendingMessage = true;
    });

    try {
      final newMessage =
          await CommunitiesService.postMessage(widget.room.id, content);
      setState(() {
        messages.insert(0, newMessage);
        _isSendingMessage = false;
      });
    } catch (e) {
      setState(() {
        _isSendingMessage = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  void dispose() {
    _messageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (!_isMember) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.lock_outline, size: 48, color: Colors.grey),
            const SizedBox(height: 16),
            Text(
              '${widget.room.emoji} ${widget.room.name}',
              style: Theme.of(context).textTheme.titleLarge,
            ),
            const SizedBox(height: 8),
            Text(
              widget.room.description,
              textAlign: TextAlign.center,
              style: Theme.of(context).textTheme.bodyMedium,
            ),
            const SizedBox(height: 16),
            Text(
              '👥 ${widget.room.memberCount} members',
              style: Theme.of(context).textTheme.bodySmall,
            ),
            const SizedBox(height: 24),
            ElevatedButton.icon(
              onPressed: _joinRoom,
              icon: const Icon(Icons.check),
              label: const Text('Join Community'),
            ),
          ],
        ),
      );
    }

    return Column(
      children: [
        // Room header
        Container(
          padding: const EdgeInsets.all(16),
          color: Colors.grey[100],
          child: Row(
            children: [
              Text(widget.room.emoji, style: const TextStyle(fontSize: 24)),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      widget.room.name,
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    Text(
                      '👥 ${widget.room.memberCount} members',
                      style: Theme.of(context).textTheme.bodySmall,
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        // Messages
        Expanded(
          child: _isLoading
              ? const Center(child: CircularProgressIndicator())
              : _error != null
                  ? Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(Icons.error_outline,
                              size: 48, color: Colors.red),
                          const SizedBox(height: 16),
                          Text('Error: $_error'),
                          const SizedBox(height: 16),
                          ElevatedButton(
                            onPressed: _loadMessages,
                            child: const Text('Retry'),
                          ),
                        ],
                      ),
                    )
                  : messages.isEmpty
                      ? const Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.mail_outline,
                                  size: 48, color: Colors.grey),
                              SizedBox(height: 16),
                              Text('No messages yet'),
                              SizedBox(height: 8),
                              Text('Be the first to say hello! 👋'),
                            ],
                          ),
                        )
                      : ListView.builder(
                          reverse: true,
                          padding: const EdgeInsets.all(12),
                          itemCount: messages.length,
                          itemBuilder: (context, index) {
                            final message = messages[index];
                            return MessageCard(
                              message: message,
                              onLikePressed: () async {
                                try {
                                  await CommunitiesService.toggleMessageLike(
                                      message.id);
                                  setState(() {
                                    messages[index] = Message(
                                      id: message.id,
                                      roomId: message.roomId,
                                      userId: message.userId,
                                      userName: message.userName,
                                      userAvatar: message.userAvatar,
                                      content: message.content,
                                      likeCount: message.isLiked
                                          ? message.likeCount - 1
                                          : message.likeCount + 1,
                                      isLiked: !message.isLiked,
                                      commentCount: message.commentCount,
                                      createdAt: message.createdAt,
                                    );
                                  });
                                } catch (e) {
                                  ScaffoldMessenger.of(context).showSnackBar(
                                    SnackBar(
                                      content: Text('Error: $e'),
                                      backgroundColor: Colors.red,
                                    ),
                                  );
                                }
                              },
                              onCommentPressed: () {
                                _showCommentSheet(message);
                              },
                            );
                          },
                        ),
        ),
        // Message input
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            border: Border(top: BorderSide(color: Colors.grey[300]!)),
          ),
          child: Row(
            children: [
              Expanded(
                child: TextField(
                  controller: _messageController,
                  decoration: InputDecoration(
                    hintText: 'Say something... 💬',
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(20),
                    ),
                    contentPadding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 12,
                    ),
                  ),
                  maxLines: null,
                  textInputAction: TextInputAction.send,
                  onSubmitted: (_) => _sendMessage(),
                ),
              ),
              const SizedBox(width: 8),
              CircleAvatar(
                backgroundColor: Colors.blue,
                child: _isSendingMessage
                    ? const SizedBox(
                        width: 24,
                        height: 24,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          valueColor:
                              AlwaysStoppedAnimation<Color>(Colors.white),
                        ),
                      )
                    : IconButton(
                        onPressed: _sendMessage,
                        icon: const Icon(Icons.send, color: Colors.white),
                      ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  void _showCommentSheet(Message message) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (context) => CommentSheetView(message: message),
    );
  }
}

class MessageCard extends StatelessWidget {
  final Message message;
  final VoidCallback onLikePressed;
  final VoidCallback onCommentPressed;

  const MessageCard({
    required this.message,
    required this.onLikePressed,
    required this.onCommentPressed,
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // User info
            Row(
              children: [
                CircleAvatar(
                  radius: 20,
                  backgroundImage: message.userAvatar.isNotEmpty
                      ? NetworkImage(message.userAvatar)
                      : null,
                  child: message.userAvatar.isEmpty
                      ? Text(message.userName[0].toUpperCase())
                      : null,
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        message.userName,
                        style: const TextStyle(
                          fontWeight: FontWeight.w600,
                          fontSize: 12,
                        ),
                      ),
                      Text(
                        _timeAgo(message.createdAt),
                        style: Theme.of(context).textTheme.bodySmall,
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            // Message content
            Text(message.content),
            const SizedBox(height: 12),
            // Actions
            Row(
              children: [
                Expanded(
                  child: GestureDetector(
                    onTap: onLikePressed,
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(
                          message.isLiked ? '❤️' : '🤍',
                          style: const TextStyle(fontSize: 14),
                        ),
                        const SizedBox(width: 4),
                        Text(
                          message.likeCount.toString(),
                          style: Theme.of(context).textTheme.bodySmall,
                        ),
                      ],
                    ),
                  ),
                ),
                Expanded(
                  child: GestureDetector(
                    onTap: onCommentPressed,
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Text('💬', style: TextStyle(fontSize: 14)),
                        const SizedBox(width: 4),
                        Text(
                          message.commentCount.toString(),
                          style: Theme.of(context).textTheme.bodySmall,
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  String _timeAgo(DateTime dateTime) {
    final now = DateTime.now();
    final diff = now.difference(dateTime);

    if (diff.inSeconds < 60) {
      return 'now';
    } else if (diff.inMinutes < 60) {
      return '${diff.inMinutes}m ago';
    } else if (diff.inHours < 24) {
      return '${diff.inHours}h ago';
    } else if (diff.inDays < 7) {
      return '${diff.inDays}d ago';
    } else {
      return '${dateTime.month}/${dateTime.day}';
    }
  }
}

class CommentSheetView extends StatefulWidget {
  final Message message;

  const CommentSheetView({
    required this.message,
    super.key,
  });

  @override
  State<CommentSheetView> createState() => _CommentSheetViewState();
}

class _CommentSheetViewState extends State<CommentSheetView> {
  late List<Comment> comments = [];
  bool _isLoading = true;
  String? _error;
  final TextEditingController _commentController = TextEditingController();
  bool _isPostingComment = false;

  @override
  void initState() {
    super.initState();
    _loadComments();
  }

  Future<void> _loadComments() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final fetchedComments =
          await CommunitiesService.fetchMessageComments(widget.message.id);
      setState(() {
        comments = fetchedComments;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
        _isLoading = false;
      });
    }
  }

  Future<void> _postComment() async {
    if (_commentController.text.isEmpty) return;

    final content = _commentController.text;
    _commentController.clear();

    setState(() {
      _isPostingComment = true;
    });

    try {
      final newComment =
          await CommunitiesService.postComment(widget.message.id, content);
      setState(() {
        comments.insert(0, newComment);
        _isPostingComment = false;
      });
    } catch (e) {
      setState(() {
        _isPostingComment = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  void dispose() {
    _commentController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return DraggableScrollableSheet(
      initialChildSize: 0.7,
      minChildSize: 0.5,
      maxChildSize: 0.95,
      builder: (context, scrollController) => Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              border: Border(bottom: BorderSide(color: Colors.grey[300]!)),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'Comments',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                IconButton(
                  onPressed: () => Navigator.pop(context),
                  icon: const Icon(Icons.close),
                ),
              ],
            ),
          ),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _error != null
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(Icons.error_outline,
                                size: 48, color: Colors.red),
                            const SizedBox(height: 16),
                            Text('Error: $_error'),
                            const SizedBox(height: 16),
                            ElevatedButton(
                              onPressed: _loadComments,
                              child: const Text('Retry'),
                            ),
                          ],
                        ),
                      )
                    : comments.isEmpty
                        ? const Center(
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(Icons.mail_outline,
                                    size: 48, color: Colors.grey),
                                SizedBox(height: 16),
                                Text('No comments yet'),
                              ],
                            ),
                          )
                        : ListView.builder(
                            controller: scrollController,
                            padding: const EdgeInsets.all(12),
                            itemCount: comments.length,
                            itemBuilder: (context, index) {
                              final comment = comments[index];
                              return CommentCard(
                                comment: comment,
                                onLikePressed: () async {
                                  try {
                                    await CommunitiesService.toggleCommentLike(
                                        comment.id);
                                    setState(() {
                                      comments[index] = Comment(
                                        id: comment.id,
                                        messageId: comment.messageId,
                                        userId: comment.userId,
                                        userName: comment.userName,
                                        userAvatar: comment.userAvatar,
                                        content: comment.content,
                                        likeCount: comment.isLiked
                                            ? comment.likeCount - 1
                                            : comment.likeCount + 1,
                                        isLiked: !comment.isLiked,
                                        createdAt: comment.createdAt,
                                      );
                                    });
                                  } catch (e) {
                                    ScaffoldMessenger.of(context).showSnackBar(
                                      SnackBar(
                                        content: Text('Error: $e'),
                                        backgroundColor: Colors.red,
                                      ),
                                    );
                                  }
                                },
                              );
                            },
                          ),
          ),
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              border: Border(top: BorderSide(color: Colors.grey[300]!)),
            ),
            child: Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _commentController,
                    decoration: InputDecoration(
                      hintText: 'Add a comment...',
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(20),
                      ),
                      contentPadding: const EdgeInsets.symmetric(
                        horizontal: 16,
                        vertical: 10,
                      ),
                    ),
                    maxLines: null,
                    textInputAction: TextInputAction.send,
                    onSubmitted: (_) => _postComment(),
                  ),
                ),
                const SizedBox(width: 8),
                CircleAvatar(
                  backgroundColor: Colors.blue,
                  radius: 20,
                  child: _isPostingComment
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            valueColor:
                                AlwaysStoppedAnimation<Color>(Colors.white),
                          ),
                        )
                      : IconButton(
                          onPressed: _postComment,
                          icon: const Icon(Icons.send,
                              color: Colors.white, size: 18),
                        ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class CommentCard extends StatelessWidget {
  final Comment comment;
  final VoidCallback onLikePressed;

  const CommentCard({
    required this.comment,
    required this.onLikePressed,
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              CircleAvatar(
                radius: 16,
                backgroundImage: comment.userAvatar.isNotEmpty
                    ? NetworkImage(comment.userAvatar)
                    : null,
                child: comment.userAvatar.isEmpty
                    ? Text(comment.userName[0].toUpperCase(),
                        style: const TextStyle(fontSize: 10))
                    : null,
              ),
              const SizedBox(width: 8),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      comment.userName,
                      style: const TextStyle(
                        fontWeight: FontWeight.w600,
                        fontSize: 11,
                      ),
                    ),
                    Text(
                      comment.content,
                      style: const TextStyle(fontSize: 12),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 6),
          GestureDetector(
            onTap: onLikePressed,
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  comment.isLiked ? '❤️' : '🤍',
                  style: const TextStyle(fontSize: 12),
                ),
                const SizedBox(width: 4),
                Text(
                  comment.likeCount.toString(),
                  style: const TextStyle(fontSize: 10, color: Colors.grey),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
