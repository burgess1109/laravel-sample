<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SampleJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RefreshAction extends Controller
{
    private SampleJWT $sampleJWT;

    public function __construct(SampleJWT $sampleJWT)
    {
        $this->sampleJWT = $sampleJWT;
    }

    public function __invoke(Request $request)
    {
        $user = $request->attributes->get('jti');
        Redis::del($request->attributes->get('jti'));
        return response()->json(['token' => $this->sampleJWT->encode($user)]);
    }
}
