<?php

namespace Tests\Feature\Users;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;
    use HelperTrait;

    private string $url = '/api/users';

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testNoAuthenticationInHeaderCase()
    {
        $response = $this->get($this->url . '/1');
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
        $this->assertEquals('token not found', $response->json('error.message'));
    }

    public function testNoPermissionCase()
    {
        $response = $this->get($this->url . '/1', $this->generateAuthorizationHeader(['role_id' => 3]));
        $response->assertStatus(403);
        $expectedResponse = [
            'error' => [
                'code' => 0,
                'message' => 'This action is unauthorized.',
            ]
        ];
        $this->assertEquals($expectedResponse, $response->json());
    }

    public function testPassCase()
    {
        $response = $this->get($this->url . '/1', $this->generateAuthorizationHeader(['role_id' => 1]));
        $response->assertStatus(200);
        $expectedResponse = [
            'id' => 1,
            'account' => 'admin01',
            'email' => '',
            'roleId' => 1
        ];
        $this->assertEquals($expectedResponse, $response->json());
    }
}
