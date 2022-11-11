<?php

namespace Tests\Feature\Roles;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class UpdateTest extends TestCase
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
        $response = $this->patch($this->url . '/2', []);
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
        $this->assertEquals('token not found', $response->json('error.message'));
    }

    public function testNoPermissionCase()
    {
        $response = $this->patch($this->url . '/2', [], $this->generateAuthorizationHeader(['role_id' => 3]));
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
        $updateData = [
            'name' => 'test',
            'note' => 'This is a test role',
            'permissionIds' => [1, 2, 3]
        ];
        $response = $this->patch(
            $this->url . '/2',
            $updateData,
            $this->generateAuthorizationHeader(['role_id' => 1])
        );
        $response->assertStatus(200);
        $expectedResponse = ['id' => 2];
        $expectedResponse = array_merge($expectedResponse, $updateData);
        $this->assertEquals($expectedResponse, $response->json());
    }

    /**
     * @dataProvider ValidationFailedCasesDataProvider
     * @param array $updateData
     */
    public function testValidationFailedCases(array $updateData)
    {
        $response = $this->patch($this->url . '/2', $updateData, $this->generateAuthorizationHeader());
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
