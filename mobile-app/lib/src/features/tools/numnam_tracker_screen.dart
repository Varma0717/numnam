import 'package:flutter/material.dart';
import 'package:mobile_app/src/models/feed_log.dart';
import 'package:mobile_app/src/services/tracker_service.dart';
import 'package:mobile_app/src/services/tracker_config_service.dart';
import 'package:mobile_app/src/shared/widgets/inner_page_nav.dart';

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
  List<FeedLog> logs = [];
  Set<int> heartedRecipes = {};
  int _currentTabIndex = 0;
  bool _isLoading = true;
  String? _error;

  // Configuration from API
  TrackerConfig? _config;

  // Form state (kept in sync with web tracker fields/options)
  String _selectedLogType = 'milk';
  String _selectedMilkTypeId = 'formula';
  double _milkVolume = 180;
  double _solidVolume = 100;
  double _waterVolume = 30;
  String _solidFood = '';
  String _solidFoodType = '';
  String _solidTexture = '';
  String _solidFinishLevel = '';
  String _selectedPoopType = '';
  final TextEditingController _milkNotesController = TextEditingController();
  final TextEditingController _solidNotesController = TextEditingController();
  final TextEditingController _waterNotesController = TextEditingController();
  TextEditingController? _ageController;

  String _cleanLabel(String value) {
    return value
        .replaceAll(RegExp(r'[^\x00-\x7F]+'), '')
        .replaceAll(RegExp(r'\s+'), ' ')
        .trim();
  }

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _tabController.addListener(() {
      setState(() => _currentTabIndex = _tabController.index);
    });
    _ageController = TextEditingController(text: '8');
    _loadTrackerData();
    _loadConfig();
  }

  Future<void> _loadConfig() async {
    try {
      final config = await TrackerConfigService.fetchConfig();
      setState(() {
        _config = config;

        if (_config != null && _config!.milkTypes.isNotEmpty) {
          _selectedMilkTypeId = _config!.milkTypes[0].id;
          _milkVolume = _config!.milkTypes[0].defaultVolume.toDouble();
        }

        if (_config != null && _config!.feedTypes.isNotEmpty) {
          for (final type in _config!.feedTypes) {
            if (type.id == 'solid' && type.defaultVolume != null) {
              _solidVolume = type.defaultVolume!.toDouble();
            }
            if (type.id == 'water' && type.defaultVolume != null) {
              _waterVolume = type.defaultVolume!.toDouble();
            }
          }
        }
      });
    } catch (e) {
      debugPrint('Error loading tracker config: $e');
    }
  }

  Future<void> _loadTrackerData() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });
    try {
      final data = await TrackerService.fetchTrackerData();
      setState(() {
        babyAge = data.babyAge;
        logs = data.logs;
        heartedRecipes = data.heartedRecipes;
        _ageController?.text = babyAge.toString();
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
        _isLoading = false;
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error loading tracker: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  Future<void> _addLog(FeedLogRequest logRequest) async {
    try {
      await TrackerService.addFeedLog(logRequest);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('✓ Log saved!'),
            duration: Duration(seconds: 2),
          ),
        );
        // Refresh logs
        _loadTrackerData();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error saving log: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  void dispose() {
    _tabController.dispose();
    _ageController?.dispose();
    _milkNotesController.dispose();
    _solidNotesController.dispose();
    _waterNotesController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('NumNam Tracker'),
        bottom: TabBar(
          isScrollable: true,
          controller: _tabController,
          tabs: const [
            Tab(icon: Icon(Icons.dashboard_outlined), text: 'Dashboard'),
            Tab(icon: Icon(Icons.add_circle_outline), text: 'Log'),
            Tab(icon: Icon(Icons.monitor_heart_outlined), text: 'Stool'),
            Tab(icon: Icon(Icons.menu_book_outlined), text: 'Guide'),
          ],
        ),
      ),
      body: _isLoading
          ? const Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  CircularProgressIndicator(),
                  SizedBox(height: 16),
                  Text('Loading tracker data...'),
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
                        onPressed: _loadTrackerData,
                        child: const Text('Retry'),
                      ),
                    ],
                  ),
                )
              : TabBarView(
                  controller: _tabController,
                  children: [
                    _buildDashboard(),
                    _buildLogPage(),
                    _buildPoopGuidePage(),
                    _buildGuidePage(),
                  ],
                ),
      bottomNavigationBar: const InnerPageNav(),
      floatingActionButton: _currentTabIndex == 0 && !_isLoading
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
      return log.timestamp.year == today.year &&
          log.timestamp.month == today.month &&
          log.timestamp.day == today.day;
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
                    const Icon(Icons.child_care_outlined, size: 18),
                    const SizedBox(width: 6),
                    const Text('Age:'),
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
              _buildStatCard(Icons.local_drink_outlined, totalMilk.toString(),
                  'Milk (ml)', Colors.blue[100]!),
              _buildStatCard(Icons.restaurant_outlined, totalSolid.toString(),
                  'Solids (ml)', Colors.orange[100]!),
              _buildStatCard(Icons.water_drop_outlined, totalWater.toString(),
                  'Water (ml)', Colors.cyan[100]!),
              _buildStatCard(Icons.monitor_heart_outlined, lastPoop,
                  'Last Stool', Colors.green[100]!),
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
                    Icon(Icons.event_note_outlined,
                        size: 44, color: Colors.grey[500]),
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
    IconData icon,
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
          Icon(icon, size: 30, color: Colors.black87),
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

  Widget _buildLogEntryCard(FeedLog log) {
    final iconMap = {
      'milk': (Icons.local_drink_outlined, Colors.blue[100]!),
      'solid': (Icons.restaurant_outlined, Colors.orange[100]!),
      'water': (Icons.water_drop_outlined, Colors.cyan[100]!),
      'poop': (Icons.monitor_heart_outlined, Colors.green[100]!),
    };

    final (icon, bgColor) =
        iconMap[log.type] ?? (Icons.notes_outlined, Colors.grey[100]!);

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
              child: Center(child: Icon(icon, size: 20, color: Colors.black87)),
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
              _buildLogTypeButton(Icons.local_drink_outlined, 'Milk', 'milk'),
              _buildLogTypeButton(Icons.restaurant_outlined, 'Solids', 'solid'),
              _buildLogTypeButton(Icons.water_drop_outlined, 'Water', 'water'),
              _buildLogTypeButton(
                  Icons.monitor_heart_outlined, 'Stool', 'poop'),
            ],
          ),
          const SizedBox(height: 24),

          // Dynamic form based on log type
          if (_selectedLogType == 'milk') _buildMilkForm(),
          if (_selectedLogType == 'solid') _buildSolidForm(),
          if (_selectedLogType == 'water') _buildWaterForm(),
          if (_selectedLogType == 'poop') _buildPoopForm(),
        ],
      ),
    );
  }

  Widget _buildLogTypeButton(IconData icon, String label, String type) {
    final isSelected = _selectedLogType == type;
    return FilterChip(
      label: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 16),
          const SizedBox(width: 6),
          Text(label),
        ],
      ),
      selected: isSelected,
      onSelected: (selected) {
        setState(() => _selectedLogType = type);
      },
    );
  }

  Widget _buildMilkForm() {
    if (_config == null || _config!.milkTypes.isEmpty) {
      return const Card(
          child: Padding(
        padding: EdgeInsets.all(16),
        child: Text('Loading milk options...'),
      ));
    }

    final milkTypeOptions = _config!.milkTypes;
    final currentMilkConfig = milkTypeOptions.firstWhere(
      (m) => m.id == _selectedMilkTypeId,
      orElse: () => milkTypeOptions[0],
    );

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Milk Feed', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(
              currentMilkConfig.description,
              style: Theme.of(context).textTheme.bodySmall,
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _selectedMilkTypeId,
              items: milkTypeOptions
                  .map(
                      (e) => DropdownMenuItem(value: e.id, child: Text(e.name)))
                  .toList(),
              onChanged: (value) {
                if (value != null) {
                  final selected =
                      milkTypeOptions.firstWhere((m) => m.id == value);
                  setState(() {
                    _selectedMilkTypeId = value;
                    _milkVolume = selected.defaultVolume.toDouble();
                  });
                }
              },
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
                Text('Volume: ${_milkVolume.toInt()} ml'),
                Slider(
                  value: _milkVolume,
                  min: currentMilkConfig.minVolume.toDouble(),
                  max: currentMilkConfig.maxVolume.toDouble(),
                  divisions: (currentMilkConfig.maxVolume -
                          currentMilkConfig.minVolume) ~/
                      10,
                  onChanged: (value) {
                    setState(() => _milkVolume = value);
                  },
                ),
              ],
            ),
            const SizedBox(height: 8),
            TextField(
              controller: _milkNotesController,
              decoration: InputDecoration(
                labelText: 'Notes (optional)',
                hintText: 'e.g., seemed hungry, fell asleep quickly',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: () {
                _addLog(FeedLogRequest(
                  type: 'milk',
                  volumeMl: _milkVolume.toInt(),
                  milkType: _selectedMilkTypeId,
                  notes: _milkNotesController.text.trim(),
                ));
                setState(() {
                  _selectedMilkTypeId = milkTypeOptions.first.id;
                  _milkVolume = milkTypeOptions.first.defaultVolume.toDouble();
                  _milkNotesController.clear();
                });
              },
              icon: const Icon(Icons.check),
              label: const Text('Log Milk Feed'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSolidForm() {
    const foodTypeOptions = [
      ('veggie', 'Vegetable'),
      ('fruit', 'Fruit'),
      ('protein', 'Protein'),
      ('grain', 'Grain'),
      ('dairy', 'Dairy'),
      ('mixed', 'Mixed'),
    ];

    const textureOptions = [
      'Smooth purée',
      'Thick purée',
      'Mashed',
      'Soft lumps',
      'Chopped/Finger Food',
    ];

    const finishOptions = [
      ('all', 'Finished All'),
      ('most', 'Ate Most'),
      ('half', 'Ate Half'),
      ('few', 'Ate a Few Bites'),
      ('floor', 'Mostly on Floor'),
      ('refused', 'Refused'),
    ];

    final canSubmit = _solidFood.trim().isNotEmpty &&
        _solidFoodType.isNotEmpty &&
        _solidTexture.isNotEmpty &&
        _solidFinishLevel.isNotEmpty;

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Solid Food', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text('Purees, mashed, or finger food',
                style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 16),
            TextField(
              onChanged: (value) => setState(() => _solidFood = value),
              decoration: InputDecoration(
                labelText: 'Food Name',
                hintText: 'e.g., Carrot Puree, Banana Mash',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _solidFoodType.isEmpty ? null : _solidFoodType,
              items: foodTypeOptions
                  .map((e) => DropdownMenuItem(value: e.$1, child: Text(e.$2)))
                  .toList(),
              onChanged: (value) =>
                  setState(() => _solidFoodType = value ?? ''),
              decoration: InputDecoration(
                labelText: 'Food Type',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _solidTexture.isEmpty ? null : _solidTexture,
              items: textureOptions
                  .map((e) => DropdownMenuItem(value: e, child: Text(e)))
                  .toList(),
              onChanged: (value) => setState(() => _solidTexture = value ?? ''),
              decoration: InputDecoration(
                labelText: 'Texture',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 12),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Volume: ${_solidVolume.toInt()} ml'),
                Slider(
                  value: _solidVolume,
                  min: 0,
                  max: 300,
                  divisions: 30,
                  onChanged: (value) => setState(() => _solidVolume = value),
                ),
              ],
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _solidFinishLevel.isEmpty ? null : _solidFinishLevel,
              items: finishOptions
                  .map((e) => DropdownMenuItem(value: e.$1, child: Text(e.$2)))
                  .toList(),
              onChanged: (value) =>
                  setState(() => _solidFinishLevel = value ?? ''),
              decoration: InputDecoration(
                labelText: 'How Much Did Baby Eat?',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 12),
            TextField(
              controller: _solidNotesController,
              decoration: InputDecoration(
                labelText: 'Notes (optional)',
                hintText: 'e.g., seemed to like it, new food',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: !canSubmit
                  ? null
                  : () {
                      _addLog(FeedLogRequest(
                        type: 'solid',
                        volumeMl: _solidVolume.toInt(),
                        foodName: _solidFood.trim(),
                        foodType: _solidFoodType,
                        texture: _solidTexture,
                        finishLevel: _solidFinishLevel,
                        notes: _solidNotesController.text.trim(),
                      ));
                      setState(() {
                        _solidFood = '';
                        _solidFoodType = '';
                        _solidTexture = '';
                        _solidFinishLevel = '';
                        _solidVolume = 100;
                        _solidNotesController.clear();
                      });
                    },
              icon: const Icon(Icons.check),
              label: const Text('Log Solid Food'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildWaterForm() {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Water', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text('Water intake for hydration',
                style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 16),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Volume: ${_waterVolume.toInt()} ml'),
                Slider(
                  value: _waterVolume,
                  min: 0,
                  max: 120,
                  divisions: 24,
                  onChanged: (value) {
                    setState(() => _waterVolume = value);
                  },
                ),
              ],
            ),
            const SizedBox(height: 8),
            TextField(
              controller: _waterNotesController,
              decoration: InputDecoration(
                labelText: 'Notes (optional)',
                hintText: 'e.g., in sippy cup',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: () {
                _addLog(FeedLogRequest(
                  type: 'water',
                  volumeMl: _waterVolume.toInt(),
                  notes: _waterNotesController.text.trim(),
                ));
                setState(() {
                  _waterVolume = 30;
                  _waterNotesController.clear();
                });
              },
              icon: const Icon(Icons.check),
              label: const Text('Log Water'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPoopForm() {
    if (_config == null || _config!.poopTypes.isEmpty) {
      return const Card(
          child: Padding(
        padding: EdgeInsets.all(16),
        child: Text('Loading poop options...'),
      ));
    }

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Stool Log', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(
                'Select the closest match (Bristol Stool Chart – infant adapted)',
                style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 16),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                for (final poop in _config!.poopTypes)
                  _buildPoopTypeSelection(poop.type),
              ],
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: _selectedPoopType.isEmpty
                  ? null
                  : () {
                      _addLog(FeedLogRequest(
                        type: 'poop',
                        poopType: _selectedPoopType,
                      ));
                      setState(() => _selectedPoopType = '');
                    },
              icon: const Icon(Icons.check),
              label: const Text('Log Stool'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPoopTypeSelection(String label) {
    final isSelected = _selectedPoopType == label;
    return FilterChip(
      label: Text(label),
      selected: isSelected,
      onSelected: (selected) {
        setState(() => _selectedPoopType = selected ? label : '');
      },
    );
  }

  Widget _buildPoopGuidePage() {
    if (_config == null || _config!.poopTypes.isEmpty) {
      return const Center(child: Text('Loading poop guide...'));
    }

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Stool Diagnostics',
            style: Theme.of(context).textTheme.headlineSmall,
          ),
          const SizedBox(height: 8),
          Text(
            'What your baby\'s output is telling you',
            style: Theme.of(context).textTheme.bodySmall,
          ),
          const SizedBox(height: 16),
          for (final poop in _config!.poopTypes)
            _buildPoopTypeCard(
              poop.type,
              poop.appearance,
              poop.meaning,
              _parseColorFromHex(poop.color),
            ),
        ],
      ),
    );
  }

  Color _parseColorFromHex(String hexColor) {
    hexColor = hexColor.replaceAll('#', '');
    return Color(int.parse('FF$hexColor', radix: 16));
  }

  Widget _buildPoopTypeCard(
    String type,
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
              type,
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

  Widget _buildGuidePage() {
    if (_config == null) {
      return const Center(child: Text('Loading weaning guide...'));
    }

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Weaning Guide',
            style: Theme.of(context).textTheme.headlineSmall,
          ),
          const SizedBox(height: 8),
          Text(
            'Stage-by-stage advice tailored to your baby',
            style: Theme.of(context).textTheme.bodySmall,
          ),
          const SizedBox(height: 24),
          for (final milestone in _config!.milestones)
            _buildMilestoneCard(
              milestone.age,
              _cleanLabel(milestone.title),
              milestone.description,
            ),
          const SizedBox(height: 24),
          Text(
            'Safety Rules',
            style: Theme.of(context).textTheme.titleMedium,
          ),
          const SizedBox(height: 12),
          for (final rule in _config!.safetyRules)
            _buildSafetyRule(_cleanLabel(rule.title), rule.description),
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
    int tempAge = babyAge;
    final ageTextController = TextEditingController(text: babyAge.toString());

    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('How old is your baby?'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: ageTextController,
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
              onPressed: () {
                ageTextController.dispose();
                Navigator.pop(context);
              },
              child: const Text('Cancel'),
            ),
            ElevatedButton(
              onPressed: () async {
                try {
                  await TrackerService.saveBabyAge(tempAge);
                  if (mounted) {
                    setState(() => babyAge = tempAge);
                    _ageController?.text = tempAge.toString();
                    ageTextController.dispose();
                    Navigator.pop(context);
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text('✓ Baby age updated!'),
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
              },
              child: const Text('Save'),
            ),
          ],
        );
      },
    );
  }
}
