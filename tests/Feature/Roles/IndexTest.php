<?php

namespace Tests\Feature\Roles;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    use HelperTrait;

    private string $url = '/api/roles';

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testPassCase()
    {
        $response = $this->get($this->url, $this->generateAuthorizationHeader(['role_id' => 1]));
        $response->assertStatus(200);
        $expectedResponse = [
            [
                'id' => 1,
                'name' => 'admin',
                'note' => 'This is a role for admin',
            ],
            [
                'id' => 2,
                'name' => 'user',
                'note' => 'This is a role for user'
            ]
        ];
        $this->assertEquals($expectedResponse, $response->json());
    }
}
