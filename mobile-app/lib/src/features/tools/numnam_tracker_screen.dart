import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class NumNamTrackerScreen extends StatefulWidget {
  static const routeName = '/tools/numnam-tracker';

  const NumNamTrackerScreen({super.key});

  @override
  State<NumNamTrackerScreen> createState() => _NumNamTrackerScreenState();
}

class _NumNamTrackerScreenState extends State<NumNamTrackerScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  int babyAge = 8;
  List<FeedEntry> logs = [];
  Set<int> heartedRecipes = {};
  int _currentTabIndex = 0;

  // Form controllers
  late TextEditingController _milkTypeController;
  late TextEditingController _milkVolumeController;
  late TextEditingController _solidFoodController;
  late TextEditingController _waterVolumeController;
  late TextEditingController _poopTimeController;

  String selectedPoopType = '';

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 5, vsync: this);
    _tabController.addListener(() {
      setState(() => _currentTabIndex = _tabController.index);
    });

    _milkTypeController = TextEditingController(text: 'Formula');
    _milkVolumeController = TextEditingController(text: '180');
    _solidFoodController = TextEditingController();
    _waterVolumeController = TextEditingController(text: '30');
    _poopTimeController = TextEditingController();

    _loadState();
  }

  Future<void> _loadState() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      babyAge = prefs.getInt('numnam_baby_age') ?? 8;
      final logsJson = prefs.getStringList('numnam_logs') ?? [];
      logs = logsJson.map((e) => FeedEntry.fromJson(jsonDecode(e))).toList();
      final heartedJson = prefs.getStringList('numnam_hearted') ?? [];
      heartedRecipes = Set.from(heartedJson.map(int.parse));
    });
  }

  Future<void> _saveState() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setInt('numnam_baby_age', babyAge);
    await prefs.setStringList(
      'numnam_logs',
      logs.map((e) => jsonEncode(e.toJson())).toList(),
    );
    await prefs.setStringList(
      'numnam_hearted',
      heartedRecipes.map((e) => e.toString()).toList(),
    );
  }

  void _addLog(FeedEntry entry) {
    setState(() => logs.add(entry));
    _saveState();
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('✓ Log saved!'),
        duration: Duration(seconds: 2),
      ),
    );
  }

  @override
  void dispose() {
    _tabController.dispose();
    _milkTypeController.dispose();
    _milkVolumeController.dispose();
    _solidFoodController.dispose();
    _waterVolumeController.dispose();
    _poopTimeController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('NumNam Tracker'),
        bottom: TabBar(
          controller: _tabController,
          tabs: const [
            Tab(text: '📊 Dashboard'),
            Tab(text: '➕ Log'),
            Tab(text: '💩 Poop'),
            Tab(text: '🍽️ Recipes'),
            Tab(text: '📖 Guide'),
          ],
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          _buildDashboard(),
          _buildLogPage(),
          _buildPoopGuidePage(),
          _buildRecipesPage(),
          _buildGuidePage(),
        ],
      ),
      floatingActionButton: _currentTabIndex == 0
          ? FloatingActionButton(
              onPressed: () {
                _tabController.animateTo(1);
              },
              tooltip: 'Log feeding',
              child: const Icon(Icons.add),
            )
          : null,
    );
  }

  Widget _buildDashboard() {
    final today = DateTime.now();
    final todayLogs = logs.where((log) {
      final logDate = DateTime.parse(log.timestamp);
      return logDate.year == today.year &&
          logDate.month == today.month &&
          logDate.day == today.day;
    }).toList();

    int totalMilk = 0, totalSolid = 0, totalWater = 0;
    String lastPoop = '—';

    for (var log in todayLogs) {
      if (log.type == 'milk') totalMilk += log.volume;
      if (log.type == 'solid') totalSolid += log.volume;
      if (log.type == 'water') totalWater += log.volume;
      if (log.type == 'poop') lastPoop = log.poopType ?? '—';
    }

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Age selector
          Center(
            child: Card(
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
              child: Padding(
                padding: const EdgeInsets.symmetric(
                  horizontal: 16,
                  vertical: 12,
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Text('👶 Age: '),
                    Text('$babyAge months'),
                    const SizedBox(width: 12),
                    GestureDetector(
                      onTap: () => _showAgeDialog(),
                      child: const Icon(Icons.edit, size: 18),
                    ),
                  ],
                ),
              ),
            ),
          ),
          const SizedBox(height: 24),

          // Today's intake
          Text(
            'Today\'s Intake',
            style: Theme.of(context).textTheme.titleLarge,
          ),
          const SizedBox(height: 12),
          GridView.count(
            crossAxisCount: 2,
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            mainAxisSpacing: 12,
            crossAxisSpacing: 12,
            childAspectRatio: 1.2,
            children: [
              _buildStatCard(
                  '🍼', totalMilk.toString(), 'Milk (ml)', Colors.blue[100]!),
              _buildStatCard('🥣', totalSolid.toString(), 'Solids (ml)',
                  Colors.orange[100]!),
              _buildStatCard(
                  '💧', totalWater.toString(), 'Water (ml)', Colors.cyan[100]!),
              _buildStatCard('💩', lastPoop, 'Last Poop', Colors.green[100]!),
            ],
          ),
          const SizedBox(height: 24),

          // Today's log
          Text(
            'Today\'s Log',
            style: Theme.of(context).textTheme.titleLarge,
          ),
          const SizedBox(height: 12),
          if (todayLogs.isEmpty)
            Center(
              child: Padding(
                padding: const EdgeInsets.symmetric(vertical: 32),
                child: Column(
                  children: [
                    const Text('🍼', style: TextStyle(fontSize: 48)),
                    const SizedBox(height: 8),
                    Text(
                      'No entries yet',
                      style: Theme.of(context).textTheme.bodyMedium,
                    ),
                  ],
                ),
              ),
            )
          else
            ListView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: todayLogs.length,
              itemBuilder: (context, index) {
                final log = todayLogs[todayLogs.length - 1 - index];
                return _buildLogEntryCard(log);
              },
            ),
        ],
      ),
    );
  }

  Widget _buildStatCard(
    String emoji,
    String value,
    String label,
    Color bgColor,
  ) {
    return Container(
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(
          color: Colors.grey[300]!,
          width: 1.5,
        ),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(emoji, style: const TextStyle(fontSize: 32)),
          const SizedBox(height: 4),
          Text(
            value,
            style: const TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            style: const TextStyle(fontSize: 12),
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildLogEntryCard(FeedEntry log) {
    final iconMap = {
      'milk': ('🍼', Colors.blue[100]!),
      'solid': ('🥣', Colors.orange[100]!),
      'water': ('💧', Colors.cyan[100]!),
      'poop': ('💩', Colors.green[100]!),
    };

    final (icon, bgColor) = iconMap[log.type] ?? ('📝', Colors.grey[100]!);

    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          children: [
            Container(
              width: 40,
              height: 40,
              decoration: BoxDecoration(
                color: bgColor,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Center(child: Text(icon)),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    log.label,
                    style: const TextStyle(fontWeight: FontWeight.w600),
                  ),
                  Text(
                    log.time,
                    style: Theme.of(context).textTheme.bodySmall,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildLogPage() {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          // Log type selector
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: [
              _buildLogTypeButton('🍼 Milk', 'milk'),
              _buildLogTypeButton('🥣 Solids', 'solid'),
              _buildLogTypeButton('💧 Water', 'water'),
              _buildLogTypeButton('💩 Poop', 'poop'),
            ],
          ),
          const SizedBox(height: 24),

          // Milk form
          _buildMilkForm(),
        ],
      ),
    );
  }

  Widget _buildLogTypeButton(String label, String type) {
    return ActionChip(
      label: Text(label),
      onPressed: () {
        // Log type switching logic
      },
    );
  }

  Widget _buildMilkForm() {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('🍼 Milk Feed',
                style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(
              'Formula or breast milk',
              style: Theme.of(context).textTheme.bodySmall,
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: 'Formula',
              items: ['Formula', 'Breast Milk', 'Expressed Milk']
                  .map((e) => DropdownMenuItem(value: e, child: Text(e)))
                  .toList(),
              onChanged: (_) {},
              decoration: InputDecoration(
                labelText: 'Milk Type',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 16),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Volume: 180 ml'),
                Slider(
                  value: 180,
                  min: 0,
                  max: 300,
                  divisions: 30,
                  onChanged: (value) {},
                ),
              ],
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: () {},
              icon: const Icon(Icons.check),
              label: const Text('Log Milk Feed'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPoopGuidePage() {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            '💩 Poop Diagnostics',
            style: Theme.of(context).textTheme.headlineSmall,
          ),
          const SizedBox(height: 8),
          Text(
            'What your baby\'s output is telling you',
            style: Theme.of(context).textTheme.bodySmall,
          ),
          const SizedBox(height: 16),
          _buildPoopTypeCard(
            'Type 1',
            '🪨',
            'Hard, small balls',
            'Constipation risk. Increase fluids and healthy fats.',
            Colors.red[100]!,
          ),
          _buildPoopTypeCard(
            'Type 2',
            '🔗',
            'Lumpy, connected balls',
            'Mild constipation. Increase water & fibre slightly.',
            Colors.orange[100]!,
          ),
          _buildPoopTypeCard(
            'Type 4',
            '✨',
            'Smooth, soft log',
            'Perfect! Fibre & fluid balance is ideal.',
            Colors.green[100]!,
          ),
          _buildPoopTypeCard(
            'Type 6',
            '⚡',
            'Fluffy, mushy pieces',
            'Loose/Diarrhoea risk. Reduce high-fibre foods.',
            Colors.yellow[100]!,
          ),
        ],
      ),
    );
  }

  Widget _buildPoopTypeCard(
    String type,
    String emoji,
    String appearance,
    String meaning,
    Color bgColor,
  ) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      color: bgColor,
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              '$emoji $type',
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 4),
            Text(appearance, style: const TextStyle(fontSize: 12)),
            const SizedBox(height: 4),
            Text(meaning, style: const TextStyle(fontSize: 12)),
          ],
        ),
      ),
    );
  }

  Widget _buildRecipesPage() {
    final recipes = [
      {
        'emoji': '🥕',
        'name': 'Carrot & Ginger Purée',
        'age': 6,
        'texture': 'Smooth purée',
        'hearts': 42,
      },
      {
        'emoji': '🥦',
        'name': 'Broccoli & Apple Mash',
        'age': 7,
        'texture': 'Thick purée',
        'hearts': 38,
      },
      {
        'emoji': '🍠',
        'name': 'Sweet Potato & Coconut',
        'age': 6,
        'texture': 'Smooth purée',
        'hearts': 61,
      },
      {
        'emoji': '🍌',
        'name': 'Banana & Oat Porridge',
        'age': 7,
        'texture': 'Mashed',
        'hearts': 55,
      },
    ];

    final filtered = recipes.where((r) => r['age']! <= babyAge).toList();

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            '🍽️ Recipe Swaps',
            style: Theme.of(context).textTheme.headlineSmall,
          ),
          const SizedBox(height: 8),
          Text(
            'Mom-to-Mom favourites — filtered for your baby\'s age',
            style: Theme.of(context).textTheme.bodySmall,
          ),
          const SizedBox(height: 16),
          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 2,
              childAspectRatio: 0.85,
              mainAxisSpacing: 12,
              crossAxisSpacing: 12,
            ),
            itemCount: filtered.length,
            itemBuilder: (context, index) {
              final recipe = filtered[index];
              return _buildRecipeCard(recipe);
            },
          ),
        ],
      ),
    );
  }

  Widget _buildRecipeCard(Map<String, dynamic> recipe) {
    final id = recipe['name'].hashCode;
    final isHearted = heartedRecipes.contains(id);

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  recipe['emoji'],
                  style: const TextStyle(fontSize: 32),
                ),
                const SizedBox(height: 8),
                Text(
                  recipe['name'],
                  style: const TextStyle(
                    fontWeight: FontWeight.w600,
                    fontSize: 12,
                  ),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 4),
                Text(
                  recipe['texture'],
                  style: const TextStyle(fontSize: 10),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(8),
            child: Row(
              children: [
                GestureDetector(
                  onTap: () {
                    setState(() {
                      if (isHearted) {
                        heartedRecipes.remove(id);
                      } else {
                        heartedRecipes.add(id);
                      }
                      _saveState();
                    });
                  },
                  child: Text(
                    isHearted ? '❤️' : '🤍',
                    style: const TextStyle(fontSize: 16),
                  ),
                ),
                const SizedBox(width: 4),
                Text(
                  recipe['hearts'].toString(),
                  style: const TextStyle(fontSize: 11),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildGuidePage() {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            '📖 Weaning Guide',
            style: Theme.of(context).textTheme.headlineSmall,
          ),
          const SizedBox(height: 8),
          Text(
            'Stage-by-stage advice tailored to your baby',
            style: Theme.of(context).textTheme.bodySmall,
          ),
          const SizedBox(height: 24),
          _buildMilestoneCard(
            6,
            '🎉 First Spoon!',
            'Today is about the tongue, not the tummy! If baby pushes food out — that\'s the extrusion reflex, not rejection.',
          ),
          _buildMilestoneCard(
            8,
            '🌟 Bridge to Texture!',
            'Baby is getting ~30% of energy from solids. If they seem less interested in milk, that\'s okay!',
          ),
          _buildMilestoneCard(
            10,
            '🍽️ Texture Challenge Time!',
            'Stop the blender! Moving to mashed and soft lumps now helps develop jaw muscles needed for speech.',
          ),
          const SizedBox(height: 24),
          Text(
            '⚠️ Safety Rules',
            style: Theme.of(context).textTheme.titleMedium,
          ),
          const SizedBox(height: 12),
          _buildSafetyRule('Never salt or sugar babies under 1',
              'Their kidneys can\'t process extra sodium.'),
          _buildSafetyRule(
            'Choking hazards: whole nuts, popcorn, hard candy, grapes',
            'Cut grapes lengthwise into 4 pieces.',
          ),
          _buildSafetyRule(
            'Never honey before 1 year',
            'Botulism risk. Use mashed fruit or date paste instead.',
          ),
        ],
      ),
    );
  }

  Widget _buildMilestoneCard(
    int age,
    String title,
    String description,
  ) {
    final isCurrentAge = babyAge == age;
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      color: isCurrentAge ? Colors.blue[50] : Colors.grey[50],
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: isCurrentAge
            ? BorderSide(color: Colors.blue[300]!)
            : const BorderSide(color: Color(0xFFFFD6E5)),
      ),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              '$title (at $age months)',
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 4),
            Text(description, style: const TextStyle(fontSize: 13)),
          ],
        ),
      ),
    );
  }

  Widget _buildSafetyRule(String title, String description) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('•', style: TextStyle(fontSize: 18)),
          const SizedBox(width: 8),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                      fontWeight: FontWeight.w600, fontSize: 13),
                ),
                Text(
                  description,
                  style: const TextStyle(fontSize: 12),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  void _showAgeDialog() {
    showDialog(
      context: context,
      builder: (context) {
        int tempAge = babyAge;
        return AlertDialog(
          title: const Text('How old is your baby?'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                keyboardType: TextInputType.number,
                onChanged: (value) => tempAge = int.tryParse(value) ?? babyAge,
                decoration: InputDecoration(
                  labelText: 'Age (months)',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel'),
            ),
            ElevatedButton(
              onPressed: () {
                setState(() => babyAge = tempAge);
                _saveState();
                Navigator.pop(context);
              },
              child: const Text('Save'),
            ),
          ],
        );
      },
    );
  }
}

class FeedEntry {
  final String type; // milk, solid, water, poop
  final int volume;
  final String time;
  final String label;
  final String timestamp;
  final String? milkType;
  final String? food;
  final String? texture;
  final String? finish;
  final String? poopType;

  FeedEntry({
    required this.type,
    required this.volume,
    required this.time,
    required this.label,
    required this.timestamp,
    this.milkType,
    this.food,
    this.texture,
    this.finish,
    this.poopType,
  });

  Map<String, dynamic> toJson() => {
        'type': type,
        'volume': volume,
        'time': time,
        'label': label,
        'timestamp': timestamp,
        'milkType': milkType,
        'food': food,
        'texture': texture,
        'finish': finish,
        'poopType': poopType,
      };

  factory FeedEntry.fromJson(Map<String, dynamic> json) => FeedEntry(
        type: json['type'],
        volume: json['volume'],
        time: json['time'],
        label: json['label'],
        timestamp: json['timestamp'],
        milkType: json['milkType'],
        food: json['food'],
        texture: json['texture'],
        finish: json['finish'],
        poopType: json['poopType'],
      );
}
