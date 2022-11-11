<?php

namespace Tests\Feature\Users;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class UpdateTest extends TestCase
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
            'password' => 'test',
            'email' => 'test@abc.com',
            'roleId' => 2
        ];
        $response = $this->patch(
            $this->url . '/2',
            $updateData,
            $this->generateAuthorizationHeader(['role_id' => 1])
        );
        $response->assertStatus(200);
        $expectedResponse = [
            'id' => 2,
            'account' => 'user01',
            'email' => 'test@abc.com',
            'roleId' => 2
        ];
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
            'The role id is not existed' => [['roleId' => 999]],
            'The format of email is wrong' => [['email' => 'test']],
            'The password is too long' => [['password' => Str::random(256)]]
        ];
    }
}
