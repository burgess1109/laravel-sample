<?php

namespace Tests\Feature\Roles;

use App\Models\Role;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;
    use HelperTrait;

    private string $url = '/api/roles';

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testNoAuthenticationInHeaderCase()
    {
        $response = $this->delete($this->url . '/999');
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
        $this->assertEquals('token not found', $response->json('error.message'));
    }

    public function testNoPermissionCase()
    {
        $response = $this->delete($this->url . '/2', [], $this->generateAuthorizationHeader(['role_id' => 3]));
        $response->assertStatus(403);
        $expectedResponse = [
            'error' => [
                'code' => 0,
                'message' => 'This action is unauthorized.',
            ]
        ];
        $this->assertEquals($expectedResponse, $response->json());
    }

    public function testValidationFailedCase()
    {
        $response = $this->delete($this->url . '/999', [], $this->generateAuthorizationHeader(['role_id' => 1]));
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
    }

    public function testRoleInUseCase()
    {
        $response = $this->delete($this->url . '/2',  [], $this->generateAuthorizationHeader(['role_id' => 1]));
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
    }

    public function testPassCases()
    {
        Role::create(['id' => 3, 'name' => 'test']);
        $response = $this->delete($this->url . '/3',  [], $this->generateAuthorizationHeader(['role_id' => 1]));
        $response->assertStatus(200);
        $this->assertEquals(['result' => true], $response->json());
    }
}
