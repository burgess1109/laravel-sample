<?php

namespace Tests\Feature\Auth;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private string $url = '/api/login';

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * @dataProvider validationFailedCasesDataProvider
     * @param array $data
     */
    public function testValidationFailedCases(array $data)
    {
        $response = $this->post($this->url, $data);
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
    }

    public function validationFailedCasesDataProvider()
    {
        return [
            'there is no password' => [['account' => 'test']],
            'there is no account' => [['password' => 'test']],
        ];
    }

    /**
     * @dataProvider authenticationExceptionCasesDataProvider
     * @param array $data
     * @param string $expectedErrorMessage
     */
    public function testAuthenticationExceptionCases(array $data, string $expectedErrorMessage)
    {
        $response = $this->post($this->url, $data);
        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
        $this->assertEquals($expectedErrorMessage, $response->json('error.message'));
    }

    public function authenticationExceptionCasesDataProvider()
    {
        return [
            'user not found' => [['account' => 'test', 'password' => 'test'], 'user not found'],
            'password incorrect' => [['account' => 'user01', 'password' => 'test01'], 'login failed'],
        ];
    }

    public function testPassCases()
    {
        $response = $this->post($this->url, ['account' => 'user01', 'password' => 'test']);
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('token'));
    }
}
