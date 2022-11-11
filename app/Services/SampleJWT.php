<?php

namespace App\Services;

use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class SampleJWT
{
    public function encode($user): string
    {
        $jti = Str::uuid();
        $issueTime = strtotime('now');
        $expirationTime = strtotime(config('auth.jwt.expiration_time'), $issueTime);
        $payload = [
            'jti' => $jti, // JWT ID
            'iss' => 'laravel-sample',
            'aud' => 'laravel-sample',
            'iat' => $issueTime, // issued at
            'nbf' => $issueTime, // not before
            'exp' => $expirationTime, // expiration time
        ];
        Redis::set($jti, $user->toJson());
        Redis::expire($jti, $expirationTime - $issueTime);

        return JWT::encode($payload, config('app.key'), config('auth.jwt.alg'));
    }

    public function decode(string $token)
    {
        return JWT::decode($token, new Key(config('app.key'), config('auth.jwt.alg')));
    }
}
