import 'package:flutter/material.dart';

class ToolsListScreen extends StatelessWidget {
  static const routeName = '/tools';

  const ToolsListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final tools = [
      {
        'id': 'numnam-tracker',
        'name': 'NumNam Weaning Tracker',
        'description':
            'Track your baby\'s feeding journey with personalized insights, recipes, and developmental guidance.',
        'icon': '🍼',
        'screen': const NumNamTrackerScreen(),
      },
    ];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Tools'),
        elevation: 0,
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(20),
        itemCount: tools.length,
        itemBuilder: (context, index) {
          final tool = tools[index];
          return GestureDetector(
            onTap: () {
              Navigator.of(context).push(
                MaterialPageRoute(builder: (_) => tool['screen'] as Widget),
              );
            },
            child: Card(
              margin: const EdgeInsets.only(bottom: 16),
              elevation: 0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
                side: const BorderSide(color: Color(0xFFFFD6E5), width: 1.5),
              ),
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      tool['icon'],
                      style: const TextStyle(fontSize: 40),
                    ),
                    const SizedBox(height: 12),
                    Text(
                      tool['name'],
                      style: Theme.of(context).textTheme.headlineSmall,
                    ),
                    const SizedBox(height: 8),
                    Text(
                      tool['description'],
                      style: Theme.of(context).textTheme.bodyMedium,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 12),
                    Align(
                      alignment: Alignment.bottomRight,
                      child: Icon(
                        Icons.arrow_forward_rounded,
                        color: Theme.of(context).colorScheme.primary,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}

// Import the tracker screen
import 'numnam_tracker_screen.dart';
