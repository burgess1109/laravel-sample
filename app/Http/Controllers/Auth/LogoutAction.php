<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LogoutAction extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(['result' => (bool)Redis::del($request->attributes->get('jti'))]);
    }
}
