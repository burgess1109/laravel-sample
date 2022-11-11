<?php

namespace Tests\Unit\Services;

use App\Services\SampleJWT;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class SampleJWTTest extends TestCase
{
    use CreatesApplication;

    public function testToken()
    {
        $this->createApplication();

        Redis::shouldReceive('set')->once()->andReturn(true);
        Redis::shouldReceive('expire')->once()->andReturn(true);

        $user = ['id' => 123, 'account' => 'test'];
        $sampleJWT = new SampleJWT();
        $token = $sampleJWT->encode($user);
        $payload = $sampleJWT->decode($token);

        $this->assertObjectHasAttribute('jti', $payload);
    }
}

