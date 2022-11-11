<?php

namespace Tests\Feature\Users;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    use HelperTrait;

    private string $url = '/api/users';

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
                'account' => 'admin01',
                'email' => '',
            ],
            [
                'id' => 2,
                'account' => 'user01',
                'email' => 'user01@test.com'
            ]
        ];
        $this->assertEquals($expectedResponse, $response->json());
    }
}
