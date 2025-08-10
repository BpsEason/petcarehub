<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Pet;
use App\Models\CareTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
class CareTaskApiTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $pet;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->pet = $this->user->pets()->create([
            'name' => 'Test Pet for Tasks',
            'species' => 'dog'
        ]);
    }

    public function test_can_retrieve_care_tasks_for_authenticated_user(): void {
        $task = $this->pet->careTasks()->create([
            'user_id' => $this->user->id,
            'title' => 'Walk Dog',
            'due_date' => now()->addDay()
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/v1/tasks');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['title' => 'Walk Dog']);
    }

    public function test_can_create_a_care_task(): void {
        $taskData = [
            'pet_id' => $this->pet->id,
            'user_id' => $this->user->id,
            'title' => 'Feed Cat',
            'description' => 'Give cat wet food',
            'due_date' => now()->addHours(2),
            'status' => 'pending'
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/v1/tasks', $taskData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Feed Cat']);
        $this->assertDatabaseHas('care_tasks', ['title' => 'Feed Cat']);
    }

    public function test_cannot_create_care_task_without_required_fields(): void {
        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/v1/tasks', [
            'pet_id' => $this->pet->id,
            // missing title
        ]);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_can_update_a_care_task(): void {
        $task = $this->pet->careTasks()->create([
            'user_id' => $this->user->id,
            'title' => 'Old Task',
            'status' => 'pending'
        ]);

        $updatedData = [
            'title' => 'Updated Task',
            'status' => 'completed',
            'completed_at' => now()
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/v1/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Task', 'status' => 'completed']);
        $this->assertDatabaseHas('care_tasks', ['id' => $task->id, 'title' => 'Updated Task']);
    }

    public function test_cannot_update_non_existent_care_task(): void {
        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/v1/tasks/9999', [
            'title' => 'Non Existent'
        ]);
        $response->assertStatus(404);
    }

    public function test_can_delete_a_care_task(): void {
        $task = $this->pet->careTasks()->create([
            'user_id' => $this->user->id,
            'title' => 'Task To Delete'
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('care_tasks', ['id' => $task->id]);
    }

    public function test_cannot_delete_non_existent_care_task(): void {
        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/v1/tasks/9999');
        $response->assertStatus(404);
    }

    public function test_cannot_access_care_tasks_without_authentication(): void {
        $response = $this->getJson('/api/v1/tasks');
        $response->assertStatus(401);
    }
}
