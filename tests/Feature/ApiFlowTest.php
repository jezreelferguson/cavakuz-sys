<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_receive_a_token(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('user.role', 'admin')
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'role'],
            ]);
    }

    public function test_admin_can_create_another_user_with_a_role(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin-creator@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'api')->postJson('/api/users', [
            'name' => 'Assistant User',
            'email' => 'assistant-2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'assistant',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.role', 'assistant')
            ->assertJsonPath('user.email', 'assistant-2@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'assistant-2@example.com',
            'role' => 'assistant',
        ]);
    }

    public function test_assistant_can_manage_customers_and_sales(): void
    {
        $assistant = User::factory()->create([
            'role' => 'assistant',
        ]);

        $customerResponse = $this->actingAs($assistant, 'api')->postJson('/api/customers', [
            'name' => 'Jane Doe',
            'phone_number' => '+123456789',
            'location' => 'Nairobi',
            'company' => 'BlueWave',
            'email' => 'jane@example.com',
        ]);

        $customerResponse->assertStatus(201);

        $customer = Customer::first();

        $inventory = InventoryItem::create([
            'name' => 'Premium Service',
            'category' => 'service',
            'unit_price' => 1500,
            'stock_quantity' => 10,
            'description' => 'Premium service plan',
        ]);

        $saleResponse = $this->actingAs($assistant, 'api')->postJson('/api/sales', [
            'customer_id' => $customer->id,
            'payment_method' => 'cash',
            'payment_status' => 'full_payment',
            'items' => [
                [
                    'inventory_id' => $inventory->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $saleResponse->assertStatus(201)
            ->assertJsonPath('data.customer_id', $customer->id)
            ->assertJsonPath('data.total_amount', 3000);
    }

    public function test_sanctum_bearer_token_authentication_works_without_recursion(): void
    {
        $user = User::factory()->create([
            'name' => 'Bearer User',
            'email' => 'bearer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'assistant',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/me');

        $response->assertOk()
            ->assertJsonPath('user.email', 'bearer@example.com');
    }

    public function test_admin_only_dashboard_is_restricted_for_assistants(): void
    {
        $assistant = User::factory()->create([
            'role' => 'assistant',
        ]);

        $response = $this->actingAs($assistant, 'api')->getJson('/api/admin/dashboard');

        $response->assertStatus(403);
    }
}
