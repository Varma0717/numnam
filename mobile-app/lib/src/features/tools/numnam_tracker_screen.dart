import 'package:flutter/material.dart';
import 'package:mobile_app/src/services/tracker_service.dart';
import 'package:mobile_app/src/services/tracker_config_service.dart';

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

  // Form state
  String _selectedLogType = 'milk';
  String _selectedMilkType = 'Formula';
  double _milkVolume = 180;
  String _solidFood = '';
  double _waterVolume = 30;
  String _selectedPoopType = '';
  TextEditingController? _ageController;

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
        // Update form defaults from config
        if (_config != null && _config!.milkTypes.isNotEmpty) {
          _selectedMilkType = _config!.milkTypes[0].name;
          _milkVolume = _config!.milkTypes[0].defaultVolume.toDouble();
        }
        if (_config != null && _config!.feedTypes.isNotEmpty) {
          final waterType =
              _config!.feedTypes.where((f) => f.id == 'water').firstOrNull;
          if (waterType != null && waterType.defaultVolume != null) {
            _waterVolume = waterType.defaultVolume!.toDouble();
          }
        }
      });
    } catch (e) {
      print('Error loading tracker config: $e');
      // Use default values if config fails to load
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
            Tab(text: '📖 Guide'),
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

  Widget _buildLogEntryCard(FeedLog log) {
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

          // Dynamic form based on log type
          if (_selectedLogType == 'milk') _buildMilkForm(),
          if (_selectedLogType == 'solid') _buildSolidForm(),
          if (_selectedLogType == 'water') _buildWaterForm(),
          if (_selectedLogType == 'poop') _buildPoopForm(),
        ],
      ),
    );
  }

  Widget _buildLogTypeButton(String label, String type) {
    final isSelected = _selectedLogType == type;
    return FilterChip(
      label: Text(label),
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
      (m) => m.name == _selectedMilkType,
      orElse: () => milkTypeOptions[0],
    );

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('${currentMilkConfig.emoji} Milk Feed',
                style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(
              currentMilkConfig.description,
              style: Theme.of(context).textTheme.bodySmall,
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _selectedMilkType,
              items: milkTypeOptions
                  .map((e) => DropdownMenuItem(
                      value: e.name, child: Text('${e.emoji} ${e.name}')))
                  .toList(),
              onChanged: (value) {
                if (value != null) {
                  final selected =
                      milkTypeOptions.firstWhere((m) => m.name == value);
                  setState(() {
                    _selectedMilkType = value;
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
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: () {
                _addLog(FeedLogRequest(
                  type: 'milk',
                  volume: _milkVolume.toInt(),
                  label: '$_selectedMilkType - ${_milkVolume.toInt()} ml',
                  milkType: _selectedMilkType,
                ));
                // Reset form
                setState(() {
                  _selectedMilkType = 'Formula';
                  _milkVolume = 180;
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
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('🥣 Solid Food',
                style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text('What food did baby eat?',
                style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 16),
            TextField(
              onChanged: (value) => _solidFood = value,
              decoration: InputDecoration(
                labelText: 'Food name (e.g., Carrot purée)',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: _solidFood.isEmpty
                  ? null
                  : () {
                      _addLog(FeedLogRequest(
                        type: 'solid',
                        volume: 100,
                        label: _solidFood,
                        food: _solidFood,
                      ));
                      setState(() => _solidFood = '');
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
    if (_config == null || _config!.feedTypes.isEmpty) {
      return const Card(
          child: Padding(
        padding: EdgeInsets.all(16),
        child: Text('Loading water options...'),
      ));
    }

    final waterConfig = _config!.feedTypes.firstWhere(
      (f) => f.id == 'water',
      orElse: () => _config!.feedTypes[0],
    );

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('${waterConfig.emoji} Water',
                style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(waterConfig.description,
                style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 16),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Volume: ${_waterVolume.toInt()} ml'),
                Slider(
                  value: _waterVolume,
                  min: (waterConfig.minVolume ?? 0).toDouble(),
                  max: (waterConfig.maxVolume ?? 100).toDouble(),
                  divisions: ((waterConfig.maxVolume ?? 100) -
                          (waterConfig.minVolume ?? 0)) ~/
                      10,
                  onChanged: (value) {
                    setState(() => _waterVolume = value);
                  },
                ),
              ],
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: () {
                _addLog(FeedLogRequest(
                  type: 'water',
                  volume: _waterVolume.toInt(),
                  label: 'Water - ${_waterVolume.toInt()} ml',
                ));
                setState(() => _waterVolume = 30);
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

    final poopConfig = _config!.feedTypes.firstWhere(
      (f) => f.id == 'poop',
      orElse: () => _config!.feedTypes[0],
    );

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('${poopConfig.emoji} Poop Tracking',
                style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(poopConfig.description,
                style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 16),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                for (final poop in _config!.poopTypes)
                  _buildPoopTypeSelection(poop.type, poop.emoji),
              ],
            ),
            const SizedBox(height: 16),
            ElevatedButton.icon(
              onPressed: _selectedPoopType.isEmpty
                  ? null
                  : () {
                      _addLog(FeedLogRequest(
                        type: 'poop',
                        volume: 0,
                        label: 'Poop: $_selectedPoopType',
                        poopType: _selectedPoopType,
                      ));
                      setState(() => _selectedPoopType = '');
                    },
              icon: const Icon(Icons.check),
              label: const Text('Log Poop Type'),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPoopTypeSelection(String label, String emoji) {
    final isSelected = _selectedPoopType == label;
    return FilterChip(
      label: Text('$emoji $label'),
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
            '💩 Poop Diagnostics',
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
              poop.emoji,
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
            '📖 Weaning Guide',
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
              milestone.title,
              milestone.description,
            ),
          const SizedBox(height: 24),
          Text(
            '⚠️ Safety Rules',
            style: Theme.of(context).textTheme.titleMedium,
          ),
          const SizedBox(height: 12),
          for (final rule in _config!.safetyRules)
            _buildSafetyRule(rule.title, rule.description),
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
