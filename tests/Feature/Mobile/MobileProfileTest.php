<?php

namespace Tests\Feature\Mobile;

use App\Models\User;
use App\Support\JwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_user_can_update_profile_with_valid_jwt(): void
    {
        $user = User::factory()->create([
            'name' => 'Before Name',
            'email' => 'before@example.com',
        ]);

        /** @var JwtService $jwtService */
        $jwtService = $this->app->make(JwtService::class);
        $token = $jwtService->issueToken($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson('/api/v1/mobile/auth/me', [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.email', 'updated@example.com');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }
}
