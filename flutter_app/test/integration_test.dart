import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:mockito/mockito.dart';
import 'package:petcarehub_app/main.dart'; // Your main app
import 'package:petcarehub_app/screens/task_list_screen.dart';
import 'package:petcarehub_app/services/api_service.dart';
import 'api_service_test.mocks.dart'; // Generated mock Dio

// Create a mock for ApiService itself if you don't want to test Dio directly
class MockApiService extends Mock implements ApiService {}

void main() {
  group('Integration Tests', () {
    late MockApiService mockApiService;

    setUp(() {
      mockApiService = MockApiService();
      // Define default successful behavior for mockApiService
      when(mockApiService.fetchTasks(any)).thenAnswer((_) async => [
            {'id': 1, 'title': 'Test Task 1 from API', 'status': 'pending'},
            {'id': 2, 'title': 'Test Task 2 from API', 'status': 'completed'}
          ]);
    });

    testWidgets('App navigates from Home to TaskList and displays data', (WidgetTester tester) async {
      // Inject the mock ApiService into the TaskListScreen for testing
      // This requires modifying main.dart or routing to allow injecting ApiService.
      // For simplicity in this example, we will directly pump TaskListScreen.
      await tester.pumpWidget(
        ProviderScope(
          child: MaterialApp(
            home: TaskListScreen(apiService: mockApiService, authToken: 'test_token'),
          ),
        ),
      );

      // Verify that loading indicator appears
      expect(find.byType(CircularProgressIndicator), findsOneWidget);

      // Wait for data to load
      await tester.pumpAndSettle();

      // Verify tasks are displayed
      expect(find.text('我的任務'), findsOneWidget);
      expect(find.text('Test Task 1 from API'), findsOneWidget);
      expect(find.text('Test Task 2 from API'), findsOneWidget);
      expect(find.byType(CircularProgressIndicator), findsNothing); // Loading should be gone

      // Verify UI changes after button click (example: FloatingActionButton)
      await tester.tap(find.byType(FloatingActionButton));
      await tester.pumpAndSettle(); // Wait for SnackBar
      expect(find.text('新增任務按鈕點擊！'), findsOneWidget);
    });

    testWidgets('TaskListScreen displays error message on API failure', (WidgetTester tester) async {
      // Stub fetchTasks to throw an exception
      when(mockApiService.fetchTasks(any)).thenThrow(Exception('Simulated API Error'));

      await tester.pumpWidget(
        ProviderScope(
          child: MaterialApp(
            home: TaskListScreen(apiService: mockApiService, authToken: 'test_token'),
          ),
        ),
      );

      await tester.pumpAndSettle(); // Wait for error to display

      expect(find.text('Failed to load tasks: Exception: Simulated API Error'), findsOneWidget);
      expect(find.text('重試'), findsOneWidget); // Verify retry button exists
      expect(find.byType(CircularProgressIndicator), findsNothing);
    });
  });
}
