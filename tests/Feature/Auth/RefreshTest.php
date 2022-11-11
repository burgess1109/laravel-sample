<?php

namespace Tests\Feature\Auth;

use App\Services\SampleJWT;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class RefreshTest extends TestCase
{
    use RefreshDatabase;
    use HelperTrait;

    private string $url = '/api/refresh';

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testNoAuthorizationInHeaderCases()
    {
        $response = $this->post($this->url);
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
        $this->assertEquals('token not found', $response->json('error.message'));
    }

    public function testPassCases()
    {
        $response = $this->post($this->url, [], $this->generateAuthorizationHeader());
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('token'));
    }
}
