import 'package:flutter_test/flutter_test.dart';

import 'package:mobile_app/src/app.dart';

void main() {
  testWidgets('app shell renders storefront title', (WidgetTester tester) async {
    await tester.pumpWidget(const NumNamApp());

    expect(find.text('NumNam Kids Store'), findsOneWidget);
    expect(find.text('Home'), findsOneWidget);
  });
}
