import 'package:flutter/material.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

import 'src/app.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();

  const appEnv = String.fromEnvironment('APP_ENV', defaultValue: 'production');
  final envFileName = '.env.$appEnv';

  try {
    await dotenv.load(fileName: envFileName);
  } catch (_) {
    try {
      await dotenv.load(fileName: '.env.example');
    } catch (_) {
      // Fall back to compile-time defaults when no env asset is present.
    }
  }

  runApp(const NumNamApp());
}
