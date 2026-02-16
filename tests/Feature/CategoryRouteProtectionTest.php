<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryRouteProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_create_route_is_protected_and_requires_admin_role()
    {
        // 1. Test Unauthenticated
        $this->postJson('/api/category/create', [])
            ->assertStatus(401);

        // 2. Test Authenticated but User role (Not Admin)
        $userRole = Role::create(['role' => 'User', 'is_active' => 1]);
        $user = User::factory()->create(['role_id' => $userRole->id]);
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/category/create', [])
            ->assertStatus(403)
            ->assertJson(['message' => 'Forbidden: Insufficient Role']);

        // 3. Test Authenticated with Admin role (Should pass middleware, but might fail validation or controller logic, which is fine for this test)
        // We just want to ensure it passes the 403 check.

        $adminRole = Role::create(['role' => 'Admin', 'is_active' => 1]);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $adminToken = JWTAuth::fromUser($admin);

        // Note: This might return 422 (validation error) or 200/201, but NOT 403 or 401.
        $response = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
            ->postJson('/api/category/create', []);

        // Assert status is NOT 401 or 403
        $this->assertNotEquals(401, $response->status());
        $this->assertNotEquals(403, $response->status());
    }
}
