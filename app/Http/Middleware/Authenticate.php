<?php

namespace App\Http\Middleware;

use App\Services\SampleJWT;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Psr\Log\LoggerInterface;

class Authenticate
{
    private SampleJWT $sampleJWT;
    private LoggerInterface $logger;

    public function __construct(SampleJWT $sampleJWT, LoggerInterface $logger)
    {
        $this->sampleJWT = $sampleJWT;
        $this->logger = $logger;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (empty($token)) {
            throw new AuthenticationException('token not found');
        }

        try {
            $payload = $this->sampleJWT->decode($token);
        } catch (\Throwable $exception) {
            $this->logger->error('jwt decode failed: ' . $exception->getMessage());
            throw new AuthenticationException('token error');
        }

        $user = Redis::get($payload->jti);
        if (empty($user)) {
            $this->logger->error('no jti in redis: ' . $payload->jti);
            throw new AuthenticationException('login failed');
        }

        $request->attributes->set('jti', $payload->jti);
        return $next($request);
    }
}
