<?php
namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    use HelperTrait;

    protected string $url;

    /**
     * @param string $url
     * @param string $method
     */
    public function testNoAuthenticationInHeaderCases(string $url, string $method)
    {
        switch ($method)
        {
            case 'post':
                $response = $this->post($url);
            case 'patch]':
                $response = $this->get($url);
            case 'delete':
                $response = $this->get($url);
            default:
                $response = $this->get($url);
        }

        $response->assertStatus(500);
        $response->assertJsonStructure(['error' => ['code', 'message']]);
        $this->assertEquals('token not found', $response->json('error.message'));
    }

    public function getUrlProvider()
    {
        return [
            'get roles' => ['/api/roles', 'get'],
            'show role' => ['/api/roles/1', 'get'],
            'create role' => ['/api/roles', 'post'],
            'get users' => ['/api/users', 'get'],
        ];
    }

    public function testNoPermissionCase()
    {
        $response = $this->get($this->url, $this->generateAuthorizationHeader(['role_id' => 3]));
        $response->assertStatus(403);
        $expectedResponse = [
            'error' => [
                'code' => 0,
                'message' => 'This action is unauthorized.',
            ]
        ];
        $this->assertEquals($expectedResponse, $response->json());
    }
}
