<?php

namespace Tests\Feature\Roles;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class StoreTest extends TestCase
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
        $response = $this->post($this->url, []);
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
        $this->assertEquals('token not found', $response->json('error.message'));
    }

    public function testNoPermissionCase()
    {
        $response = $this->post($this->url, [], $this->generateAuthorizationHeader(['role_id' => 3]));
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
        $createData = [
            'name' => 'test',
            'note' => 'This is a test role',
            'permissionIds' => [1, 2, 3]
        ];
        $response = $this->post($this->url, $createData, $this->generateAuthorizationHeader(['role_id' => 1]));
        $response->assertStatus(200);
        $expectedResponse = ['id' => 3];
        $expectedResponse = array_merge($expectedResponse, $createData);
        $this->assertEquals($expectedResponse, $response->json());
    }

    /**
     * @dataProvider ValidationFailedCasesDataProvider
     * @param array $createData
     */
    public function testValidationFailedCases(array $createData)
    {
        $response = $this->post($this->url, $createData, $this->generateAuthorizationHeader());
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
    }

    public function ValidationFailedCasesDataProvider()
    {
        return [
            'There is no name' => [[]],
            'The name is too long' => [['name' => Str::random(256)]],
            'The permission id is not existed' => [['name' => 'test', 'permissionIds' => [99999]]],
        ];
    }
}
