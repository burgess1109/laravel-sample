<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Authenticate;
use App\Services\SampleJWT;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AuthenticateTest extends TestCase
{
    private SampleJWT $sampleJWT;
    private LoggerInterface $logger;
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->sampleJWT = $this->createMock(SampleJWT::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->request = new Request();
    }

    public function testTokenNotFound()
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('token not found');

        $authenticate = new Authenticate($this->sampleJWT, $this->logger);
        $authenticate->handle($this->request, function () {
        });
    }

    public function testJwtDecodeFailed()
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('token error');

        $this->sampleJWT->expects($this->once())->method('decode')
            ->willThrowException(new \Exception('something wrong'));
        $this->logger->expects($this->once())->method('error')
            ->with('jwt decode failed: something wrong');

        $this->request->headers->set('Authorization', 'Bearer test.123');
        $authenticate = new Authenticate($this->sampleJWT, $this->logger);
        $authenticate->handle($this->request, function () {
        });
    }

    public function testRedisHasNoUser()
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('login failed');

        $this->sampleJWT->expects($this->once())->method('decode')
            ->willReturn((object)['jti' => 123]);
        $this->logger->expects($this->once())->method('error')
            ->with('no jti in redis: 123');
        Redis::shouldReceive('get')->once()->with(123)->andReturn(null);

        $this->request->headers->set('Authorization', 'Bearer test.123.456');
        $authenticate = new Authenticate($this->sampleJWT, $this->logger);
        $authenticate->handle($this->request, function () {
        });
    }

    public function testRequestAttributesHasUser()
    {
        $user = (object)['id' => 1, 'account' => 'test'];

        $this->sampleJWT->expects($this->once())->method('decode')
            ->willReturn((object)['jti' => 123]);
        Redis::shouldReceive('get')->once()->with(123)->andReturn($user);
        $this->request->headers->set('Authorization', 'Bearer test.123.456');
        $authenticate = new Authenticate($this->sampleJWT, $this->logger);
        $authenticate->handle($this->request, function ($request) {
            $this->assertEquals(123, $request->attributes->get('jti'));
        });
    }
}
