import 'package:dio/dio.dart';

class ApiService {
  // Marked as protected for testing purposes. In production, consider injecting Dio.
  @protected
  final Dio _dio;
  final String baseUrl;

  ApiService(this.baseUrl) : _dio = Dio();

  // Constructor for testing, allows injecting a mock Dio
  ApiService.withDio(this.baseUrl, this._dio);


  Future<List<Map<String, dynamic>>> fetchTasks(String token) async {
    try {
      final response = await _dio.get(
        '$baseUrl/tasks',
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
            'Content-Type': 'application/json', // Add Content-Type header
          },
        ),
      );
      if (response.statusCode == 200) {
        return List<Map<String, dynamic>>.from(response.data);
      } else {
        throw Exception('Failed to load tasks: Status code ${response.statusCode}');
      }
    } on DioException catch (e) {
      print('Dio Error fetching tasks: $e');
      if (e.response != null) {
        print('Error Response: ${e.response?.data}');
        if (e.response?.statusCode == 401) {
          throw Exception('Unauthorized: Invalid token');
        } else if (e.response?.statusCode == 404) {
          throw Exception('Not Found: ${e.requestOptions.path}');
        }
      }
      throw Exception('Network error or API call failed: ${e.message}');
    } catch (e) {
      print('Generic Error fetching tasks: $e');
      rethrow;
    }
  }

  // You can add other API methods like login, createPet, etc.
}
