import 'package:flutter/material.dart';
import 'package:mobile_app/src/services/communities_service.dart';
import 'package:mobile_app/src/shared/widgets/inner_page_nav.dart';

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
  CommunityStats? _stats;
  bool _isLoadingRooms = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadRooms();
  }

  IconData _roomIcon(String roomName) {
    final key = roomName.toLowerCase();
    if (key.contains('recipe') || key.contains('food')) return Icons.restaurant;
    if (key.contains('sleep')) return Icons.bedtime_outlined;
    if (key.contains('growth')) return Icons.trending_up;
    if (key.contains('doctor') || key.contains('health')) {
      return Icons.health_and_safety_outlined;
    }
    return Icons.forum_outlined;
  }

  Future<void> _loadRooms() async {
    setState(() {
      _isLoadingRooms = true;
      _error = null;
    });
    try {
      final fetchedRooms = await CommunitiesService.fetchRooms();
      CommunityStats? fetchedStats;
      try {
        fetchedStats = await CommunitiesService.fetchStats();
      } catch (_) {
        fetchedStats = null;
      }
      setState(() {
        rooms = fetchedRooms;
        _stats = fetchedStats;
        _isLoadingRooms = false;
        // Initialize TabController with number of rooms
        if (rooms.isNotEmpty) {
          _tabController = TabController(length: rooms.length, vsync: this);
          _tabController.addListener(() {
            setState(() {});
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
        title: const Text('NumNam Community'),
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
                          Text(
                              'No community rooms available yet. Check back soon!'),
                        ],
                      ),
                    )
                  : Column(
                      children: [
                        Padding(
                          padding: const EdgeInsets.fromLTRB(16, 12, 16, 10),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Join thousands of parents sharing their weaning journey, recipes, tips, and experiences.',
                                style: Theme.of(context).textTheme.bodyMedium,
                              ),
                              const SizedBox(height: 12),
                              Wrap(
                                spacing: 10,
                                runSpacing: 10,
                                children: [
                                  _buildStatCard(
                                    'Active Members',
                                    (_stats?.activeMembers ?? 0).toString(),
                                    Icons.people_outline,
                                  ),
                                  _buildStatCard(
                                    'Discussion Rooms',
                                    (_stats?.activeRooms ?? rooms.length)
                                        .toString(),
                                    Icons.forum_outlined,
                                  ),
                                  _buildStatCard(
                                    'Messages Shared',
                                    (_stats?.totalMessages ??
                                            rooms.fold<int>(
                                                0,
                                                (sum, room) =>
                                                    sum + room.messageCount))
                                        .toString(),
                                    Icons.chat_bubble_outline,
                                  ),
                                ],
                              ),
                              const SizedBox(height: 10),
                              Card(
                                margin: EdgeInsets.zero,
                                child: Padding(
                                  padding: const EdgeInsets.all(12),
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: const [
                                      Text('Community Guidelines',
                                          style: TextStyle(
                                              fontWeight: FontWeight.w700)),
                                      SizedBox(height: 8),
                                      Text('• Be respectful and supportive'),
                                      Text(
                                          '• Share experiences, not medical advice'),
                                      Text('• No commercial promotion'),
                                      Text(
                                          '• Keep discussions focused and kind'),
                                    ],
                                  ),
                                ),
                              ),
                              const SizedBox(height: 10),
                              Card(
                                margin: EdgeInsets.zero,
                                child: const Padding(
                                  padding: EdgeInsets.all(12),
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text('Pro Tip',
                                          style: TextStyle(
                                              fontWeight: FontWeight.w700)),
                                      SizedBox(height: 4),
                                      Text(
                                          'Engage authentically with the community. Your experiences and questions help other parents tremendously. No question is too small!'),
                                    ],
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                        // Room tabs
                        Container(
                          color: Colors.grey[100],
                          child: TabBar(
                            controller: _tabController,
                            isScrollable: true,
                            tabs: [
                              for (final room in rooms)
                                Tab(
                                  child: Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      Icon(_roomIcon(room.name), size: 16),
                                      const SizedBox(width: 4),
                                      Text(room.name),
                                    ],
                                  ),
                                ),
                            ],
                          ),
                        ),
                        // Room content
                        Expanded(
                          child: TabBarView(
                            controller: _tabController,
                            children: [
                              for (final room in rooms)
                                RoomDetailView(room: room),
                            ],
                          ),
                        ),
                      ],
                    ),
      bottomNavigationBar: const InnerPageNav(),
    );
  }

  Widget _buildStatCard(String title, String value, IconData icon) {
    return Container(
      width: 108,
      padding: const EdgeInsets.all(10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey[300]!),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 18, color: Colors.black87),
          const SizedBox(height: 6),
          Text(value,
              style:
                  const TextStyle(fontSize: 16, fontWeight: FontWeight.w700)),
          const SizedBox(height: 2),
          Text(title,
              style: const TextStyle(fontSize: 11, color: Colors.black54)),
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
              widget.room.name,
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
              '${widget.room.memberCount} members',
              style: Theme.of(context).textTheme.bodySmall,
            ),
            const SizedBox(height: 24),
            Text(
              'Connect with thousands of parents, share your weaning journey, and get support from our caring community.',
              textAlign: TextAlign.center,
              style: Theme.of(context).textTheme.bodySmall,
            ),
            const SizedBox(height: 16),
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
              Icon(Icons.forum_outlined, size: 24, color: Colors.grey[800]),
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
                      '${widget.room.memberCount} members',
                      style: Theme.of(context).textTheme.bodySmall,
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        Padding(
          padding: const EdgeInsets.fromLTRB(12, 10, 12, 10),
          child: Column(
            children: [
              Card(
                margin: EdgeInsets.zero,
                child: Padding(
                  padding: const EdgeInsets.all(12),
                  child: Row(
                    children: [
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('Room Statistics',
                                style: TextStyle(fontWeight: FontWeight.w700)),
                            const SizedBox(height: 6),
                            Text(
                              'Total Messages: ${widget.room.messageCount}',
                              style: Theme.of(context).textTheme.bodySmall,
                            ),
                          ],
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          color: Colors.green[100],
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Text(
                          'Active',
                          style: TextStyle(
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                            color: Colors.green[800],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 8),
              Card(
                margin: EdgeInsets.zero,
                child: const Padding(
                  padding: EdgeInsets.all(12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Community Guidelines',
                          style: TextStyle(fontWeight: FontWeight.w700)),
                      SizedBox(height: 8),
                      Text('• Be respectful and kind'),
                      Text('• Share experiences only'),
                      Text('• No commercial content'),
                      Text('• Keep discussions focused'),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 8),
              Card(
                margin: EdgeInsets.zero,
                child: const Padding(
                  padding: EdgeInsets.all(12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Tips',
                          style: TextStyle(fontWeight: FontWeight.w700)),
                      SizedBox(height: 4),
                      Text(
                          'Ask specific questions and share what worked for your baby. Other parents learn best from real experiences!'),
                    ],
                  ),
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
                              Text('No messages yet. Be the first to share!'),
                              SizedBox(height: 8),
                              Text(
                                  'Share your question, experience, or tip with the community...'),
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
                    hintText:
                        'Share your question, experience, or tip with the community...',
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
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => CommentScreen(message: message),
      ),
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
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      Text(
                        _timeAgo(message.createdAt),
                        style: Theme.of(context).textTheme.bodySmall,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            // Message content
            SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: ConstrainedBox(
                constraints: BoxConstraints(
                  maxWidth: MediaQuery.of(context).size.width - 50,
                ),
                child: Text(
                  message.content,
                  softWrap: true,
                  overflow: TextOverflow.ellipsis,
                  maxLines: 3,
                ),
              ),
            ),
            const SizedBox(height: 12),
            // Actions
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: [
                Expanded(
                  child: GestureDetector(
                    onTap: onLikePressed,
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          message.isLiked
                              ? Icons.favorite
                              : Icons.favorite_border,
                          size: 16,
                          color: message.isLiked
                              ? Colors.redAccent
                              : Colors.grey[700],
                        ),
                        const SizedBox(width: 4),
                        Text(
                          message.likeCount.toString(),
                          style: Theme.of(context).textTheme.bodySmall,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
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
                        const Icon(Icons.chat_bubble_outline, size: 15),
                        const SizedBox(width: 4),
                        Text(
                          message.commentCount.toString(),
                          style: Theme.of(context).textTheme.bodySmall,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
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

class CommentScreen extends StatefulWidget {
  final Message message;

  const CommentScreen({
    required this.message,
    super.key,
  });

  @override
  State<CommentScreen> createState() => _CommentScreenState();
}

class _CommentScreenState extends State<CommentScreen> {
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
    return Scaffold(
      appBar: AppBar(
        title: const Text('Comments'),
      ),
      body: Column(
        children: [
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
      bottomNavigationBar: const InnerPageNav(),
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
            crossAxisAlignment: CrossAxisAlignment.start,
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
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    Text(
                      comment.content,
                      style: const TextStyle(fontSize: 12),
                      maxLines: 5,
                      overflow: TextOverflow.ellipsis,
                      softWrap: true,
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
                Icon(
                  comment.isLiked ? Icons.favorite : Icons.favorite_border,
                  size: 14,
                  color: comment.isLiked ? Colors.redAccent : Colors.grey,
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
