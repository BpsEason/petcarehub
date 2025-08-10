import 'package:flutter/material.dart';
import 'package:petcarehub_app/services/api_service.dart';

class TaskListScreen extends StatefulWidget {
  final ApiService apiService;
  final String authToken;

  const TaskListScreen({Key? key, required this.apiService, required this.authToken}) : super(key: key);

  @override
  _TaskListScreenState createState() => _TaskListScreenState();
}

class _TaskListScreenState extends State<TaskListScreen> {
  List<Map<String, dynamic>> tasks = [];
  bool isLoading = true;
  String? error;

  @override
  void initState() {
    super.initState();
    _fetchTasks();
  }

  Future<void> _fetchTasks() async {
    setState(() {
      isLoading = true;
      error = null;
    });
    try {
      final fetchedTasks = await widget.apiService.fetchTasks(widget.authToken);
      setState(() {
        tasks = fetchedTasks;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        error = 'Failed to load tasks: $e';
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('我的任務'),
        centerTitle: true,
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : error != null
              ? Center(
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(error!, style: const TextStyle(color: Colors.red, fontSize: 16)),
                        const SizedBox(height: 10),
                        ElevatedButton(
                          onPressed: _fetchTasks,
                          child: const Text('重試'),
                        ),
                      ],
                    ),
                  ),
                )
              : tasks.isEmpty
                  ? const Center(child: Text('目前沒有任何任務。'))
                  : ListView.builder(
                      itemCount: tasks.length,
                      itemBuilder: (context, index) {
                        final task = tasks[index];
                        return Card(
                          margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                          child: ListTile(
                            title: Text(task['title']),
                            subtitle: Text('Status: ${task['status']}'),
                            trailing: Checkbox(
                              value: task['status'] == 'completed',
                              onChanged: (bool? value) {
                                // TODO: Update task status via API
                                ScaffoldMessenger.of(context).showSnackBar(
                                  SnackBar(content: Text('任務 "${task['title']}" 狀態已更新為 ${value == true ? "完成" : "待辦"}')),
                                );
                              },
                            ),
                          ),
                        );
                      },
                    ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          // TODO: Navigate to add new task screen
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('新增任務按鈕點擊！')),
          );
        },
        child: const Icon(Icons.add),
      ),
    );
  }
}
