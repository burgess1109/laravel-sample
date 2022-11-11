<?php

namespace Tests\Feature\Users;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Feature\HelperTrait;
use Tests\TestCase;

class StoreTest extends TestCase
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
            'account' => 'test',
            'password' => 'test',
            'email' => 'test@abc.com',
            'roleId' => 1
        ];
        $response = $this->post($this->url, $createData, $this->generateAuthorizationHeader(['role_id' => 1]));
        $response->assertStatus(200);
        $expectedResponse = [
            'id' => 3,
            'account' => 'test',
            'email' => 'test@abc.com',
            'roleId' => 1
        ];
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
            'There is no params' => [[]],
            'There is no account' => [['password' => 'test', 'roleId' => 1]],
            'There is no password' => [['account' => 'test', 'roleId' => 1]],
            'There is no roleId' => [['account' => 'test', 'password' => 'test']],
            'The role id is not existed' => [['account' => 'test', 'password' => 'test', 'roleId' => 999]],
            'The format of email is wrong' => [
                ['account' => 'test', 'password' => 'test', 'roleId' => 999, 'email' => 'test']
            ],
            'The account is too long' => [
                ['account' => Str::random(256), 'password' => 'test', 'roleId' => 1]
            ]
        ];
    }
}
