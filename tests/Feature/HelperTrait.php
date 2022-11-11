<?php

namespace Tests\Feature;

use App\Services\SampleJWT;

trait HelperTrait
{
    public function generateAuthorizationHeader($user = []): array
    {
        $sampleJWT = app(SampleJWT::class);
        return ['Authorization' => 'Bearer ' . $sampleJWT->encode(collect($user))];
    }
}
