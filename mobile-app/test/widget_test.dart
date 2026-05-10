import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile_app/src/app.dart';

void main() {
  testWidgets('app shell renders storefront title', (WidgetTester tester) async {
    await tester.pumpWidget(const NumNamApp());
    await tester.pump(const Duration(milliseconds: 500));

    expect(find.byType(MaterialApp), findsOneWidget);
    expect(find.byType(Scaffold), findsWidgets);
  });
}
