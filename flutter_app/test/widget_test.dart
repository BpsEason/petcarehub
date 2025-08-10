import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart'; // 導入 ProviderScope
import 'package:petcarehub_app/screens/home_screen.dart'; // 導入你的 HomeScreen

void main() {
  testWidgets('HomeScreen displays title and welcome message', (WidgetTester tester) async {
    // 建立 widget
    await tester.pumpWidget(
      const ProviderScope( // 包含 ProviderScope 因為 HomeScreen 可能依賴它
        child: MaterialApp(home: HomeScreen()),
      ),
    );

    // 驗證 AppBar 標題
    expect(find.text('PetCareHub'), findsOneWidget);
    // 驗證歡迎訊息
    expect(find.byKey(Key('welcome_message')), findsOneWidget); // Find by Key for robustness
    expect(find.text('歡迎來到 PetCareHub 行動 App！'), findsOneWidget);
    // 驗證按鈕
    expect(find.byKey(Key('start_button')), findsOneWidget); // Find by Key
    expect(find.widgetWithText(ElevatedButton, '開始使用'), findsOneWidget);
    // 驗證寵物圖示
    expect(find.byIcon(Icons.pets), findsOneWidget);
  });
}
