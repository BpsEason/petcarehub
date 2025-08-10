<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
class PetApiTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create(); // Create a user for authentication
    }

    public function test_can_retrieve_pets_for_authenticated_user(): void {
        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/v1/pets');
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_can_create_a_pet(): void {
        $petData = [
            'user_id' => $this->user->id,
            'name' => 'Test Pet',
            'species' => 'dog',
            'breed' => 'Labrador',
            'birth_date' => '2022-01-01',
            'weight' => 25.5
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/v1/pets', $petData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Pet']);

        $this->assertDatabaseHas('pets', ['name' => 'Test Pet', 'user_id' => $this->user->id]);
    }

    public function test_cannot_create_pet_without_required_fields(): void {
        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/v1/pets', [
            'name' => 'Missing Species Pet' // missing species
        ]);
        $response->assertStatus(422) // Unprocessable Entity
                 ->assertJsonValidationErrors(['species']);
    }

    public function test_can_update_a_pet(): void {
        $pet = $this->user->pets()->create([
            'name' => 'Old Name', 'species' => 'cat'
        ]);

        $updatedData = [
            'name' => 'New Name', 'weight' => 5.2
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/v1/pets/{$pet->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'New Name']);
        
        $this->assertDatabaseHas('pets', ['id' => $pet->id, 'name' => 'New Name']);
    }

    public function test_cannot_update_non_existent_pet(): void {
        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/v1/pets/9999', [
            'name' => 'Non Existent'
        ]);
        $response->assertStatus(404); // Not Found
    }

    public function test_can_delete_a_pet(): void {
        $pet = $this->user->pets()->create([
            'name' => 'Pet To Delete', 'species' => 'bird'
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/v1/pets/{$pet->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }

    public function test_cannot_delete_non_existent_pet(): void {
        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/v1/pets/9999');
        $response->assertStatus(404); // Not Found
    }

    public function test_cannot_create_pet_without_authentication(): void {
        $petData = [ 'name' => 'Unauthorized Pet', 'species' => 'fish' ];
        $response = $this->postJson('/api/v1/pets', $petData);
        $response->assertStatus(401); // Unauthorized
    }
}
