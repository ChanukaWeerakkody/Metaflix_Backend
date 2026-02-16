<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RolePermissionTest extends TestCase
{
    // use RefreshDatabase; // Ideally use RefreshDatabase, but might need to be careful with existing data if not using in-memory DB.
    // Given the user context, better to just create and delete or use transactions if possible. 
    // For now I'll use explicit creation/cleanup or just rely on factories.
    // Actually, RefreshDatabase is safest for tests.

    use RefreshDatabase;

    public function test_access_denied_for_unauthenticated_user()
    {
        $response = $this->getJson('/api/test-permission');

        $response->assertStatus(401);
    }

    public function test_access_denied_for_user_without_role()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/test-permission');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Forbidden: Insufficient Role']);
    }

    public function test_access_denied_for_user_with_wrong_role()
    {
        $role = Role::create(['role' => 'User', 'is_active' => 1]);
        $user = User::factory()->create(['role_id' => $role->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/test-permission');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Forbidden: Insufficient Role']);
    }

    public function test_access_denied_for_user_with_role_but_no_permission()
    {
        $role = Role::create(['role' => 'Admin', 'is_active' => 1]);
        $user = User::factory()->create(['role_id' => $role->id]);
        $token = JWTAuth::fromUser($user);

        // The middleware requires 'view_test' permission

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/test-permission');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Forbidden: Insufficient Permission']);
    }

    public function test_access_granted_for_user_with_role_and_permission()
    {
        $role = Role::create(['role' => 'Admin', 'is_active' => 1]);
        $permission = Permission::create([
            'role_id' => $role->id,
            'permission' => 'View Test',
            'permission_key' => 'view_test',
            'is_active' => 1
        ]);

        $user = User::factory()->create(['role_id' => $role->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/test-permission');

        $response->assertStatus(200)
            ->assertJson(['message' => 'You have permission!']);
    }
}
